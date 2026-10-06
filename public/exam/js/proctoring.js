(function (window, document) {
    'use strict';

    var RankProProctoring = {
        config: {},
        stream: null,
        video: null,
        canvas: null,
        started: false,
        ended: false,
        violationCount: 0,
        webcamStrikeCount: 0,
        lastViolationAt: 0,
        lastWebcamStrikeAt: 0,
        snapshotTimer: null,
        faceTimer: null,
        noFaceStreak: 0,
        extraPersonStreak: 0,
        awayAfterWarnStreak: 0,
        modelsReady: false,
        ssdReady: false,
        tinyReady: false,
        landmarksReady: false,
        lastPresentAt: 0,
        lastEyesAt: 0,
        lastMotionAt: 0,
        graceUntil: 0,
        warningOpen: false,
        webcamWarningShown: false,
        lockType: null,
        lockTimer: null,
        guardsBound: false,
        resumeGraceUntil: 0,
        checking: false,
        prevGray: null,
        referenceGray: null,
        referenceReady: false,
        recognitionReady: false,
        fullLandmarksReady: false,
        tinyLandmarksReady: false,
        profileDescriptor: null,
        profileLoadAttempted: false,
        sessionDescriptor: null,
        identityVerified: false,
        identityMismatchStreak: 0,
        earHistory: [],
        blinkCount: 0,
        lastBlinkAt: 0,
        lastLandmarkPoint: null,
        landmarkMotionScore: 0,
        stagnantStreak: 0,
        noBlinkStreak: 0,
        verifyTimer: null,
        verifyingIdentity: false,
        identityLocked: false,

        init: function (config) {
            this.config = config || {};
            if (!this.config.enabled) {
                return;
            }
            this.video = document.getElementById('proctoringVideo');
            this.canvas = document.getElementById('proctoringCanvas');
            this.bindUi();
            this.loadModels();
        },

        bindUi: function () {
            var self = this;
            var startBtn = document.getElementById('proctoringStartBtn');
            var cameraBtn = document.getElementById('proctoringCameraBtn');
            var resumeBtn = document.getElementById('proctoringResumeBtn');

            if (cameraBtn) {
                cameraBtn.addEventListener('click', function () {
                    if (cameraBtn.disabled || self.stream) {
                        return;
                    }
                    self.requestCamera();
                });
            }
            if (startBtn) {
                startBtn.addEventListener('click', function () {
                    if (!self.identityVerified) {
                        self.setStatus(
                            document.getElementById('proctoringCameraStatus'),
                            'Face must match your profile photo before starting.',
                            true
                        );
                        self.beginIdentityVerifyLoop();
                        return;
                    }
                    self.startExam();
                });
            }
            if (resumeBtn) {
                resumeBtn.addEventListener('click', function () {
                    // Identity lock clears only after live face rematches profile photo.
                    if (self.identityLocked) {
                        self.setFaceStatus('Waiting for profile face match...', true);
                        self.checkWebcam();
                        return;
                    }
                    self.enterFullscreen();
                    self.resumeGraceUntil = Date.now() + 2000;
                    if (self.lockType === 'webcam') {
                        // Let them sit back down; do not pretend they are already present.
                        self.noFaceStreak = 0;
                        self.extraPersonStreak = 0;
                        self.awayAfterWarnStreak = 0;
                        self.webcamWarningShown = false;
                        self.graceUntil = Date.now() + 4000;
                    }
                    self.lockType = null;
                    self.hideWarning();
                    setTimeout(function () {
                        if (self.started && !self.ended && !self.isFullscreen() && !self.identityLocked) {
                            self.lockToExam('fullscreen', 'Stay in fullscreen. Click Return to Exam to continue.');
                        }
                    }, 1800);
                });
            }
        },

        loadModels: function () {
            var self = this;
            if (!window.faceapi || !this.config.modelUrl) {
                return;
            }
            var url = this.config.modelUrl;
            var tiny = window.faceapi.nets.tinyFaceDetector.loadFromUri(url).then(function () {
                self.tinyReady = true;
            }).catch(function () {
                self.tinyReady = false;
            });
            var ssd = window.faceapi.nets.ssdMobilenetv1.loadFromUri(url).then(function () {
                self.ssdReady = true;
            }).catch(function () {
                self.ssdReady = false;
            });
            var tinyLandmarks = window.faceapi.nets.faceLandmark68TinyNet.loadFromUri(url).then(function () {
                self.tinyLandmarksReady = true;
            }).catch(function () {
                self.tinyLandmarksReady = false;
            });
            var fullLandmarks = window.faceapi.nets.faceLandmark68Net.loadFromUri(url).then(function () {
                self.fullLandmarksReady = true;
            }).catch(function () {
                self.fullLandmarksReady = false;
            });
            var recognition = window.faceapi.nets.faceRecognitionNet.loadFromUri(url).then(function () {
                self.recognitionReady = true;
            }).catch(function (err) {
                self.recognitionReady = false;
                console.warn('Face recognition model failed to load', err);
            });
            Promise.all([tiny, ssd, tinyLandmarks, fullLandmarks, recognition]).then(function () {
                self.landmarksReady = !!(self.tinyLandmarksReady || self.fullLandmarksReady);
                self.modelsReady = !!(self.ssdReady || self.tinyReady);
                if (!self.recognitionReady) {
                    self.setStatus(
                        document.getElementById('proctoringIdentityStatus'),
                        'Face recognition model failed to load. Refresh the page and try again.',
                        true
                    );
                    self.setScanLabel('Recognition model failed', 'error');
                }
                return self.loadProfileDescriptor(true);
            }).then(function () {
                if (self.stream) {
                    self.beginIdentityVerifyLoop();
                }
            }).catch(function (err) {
                console.warn('Proctoring model bootstrap failed', err);
            });
        },

        useTinyLandmarks: function () {
            // face-api withFaceLandmarks(true) = tiny net; false/undefined = full net.
            return !this.fullLandmarksReady && !!this.tinyLandmarksReady;
        },

        loadProfileDescriptor: function (force) {
            var self = this;
            var statusEl = document.getElementById('proctoringIdentityStatus');
            if (!force && this.profileLoadAttempted && this.profileDescriptor) {
                return Promise.resolve(this.profileDescriptor);
            }
            if (!this.config.profileImageUrl) {
                this.setStatus(statusEl, 'Upload a clear profile photo before taking a proctored exam.', true);
                this.setScanLabel('Profile photo required', 'error');
                return Promise.resolve(null);
            }
            if (!window.faceapi || !this.recognitionReady || !this.landmarksReady) {
                this.setStatus(statusEl, 'Loading face recognition models...', false);
                this.setScanLabel('Loading face models...', null);
                return Promise.resolve(null);
            }

            this.profileLoadAttempted = true;
            this.setStatus(statusEl, 'Loading profile face for identity match...', false);
            this.setScanLabel('Loading profile face...', null);

            return this.fetchProfileImageElement(this.config.profileImageUrl).then(function (img) {
                if (!img) {
                    self.setStatus(statusEl, 'Profile photo could not be loaded. Update it on Profile, then retry.', true);
                    self.setScanLabel('Profile photo load failed', 'error');
                    return null;
                }
                return self.detectSingleDescriptor(img).then(function (desc) {
                    self.profileDescriptor = desc;
                    if (!desc) {
                        self.setStatus(statusEl, 'Could not detect a face in your profile photo. Update it on Profile, then retry.', true);
                        self.setScanLabel('No face in profile photo', 'error');
                    } else {
                        self.setStatus(statusEl, 'Profile face loaded. Look at the camera to verify identity.', false);
                        self.setScanLabel('Profile face ready — scanning camera...', null);
                    }
                    return desc;
                });
            }).catch(function (err) {
                console.warn('Profile descriptor load failed', err);
                self.setStatus(statusEl, 'Could not read profile photo for face match.', true);
                self.setScanLabel('Profile face read failed', 'error');
                return null;
            });
        },

        fetchProfileImageElement: function (url) {
            return new Promise(function (resolve) {
                var finishWithImg = function (src) {
                    var img = new Image();
                    img.onload = function () {
                        if (img.naturalWidth < 8 || img.naturalHeight < 8) {
                            resolve(null);
                            return;
                        }
                        resolve(img);
                    };
                    img.onerror = function () { resolve(null); };
                    img.src = src;
                };

                // Prefer fetch+blob so we avoid cache/CORS edge cases on local assets.
                if (typeof fetch === 'function') {
                    fetch(url, { credentials: 'same-origin', cache: 'no-store' }).then(function (res) {
                        if (!res.ok) {
                            finishWithImg(url + (url.indexOf('?') >= 0 ? '&' : '?') + 't=' + Date.now());
                            return null;
                        }
                        return res.blob();
                    }).then(function (blob) {
                        if (!blob) return;
                        var objUrl = URL.createObjectURL(blob);
                        var img = new Image();
                        img.onload = function () {
                            URL.revokeObjectURL(objUrl);
                            resolve(img);
                        };
                        img.onerror = function () {
                            URL.revokeObjectURL(objUrl);
                            finishWithImg(url + (url.indexOf('?') >= 0 ? '&' : '?') + 't=' + Date.now());
                        };
                        img.src = objUrl;
                    }).catch(function () {
                        finishWithImg(url + (url.indexOf('?') >= 0 ? '&' : '?') + 't=' + Date.now());
                    });
                    return;
                }
                finishWithImg(url + (url.indexOf('?') >= 0 ? '&' : '?') + 't=' + Date.now());
            });
        },

        detectSingleDescriptor: function (input) {
            var self = this;
            if (!input || !window.faceapi || !this.recognitionReady || !this.landmarksReady) {
                return Promise.resolve(null);
            }

            var useTinyLm = this.useTinyLandmarks();
            var attempts = [];
            if (this.ssdReady) {
                attempts.push(new window.faceapi.SsdMobilenetv1Options({ minConfidence: 0.35 }));
                attempts.push(new window.faceapi.SsdMobilenetv1Options({ minConfidence: 0.25 }));
            }
            if (this.tinyReady) {
                attempts.push(new window.faceapi.TinyFaceDetectorOptions({ inputSize: 416, scoreThreshold: 0.25 }));
                attempts.push(new window.faceapi.TinyFaceDetectorOptions({ inputSize: 320, scoreThreshold: 0.2 }));
            }
            if (!attempts.length) {
                return Promise.resolve(null);
            }

            var runOne = function (options) {
                return window.faceapi
                    .detectSingleFace(input, options)
                    .withFaceLandmarks(useTinyLm)
                    .withFaceDescriptor()
                    .then(function (det) {
                        return det && det.descriptor ? det.descriptor : null;
                    })
                    .catch(function () {
                        // Fallback: detectAllFaces then take strongest match.
                        return window.faceapi
                            .detectAllFaces(input, options)
                            .withFaceLandmarks(useTinyLm)
                            .withFaceDescriptors()
                            .then(function (faces) {
                                if (!faces || !faces.length || !faces[0].descriptor) {
                                    return null;
                                }
                                return faces[0].descriptor;
                            })
                            .catch(function () { return null; });
                    });
            };

            var tryAt = function (index) {
                if (index >= attempts.length) {
                    return Promise.resolve(null);
                }
                return runOne(attempts[index]).then(function (desc) {
                    if (desc) return desc;
                    return tryAt(index + 1);
                });
            };
            return tryAt(0);
        },

        descriptorDistance: function (a, b) {
            if (!a || !b || !window.faceapi || typeof window.faceapi.euclideanDistance !== 'function') {
                return 999;
            }
            try {
                return window.faceapi.euclideanDistance(a, b);
            } catch (e) {
                return 999;
            }
        },

        beginIdentityVerifyLoop: function () {
            var self = this;
            if (this.verifyTimer) {
                return;
            }
            this.verifyingIdentity = true;
            this.verifyTimer = setInterval(function () {
                self.verifyIdentityOnce();
            }, 1500);
            this.verifyIdentityOnce();
        },

        stopIdentityVerifyLoop: function () {
            if (this.verifyTimer) {
                clearInterval(this.verifyTimer);
                this.verifyTimer = null;
            }
            this.verifyingIdentity = false;
        },

        getGateDetectCanvas: function () {
            var gateVideo = document.getElementById('proctoringGateVideo');
            var source = null;
            if (gateVideo && gateVideo.videoWidth) {
                source = gateVideo;
            } else if (this.video && this.video.videoWidth) {
                source = this.video;
            }
            if (!source) {
                return null;
            }
            if (!this.gateSampleCanvas) {
                this.gateSampleCanvas = document.createElement('canvas');
            }
            var w = 420;
            var h = Math.round((source.videoHeight / source.videoWidth) * w) || 320;
            this.gateSampleCanvas.width = w;
            this.gateSampleCanvas.height = h;
            this.gateSampleCanvas.getContext('2d').drawImage(source, 0, 0, w, h);
            return this.gateSampleCanvas;
        },

        verifyIdentityOnce: function () {
            var self = this;
            var statusEl = document.getElementById('proctoringIdentityStatus');
            var startBtn = document.getElementById('proctoringStartBtn');
            if (this.identityVerified || this.started) {
                this.stopIdentityVerifyLoop();
                return;
            }
            if (!this.stream) {
                return;
            }
            var gateVideo = document.getElementById('proctoringGateVideo');
            var hasFrames = (gateVideo && gateVideo.videoWidth) || (this.video && this.video.videoWidth);
            if (!hasFrames) {
                this.setScanLabel('Waiting for camera frames...', null);
                return;
            }
            if (!this.config.profileImageUrl) {
                this.setStatus(statusEl, 'Upload a clear profile photo on Profile before starting.', true);
                this.setScanLabel('Profile photo required', 'error');
                if (startBtn) startBtn.disabled = true;
                return;
            }
            if (!this.modelsReady || !this.recognitionReady || !this.landmarksReady) {
                this.setStatus(statusEl, 'Loading face recognition models...', false);
                this.setScanLabel('Loading face models...', null);
                return;
            }
            if (!this.profileDescriptor) {
                if (!this._profileReloadBusy) {
                    this._profileReloadBusy = true;
                    this.setStatus(statusEl, 'Loading profile face for identity match...', false);
                    this.setScanLabel('Loading profile face...', null);
                    this.loadProfileDescriptor(true).then(function () {
                        self._profileReloadBusy = false;
                    }, function () {
                        self._profileReloadBusy = false;
                    });
                }
                return;
            }
            if (this._identityScanBusy) {
                return;
            }

            this._identityScanBusy = true;
            this.setScanLabel('Scanning face...', null);
            var input = this.getGateDetectCanvas();
            this.detectCandidate(input, true).then(function (result) {
                self._identityScanBusy = false;
                if (self.identityVerified || self.started) {
                    return;
                }
                if (result.faceCount !== 1 || !result.descriptor) {
                    self.setStatus(statusEl, 'Show one clear face to the camera for identity match.', true);
                    self.setScanLabel('No clear face detected', 'error');
                    if (startBtn) startBtn.disabled = true;
                    return;
                }
                // Soft eye check at gate (glasses / lighting can lower EAR).
                if (!result.eyesVisible && !result.eyesOpen && result.label === 'face') {
                    self.setStatus(statusEl, 'Face the camera with eyes open for identity verification.', true);
                    self.setScanLabel('Keep eyes visible to camera', 'error');
                    if (startBtn) startBtn.disabled = true;
                    return;
                }

                var dist = self.descriptorDistance(result.descriptor, self.profileDescriptor);
                var threshold = self.config.identityMatchThreshold || 0.62;
                if (dist <= threshold) {
                    self.identityVerified = true;
                    self.sessionDescriptor = result.descriptor;
                    self.stopIdentityVerifyLoop();
                    self.setStatus(statusEl, 'Identity matched with profile photo. You can start the exam.', false);
                    self.setScanLabel('Identity matched — ready to start', 'ok');
                    if (startBtn) startBtn.disabled = false;
                    self.logEvent('identity_matched', 'Live face matched profile photo (distance ' + dist.toFixed(3) + ')');
                } else {
                    self.setStatus(
                        statusEl,
                        'Face does not match your profile photo. Sit facing the camera with good light.',
                        true
                    );
                    self.setScanLabel('Face does not match profile (' + dist.toFixed(2) + ')', 'error');
                    if (startBtn) startBtn.disabled = true;
                }
            }).catch(function () {
                self._identityScanBusy = false;
            });
        },

        setCameraButtonEnabled: function (enabled) {
            var cameraBtn = document.getElementById('proctoringCameraBtn');
            if (!cameraBtn) return;
            cameraBtn.disabled = !enabled;
            cameraBtn.style.pointerEvents = enabled ? '' : 'none';
            cameraBtn.style.opacity = enabled ? '' : '0.65';
            cameraBtn.textContent = enabled ? 'Allow Camera' : 'Camera Allowed';
        },

        requestCamera: function () {
            var self = this;
            var statusEl = document.getElementById('proctoringCameraStatus');
            if (this.stream) {
                this.setCameraButtonEnabled(false);
                return;
            }
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                this.setStatus(statusEl, 'Camera is not supported in this browser.', true);
                return;
            }
            this.setCameraButtonEnabled(false);
            this.setStatus(statusEl, 'Requesting camera permission...', false);
            navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'user',
                    width: { ideal: 640 },
                    height: { ideal: 480 }
                },
                audio: false
            }).then(function (stream) {
                self.stream = stream;
                if (self.video) {
                    self.video.srcObject = stream;
                    self.video.play().catch(function () {});
                }
                self.showGateCamera(stream);
                self.setCameraButtonEnabled(false);
                self.setStatus(statusEl, 'Camera connected. Keep your face clearly visible for identity match.', false);
                self.setScanLabel('Scanning face for identity match...', null);
                var startBtn = document.getElementById('proctoringStartBtn');
                if (startBtn) {
                    startBtn.disabled = !self.identityVerified;
                }
                stream.getVideoTracks().forEach(function (track) {
                    track.addEventListener('ended', function () {
                        if (self.started && !self.ended) {
                            self.recordWebcamStrike('camera_lost', 'Camera was disconnected');
                        }
                    });
                });
                // Wait briefly so gate video has frames before identity scan.
                setTimeout(function () {
                    if (self.modelsReady) {
                        self.loadProfileDescriptor(true).then(function () {
                            self.beginIdentityVerifyLoop();
                        });
                    } else {
                        self.beginIdentityVerifyLoop();
                    }
                }, 700);
            }).catch(function () {
                self.stream = null;
                self.setCameraButtonEnabled(true);
                self.setStatus(statusEl, 'Camera permission is required to start this exam.', true);
                self.hideGateCamera();
            });
        },

        showGateCamera: function (stream) {
            var stage = document.getElementById('proctoringScanStage');
            var gateVideo = document.getElementById('proctoringGateVideo');
            if (stage) {
                stage.classList.add('is-active');
            }
            if (gateVideo && stream) {
                gateVideo.srcObject = stream;
                gateVideo.play().catch(function () {});
            }
        },

        hideGateCamera: function () {
            var stage = document.getElementById('proctoringScanStage');
            var gateVideo = document.getElementById('proctoringGateVideo');
            if (stage) {
                stage.classList.remove('is-active');
            }
            if (gateVideo) {
                gateVideo.srcObject = null;
            }
            this.setScanLabel('Scanning face...', null);
        },

        setScanLabel: function (text, state) {
            var label = document.getElementById('proctoringScanLabel');
            var frame = document.getElementById('proctoringScanFrame');
            if (label) {
                label.textContent = text || 'Scanning face...';
                label.classList.remove('is-error', 'is-ok');
                if (state === 'error') label.classList.add('is-error');
                if (state === 'ok') label.classList.add('is-ok');
            }
            if (frame) {
                frame.classList.remove('is-error', 'is-ok');
                if (state === 'error') frame.classList.add('is-error');
                if (state === 'ok') frame.classList.add('is-ok');
            }
        },

        startExam: function () {
            if (!this.stream) {
                this.requestCamera();
                return;
            }
            if (!this.identityVerified || !this.sessionDescriptor) {
                this.beginIdentityVerifyLoop();
                this.setStatus(
                    document.getElementById('proctoringIdentityStatus'),
                    'Identity match with profile photo is required before starting.',
                    true
                );
                return;
            }
            this.stopIdentityVerifyLoop();
            this.hideGateCamera();
            this.enterFullscreen();
            this.started = true;
            this.ended = false;
            this.graceUntil = Date.now() + 10000;
            this.lastPresentAt = Date.now();
            this.lastMotionAt = Date.now();
            this.lastBlinkAt = Date.now();
            this.noFaceStreak = 0;
            this.awayAfterWarnStreak = 0;
            this.webcamStrikeCount = 0;
            this.webcamWarningShown = false;
            this.identityLocked = false;
            this.identityMismatchStreak = 0;
            if (document.body) {
                document.body.classList.remove('exam-identity-locked');
            }
            this.stagnantStreak = 0;
            this.noBlinkStreak = 0;
            this.earHistory = [];
            this.blinkCount = 0;
            this.lastLandmarkPoint = null;
            this.landmarkMotionScore = 0;
            this.referenceReady = false;
            this.referenceGray = null;
            this.prevGray = null;
            document.body.classList.add('exam-proctored-active');
            var overlay = document.getElementById('proctoringGate');
            if (overlay) {
                overlay.style.display = 'none';
            }
            this.bindExamGuards();
            this.startLockWatch();
            this.logEvent('started', 'Proctored exam started after profile face match');
            this.startSnapshots();
            this.startFaceMonitor();
            if (typeof window.beginExamTimer === 'function') {
                window.beginExamTimer();
            }
            var self = this;
            setTimeout(function () {
                self.captureReference();
            }, 1200);
        },

        enterFullscreen: function () {
            var el = document.documentElement;
            var request = el.requestFullscreen || el.webkitRequestFullscreen || el.mozRequestFullScreen || el.msRequestFullscreen;
            if (request) {
                try {
                    request.call(el);
                } catch (e) {}
            }
        },

        isFullscreen: function () {
            return !!(document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement);
        },

        bindExamGuards: function () {
            if (this.guardsBound) {
                return;
            }
            this.guardsBound = true;
            var self = this;

            document.addEventListener('keydown', function (e) {
                if (!self.started || self.ended) {
                    return;
                }
                if (e.key === 'Escape' || e.keyCode === 27) {
                    e.preventDefault();
                    e.stopPropagation();
                    self.lockToExam('fullscreen', 'You pressed Esc and left fullscreen. Click Return to Exam to continue.');
                    self.recordViolation('fullscreen_exit', 'Student pressed Esc / left fullscreen');
                }
            }, true);

            document.addEventListener('visibilitychange', function () {
                if (document.hidden && self.started && !self.ended && !self.isExamModalOpen()) {
                    self.lockToExam('focus', 'You switched away from the exam. Click Return to Exam to continue in fullscreen.');
                    self.recordViolation('tab_switch', 'Student switched tab or minimized the window');
                }
            });

            window.addEventListener('blur', function () {
                if (self.started && !self.ended && !self.warningOpen && !self.isExamModalOpen()) {
                    self.lockToExam('focus', 'The exam window lost focus. Click Return to Exam to continue in fullscreen.');
                    self.recordViolation('tab_switch', 'Exam window lost focus');
                }
            });

            var onFullscreenLeave = function () {
                if (!self.isFullscreen() && self.started && !self.ended && !self.isExamModalOpen()) {
                    self.lockToExam('fullscreen', 'Stay in fullscreen. Click Return to Exam to continue.');
                    self.recordViolation('fullscreen_exit', 'Student left fullscreen');
                }
            };
            document.addEventListener('fullscreenchange', onFullscreenLeave);
            document.addEventListener('webkitfullscreenchange', onFullscreenLeave);
            document.addEventListener('mozfullscreenchange', onFullscreenLeave);
            document.addEventListener('MSFullscreenChange', onFullscreenLeave);

            document.addEventListener('contextmenu', function (e) {
                e.preventDefault();
                self.recordViolation('right_click', 'Right click blocked');
            });

            document.addEventListener('copy', function (e) {
                e.preventDefault();
                self.recordViolation('copy_attempt', 'Copy attempt blocked');
            });
            document.addEventListener('cut', function (e) {
                e.preventDefault();
                self.recordViolation('copy_attempt', 'Cut attempt blocked');
            });
            document.addEventListener('paste', function (e) {
                e.preventDefault();
                self.recordViolation('paste_attempt', 'Paste attempt blocked');
            });

            document.addEventListener('keydown', function (e) {
                var key = (e.key || '').toLowerCase();
                var blocked = false;
                if ((e.ctrlKey || e.metaKey) && ['c', 'v', 'x', 'u', 's', 'p'].indexOf(key) !== -1) {
                    blocked = true;
                }
                if (e.key === 'F12' || ((e.ctrlKey || e.metaKey) && e.shiftKey && ['i', 'j', 'c'].indexOf(key) !== -1)) {
                    blocked = true;
                }
                if (e.key === 'PrintScreen') {
                    blocked = true;
                }
                if (blocked) {
                    e.preventDefault();
                    self.recordViolation('copy_attempt', 'Blocked keyboard shortcut: ' + e.key);
                }
            });
        },

        isExamModalOpen: function () {
            return !!(document.body && document.body.classList.contains('modal-active'));
        },

        lockToExam: function (type, message) {
            if (!this.started || this.ended || this.isExamModalOpen()) {
                return;
            }
            this.lockType = type;
            this.showWarning(message, false);
        },

        startLockWatch: function () {
            var self = this;
            if (this.lockTimer) {
                clearInterval(this.lockTimer);
            }
            this.lockTimer = setInterval(function () {
                if (!self.started || self.ended || self.isExamModalOpen()) {
                    return;
                }
                if (Date.now() < (self.resumeGraceUntil || 0)) {
                    return;
                }
                if (Date.now() < self.graceUntil && !self.lockType) {
                    return;
                }
                if (!self.isFullscreen() && self.lockType !== 'webcam' && !self.identityLocked) {
                    self.lockToExam('fullscreen', 'Stay in fullscreen. Click Return to Exam to continue.');
                }
            }, 2000);
        },

        startFaceMonitor: function () {
            var self = this;
            this.checkWebcam();
            this.faceTimer = setInterval(function () {
                self.checkWebcam();
            }, this.config.faceInterval || 2500);
        },

        getSampleCanvas: function (width) {
            if (!this.video || !this.video.videoWidth) {
                return null;
            }
            if (!this.sampleCanvas) {
                this.sampleCanvas = document.createElement('canvas');
            }
            var w = width || 160;
            var h = Math.round((this.video.videoHeight / this.video.videoWidth) * w) || 120;
            this.sampleCanvas.width = w;
            this.sampleCanvas.height = h;
            this.sampleCanvas.getContext('2d').drawImage(this.video, 0, 0, w, h);
            return this.sampleCanvas;
        },

        getDetectCanvas: function () {
            return this.getSampleCanvas(420);
        },

        getCenterCropCanvas: function () {
            if (!this.video || !this.video.videoWidth) {
                return null;
            }
            if (!this.cropCanvas) {
                this.cropCanvas = document.createElement('canvas');
            }
            var vw = this.video.videoWidth;
            var vh = this.video.videoHeight;
            var cw = Math.round(vw * 0.78);
            var ch = Math.round(vh * 0.82);
            var sx = Math.round((vw - cw) / 2);
            var sy = Math.max(0, Math.round(vh * 0.08));
            this.cropCanvas.width = 360;
            this.cropCanvas.height = Math.round(360 * ch / cw) || 300;
            this.cropCanvas.getContext('2d').drawImage(
                this.video, sx, sy, cw, ch,
                0, 0, this.cropCanvas.width, this.cropCanvas.height
            );
            return this.cropCanvas;
        },

        toGray: function (data, width, height) {
            var gray = new Float32Array(width * height);
            for (var y = 0; y < height; y += 1) {
                for (var x = 0; x < width; x += 1) {
                    var i = ((y * width) + x) * 4;
                    gray[(y * width) + x] = (data[i] * 0.299) + (data[i + 1] * 0.587) + (data[i + 2] * 0.114);
                }
            }
            return gray;
        },

        meanAbsDiff: function (a, b) {
            if (!a || !b || a.length !== b.length) {
                return 255;
            }
            var total = 0;
            for (var i = 0; i < a.length; i += 1) {
                total += Math.abs(a[i] - b[i]);
            }
            return total / a.length;
        },

        isSkinPixel: function (r, g, b) {
            var max = Math.max(r, g, b);
            var min = Math.min(r, g, b);
            var y = (0.299 * r) + (0.587 * g) + (0.114 * b);
            var cb = 128 - (0.168736 * r) - (0.331264 * g) + (0.5 * b);
            var cr = 128 + (0.5 * r) - (0.418688 * g) - (0.081312 * b);
            // Tighter ranges so pink walls are not treated as skin.
            var ycbcrOk = y > 45 && y < 220
                && cb > 80 && cb < 130
                && cr > 132 && cr < 172;
            var rgbOk = r > 55 && g > 25 && b > 15
                && r > g + 8
                && r > b + 12
                && (max - min) > 18
                && g >= b - 12;
            return ycbcrOk || rgbOk;
        },

        captureReference: function () {
            var sample = this.getSampleCanvas(96);
            if (!sample) {
                return;
            }
            var ctx = sample.getContext('2d');
            var image = ctx.getImageData(0, 0, sample.width, sample.height);
            this.referenceGray = this.toGray(image.data, sample.width, sample.height);
            this.referenceReady = true;
            this.lastPresentAt = Date.now();
        },

        analyzePresence: function () {
            var sample = this.getSampleCanvas(96);
            var empty = {
                covered: false,
                present: false,
                reason: 'none',
                motion: 0,
                similarity: 0
            };
            if (!sample) {
                return empty;
            }

            var width = sample.width;
            var height = sample.height;
            var ctx = sample.getContext('2d');
            var image = ctx.getImageData(0, 0, width, height);
            var data = image.data;
            var gray = this.toGray(data, width, height);

            var brightness = 0;
            for (var i = 0; i < gray.length; i += 1) {
                brightness += gray[i];
            }
            brightness = brightness / gray.length;
            // Covered / blocked lens is usually very dark (or a hand close to the lens).
            if (brightness < 22) {
                this.prevGray = gray;
                return {
                    covered: true,
                    present: false,
                    reason: 'covered',
                    motion: 0,
                    similarity: 0
                };
            }

            // Estimate wall color from the top strip (usually background).
            var wallR = 0;
            var wallG = 0;
            var wallB = 0;
            var wallCount = 0;
            var topY = Math.max(2, Math.floor(height * 0.18));
            for (var ty = 0; ty < topY; ty += 1) {
                for (var tx = 0; tx < width; tx += 2) {
                    var ti = ((ty * width) + tx) * 4;
                    wallR += data[ti];
                    wallG += data[ti + 1];
                    wallB += data[ti + 2];
                    wallCount += 1;
                }
            }
            wallR /= wallCount;
            wallG /= wallCount;
            wallB /= wallCount;

            var centerSkin = 0;
            var centerFg = 0;
            var centerCount = 0;
            var centerEdge = 0;
            var topSkin = 0;
            var topFg = 0;
            var topCount = 0;
            var x0 = Math.floor(width * 0.20);
            var x1 = Math.floor(width * 0.80);
            var y0 = Math.floor(height * 0.12);
            var y1 = Math.floor(height * 0.82);

            for (var y = 0; y < height; y += 1) {
                for (var x = 0; x < width; x += 1) {
                    var pi = ((y * width) + x) * 4;
                    var r = data[pi];
                    var g = data[pi + 1];
                    var b = data[pi + 2];
                    var lum = gray[(y * width) + x];
                    var dist = Math.abs(r - wallR) + Math.abs(g - wallG) + Math.abs(b - wallB);
                    var skin = this.isSkinPixel(r, g, b);

                    if (y < topY && x >= x0 && x < x1) {
                        topCount += 1;
                        if (skin) {
                            topSkin += 1;
                        }
                        if (dist > 85) {
                            topFg += 1;
                        }
                    }

                    if (x >= x0 && x < x1 && y >= y0 && y < y1) {
                        centerCount += 1;
                        if (skin) {
                            centerSkin += 1;
                        }
                        if (dist > 85) {
                            centerFg += 1;
                        }
                        if (x + 1 < x1 && y + 1 < y1) {
                            var gx = Math.abs(lum - gray[(y * width) + x + 1]);
                            var gy = Math.abs(lum - gray[((y + 1) * width) + x]);
                            if ((gx + gy) > 30) {
                                centerEdge += 1;
                            }
                        }
                    }
                }
            }

            var skinRatio = centerCount ? (centerSkin / centerCount) : 0;
            var fgRatio = centerCount ? (centerFg / centerCount) : 0;
            var edgeRatio = centerCount ? (centerEdge / centerCount) : 0;
            var topSkinRatio = topCount ? (topSkin / topCount) : 0;
            var topFgRatio = topCount ? (topFg / topCount) : 0;
            var fgDelta = fgRatio - topFgRatio;
            var skinDelta = skinRatio - topSkinRatio;

            var motion = 0;
            if (this.prevGray && this.prevGray.length === gray.length) {
                motion = this.meanAbsDiff(this.prevGray, gray);
            }
            this.prevGray = gray;
            if (motion > 3.5) {
                this.lastMotionAt = Date.now();
            }

            var similarity = 0;
            if (this.referenceReady && this.referenceGray && this.referenceGray.length === gray.length) {
                similarity = 1 - Math.min(1, this.meanAbsDiff(this.referenceGray, gray) / 50);
            }

            // Person = stronger center contrast than the wall strip, or clear skin/edges.
            var personLike = (fgDelta >= 0.10 && edgeRatio >= 0.025)
                || (skinDelta >= 0.08 && fgRatio >= 0.12)
                || (fgRatio >= 0.28 && edgeRatio >= 0.035)
                || (skinRatio >= 0.12 && fgRatio >= 0.18);

            var looksLikeStart = similarity >= 0.58;
            var recentMotion = (Date.now() - this.lastMotionAt) < 10000;
            var present = false;
            var reason = 'none';

            if (personLike) {
                present = true;
                reason = 'person';
            } else if (looksLikeStart && (recentMotion || similarity >= 0.75)) {
                present = true;
                reason = 'match';
            } else if (recentMotion && fgRatio >= 0.16 && edgeRatio >= 0.02) {
                present = true;
                reason = 'motion';
            }

            return {
                covered: false,
                present: present,
                reason: reason,
                motion: motion,
                similarity: similarity,
                fgRatio: fgRatio,
                skinRatio: skinRatio,
                fgDelta: fgDelta
            };
        },

        eyeAspectRatio: function (eyePoints) {
            if (!eyePoints || eyePoints.length < 6) {
                return 0;
            }
            var dist = function (a, b) {
                var dx = a.x - b.x;
                var dy = a.y - b.y;
                return Math.sqrt((dx * dx) + (dy * dy));
            };
            var vertical1 = dist(eyePoints[1], eyePoints[5]);
            var vertical2 = dist(eyePoints[2], eyePoints[4]);
            var horizontal = dist(eyePoints[0], eyePoints[3]);
            if (horizontal < 0.001) {
                return 0;
            }
            return (vertical1 + vertical2) / (2 * horizontal);
        },

        scoreFaceResult: function (detections) {
            var result = {
                faceCount: 0,
                eyesVisible: false,
                eyesOpen: false,
                ear: 0,
                blink: false,
                landmarkPoint: null,
                descriptor: null,
                label: 'none'
            };
            if (!detections || !detections.length) {
                return result;
            }
            result.faceCount = detections.length;
            if (detections.length !== 1) {
                result.label = 'multiple';
                return result;
            }

            var det = detections[0];
            if (det.descriptor) {
                result.descriptor = det.descriptor;
            }
            var landmarks = det.landmarks;
            if (!landmarks || typeof landmarks.getLeftEye !== 'function') {
                result.label = 'face';
                return result;
            }

            var leftEye = landmarks.getLeftEye();
            var rightEye = landmarks.getRightEye();
            var leftEar = this.eyeAspectRatio(leftEye);
            var rightEar = this.eyeAspectRatio(rightEye);
            var avgEar = (leftEar + rightEar) / 2;
            result.ear = avgEar;

            // Lower EAR floors help with glasses / lower webcam resolution.
            result.eyesVisible = !!(leftEye && rightEye && leftEye.length >= 6 && rightEye.length >= 6 && leftEar > 0.05 && rightEar > 0.05);
            result.eyesOpen = result.eyesVisible && avgEar >= 0.11;

            try {
                var nose = typeof landmarks.getNose === 'function' ? landmarks.getNose() : null;
                if (nose && nose.length) {
                    var mid = nose[Math.floor(nose.length / 2)] || nose[0];
                    result.landmarkPoint = { x: mid.x, y: mid.y };
                } else if (det.detection && det.detection.box) {
                    result.landmarkPoint = {
                        x: det.detection.box.x + (det.detection.box.width / 2),
                        y: det.detection.box.y + (det.detection.box.height / 2)
                    };
                }
            } catch (e) {}

            result.blink = this.trackBlink(avgEar);
            this.trackLandmarkMotion(result.landmarkPoint);

            if (result.eyesOpen) {
                result.label = 'eyes';
            } else if (result.eyesVisible) {
                result.label = 'eyes_soft';
            } else {
                result.label = 'face';
            }
            return result;
        },

        trackBlink: function (ear) {
            if (!ear || ear <= 0) {
                return false;
            }
            this.earHistory.push(ear);
            if (this.earHistory.length > 12) {
                this.earHistory.shift();
            }
            if (this.earHistory.length < 3) {
                return false;
            }
            var prev = this.earHistory[this.earHistory.length - 2];
            var older = this.earHistory[this.earHistory.length - 3];
            // Open -> closed -> open style transition.
            var blinked = older >= 0.20 && prev < 0.17 && ear >= 0.19;
            if (blinked) {
                this.blinkCount += 1;
                this.lastBlinkAt = Date.now();
                this.noBlinkStreak = 0;
            }
            return blinked;
        },

        trackLandmarkMotion: function (point) {
            if (!point) {
                return;
            }
            if (!this.lastLandmarkPoint) {
                this.lastLandmarkPoint = point;
                this.landmarkMotionScore = 0;
                return;
            }
            var dx = point.x - this.lastLandmarkPoint.x;
            var dy = point.y - this.lastLandmarkPoint.y;
            var dist = Math.sqrt((dx * dx) + (dy * dy));
            this.lastLandmarkPoint = point;
            this.landmarkMotionScore = (this.landmarkMotionScore * 0.7) + (dist * 0.3);
            if (this.landmarkMotionScore < 0.35) {
                this.stagnantStreak += 1;
            } else {
                this.stagnantStreak = 0;
            }
        },

        detectCandidate: function (input, withDescriptor) {
            var self = this;
            if (!input || !window.faceapi) {
                return Promise.resolve({ faceCount: 0, eyesVisible: false, eyesOpen: false, label: 'none', descriptor: null });
            }

            var wantDescriptor = !!(withDescriptor && this.recognitionReady && this.landmarksReady);
            var useTinyLm = this.useTinyLandmarks();

            var runDetect = function (options) {
                var detector = window.faceapi.detectAllFaces(input, options);
                if (self.landmarksReady) {
                    detector = detector.withFaceLandmarks(useTinyLm);
                }
                if (wantDescriptor) {
                    detector = detector.withFaceDescriptors();
                }
                return detector.then(function (faces) {
                    return self.scoreFaceResult(faces || []);
                }).catch(function () {
                    return { faceCount: 0, eyesVisible: false, eyesOpen: false, label: 'none', descriptor: null };
                });
            };

            // Higher thresholds: low scores were matching ceiling fans / walls as "faces".
            var attempts = [];
            if (this.tinyReady) {
                attempts.push(new window.faceapi.TinyFaceDetectorOptions({ inputSize: 320, scoreThreshold: 0.35 }));
                attempts.push(new window.faceapi.TinyFaceDetectorOptions({ inputSize: 416, scoreThreshold: 0.3 }));
            }
            if (this.ssdReady) {
                attempts.push(new window.faceapi.SsdMobilenetv1Options({ minConfidence: 0.4 }));
            }
            if (!attempts.length) {
                return Promise.resolve({ faceCount: 0, eyesVisible: false, eyesOpen: false, label: 'none', descriptor: null });
            }

            var tryAt = function (index) {
                if (index >= attempts.length) {
                    return Promise.resolve({ faceCount: 0, eyesVisible: false, eyesOpen: false, label: 'none', descriptor: null });
                }
                return runDetect(attempts[index]).then(function (result) {
                    if (result.faceCount > 0) {
                        return result;
                    }
                    return tryAt(index + 1);
                });
            };
            return tryAt(0);
        },

        checkWebcam: function () {
            var self = this;
            if (!this.started || this.ended || this.checking) {
                return;
            }
            if (Date.now() < this.graceUntil) {
                this.setFaceStatus(this.landmarksReady ? 'Tracking eyes...' : 'Camera on', false);
                if (!this.referenceReady) {
                    this.captureReference();
                }
                return;
            }

            var stats = this.analyzePresence();
            if (stats.covered) {
                this.handleCoveredCamera();
                return;
            }

            this.checking = true;
            var finish = function (result) {
                self.checking = false;
                self.applyPresence(result, stats);
            };

            if (!this.modelsReady) {
                finish({ faceCount: 0, eyesVisible: false, eyesOpen: false, label: 'none' });
                return;
            }

            var input = this.getDetectCanvas() || this.video;
            this.detectCandidate(input, true).then(function (result) {
                if (result.faceCount > 0) {
                    finish(result);
                    return;
                }
                var crop = self.getCenterCropCanvas();
                if (!crop) {
                    finish(result);
                    return;
                }
                return self.detectCandidate(crop, true).then(function (cropResult) {
                    finish(cropResult.faceCount > 0 ? cropResult : result);
                });
            }).catch(function () {
                finish({ faceCount: 0, eyesVisible: false, eyesOpen: false, label: 'none', descriptor: null });
            });
        },

        applyPresence: function (result, stats) {
            result = result || { faceCount: 0, label: 'none' };
            stats = stats || {};

            if (result.faceCount >= 2 || result.label === 'multiple') {
                this.noFaceStreak = 0;
                this.extraPersonStreak += 1;
                this.setFaceStatus('More than one person detected.', true);
                if (this.extraPersonStreak >= 3) {
                    this.recordWebcamStrike('multiple_faces', 'More than one person was visible. Sit alone to continue.');
                }
                return;
            }

            // Reject face-api false positives on empty rooms (ceiling fan, walls, etc.).
            var faceApiSaysPresent = (
                result.label === 'eyes'
                || result.label === 'eyes_soft'
                || result.label === 'face'
                || result.faceCount === 1
            );
            if (faceApiSaysPresent && stats.present === false && !stats.covered) {
                this.handleAwayFromSeat();
                return;
            }

            if (result.label === 'eyes' || result.label === 'eyes_soft' || result.label === 'face' || result.faceCount === 1) {
                if (!this.ensureSamePerson(result)) {
                    return;
                }
                if (!this.ensureLiveness(result, stats)) {
                    return;
                }
                this.lastEyesAt = Date.now();
                var statusLabel = result.eyesOpen
                    ? 'Identity OK · eyes tracked'
                    : (result.eyesVisible ? 'Identity OK · eyes soft' : 'Identity OK · face tracked');
                this.markPresent(statusLabel);
                return;
            }

            // Soft fallback only while looking down — and only if pixel analysis still sees a person.
            if (stats.present && this.lastPresentAt && (Date.now() - this.lastPresentAt) < 4000) {
                this.setFaceStatus('At the seat', false);
                return;
            }

            this.handleAwayFromSeat();
        },

        ensureSamePerson: function (result) {
            if (!result || !result.descriptor || !this.recognitionReady || !this.profileDescriptor) {
                // Without descriptor this sample cannot confirm identity; allow short grace.
                return true;
            }
            var profileThreshold = this.config.identityMatchThreshold || 0.62;
            var profileDist = this.descriptorDistance(result.descriptor, this.profileDescriptor);
            var matchesProfile = profileDist <= profileThreshold;

            if (matchesProfile) {
                this.identityMismatchStreak = 0;
                this.sessionDescriptor = result.descriptor;
                if (this.identityLocked) {
                    this.unlockIdentity('Identity restored — face matches profile photo.');
                }
                return true;
            }

            this.identityMismatchStreak += 1;
            this.setFaceStatus('Face does not match profile photo', true);

            // Quickly hide questions so a different person cannot continue the paper.
            if (this.identityMismatchStreak >= 2) {
                this.lockIdentity(
                    'Face does not match your RankPro profile photo. Questions are hidden until your live face matches again.'
                );
            }
            if (this.identityMismatchStreak >= 4) {
                this.recordWebcamStrike(
                    'face_mismatch',
                    'A different face was detected. Your face must match your profile photo to continue the exam.'
                );
                this.identityMismatchStreak = 2; // keep locked while mismatch continues
            }
            return false;
        },

        lockIdentity: function (message) {
            if (!this.started || this.ended) {
                return;
            }
            var alreadyLocked = !!this.identityLocked;
            this.identityLocked = true;
            this.lockType = 'identity';
            if (document.body) {
                document.body.classList.add('exam-identity-locked');
            }
            var resumeBtn = document.getElementById('proctoringResumeBtn');
            if (resumeBtn) {
                resumeBtn.textContent = 'Recheck Face Match';
            }
            this.showWarning(
                message
                + ' Stay in front of the camera. Exam questions stay blocked until identity matches.',
                false
            );
            if (!alreadyLocked) {
                this.logEvent('identity_lock', message);
            }
        },

        unlockIdentity: function (statusText) {
            var wasLocked = !!this.identityLocked
                || !!(document.body && document.body.classList.contains('exam-identity-locked'));
            this.identityLocked = false;
            this.identityMismatchStreak = 0;
            if (document.body) {
                document.body.classList.remove('exam-identity-locked');
            }
            var resumeBtn = document.getElementById('proctoringResumeBtn');
            if (resumeBtn) {
                resumeBtn.textContent = 'Return to Exam';
            }
            if (this.lockType === 'identity') {
                this.lockType = null;
            }
            this.hideWarning();
            this.setFaceStatus(statusText || 'Identity OK', false);
            if (wasLocked) {
                this.logEvent('identity_unlock', statusText || 'Identity restored');
            }
        },

        ensureLiveness: function (result, stats) {
            stats = stats || {};
            // Require visible eyes for ongoing proctoring (blocks many printed photos / closed eyes).
            if (!result.eyesVisible) {
                this.noBlinkStreak += 1;
                this.setFaceStatus('Eyes not tracked clearly', true);
                if (this.noBlinkStreak >= 4) {
                    this.recordWebcamStrike(
                        'eyes_not_tracked',
                        'Eyes were not tracked clearly. Keep both eyes visible to the camera.'
                    );
                    this.noBlinkStreak = 0;
                }
                return false;
            }

            var blinkWindowMs = this.config.blinkWindowMs || 45000;
            if (this.lastBlinkAt && (Date.now() - this.lastBlinkAt) > blinkWindowMs) {
                this.noBlinkStreak += 1;
            } else if (result.blink) {
                this.noBlinkStreak = 0;
            }

            // Static photo spoof: almost no landmark motion + no blink + little pixel motion.
            var looksStatic = this.stagnantStreak >= 6
                && this.landmarkMotionScore < 0.35
                && !stats.motion
                && (Date.now() - (this.lastBlinkAt || 0)) > 20000;

            if (looksStatic) {
                this.setFaceStatus('Possible photo spoof — move naturally / blink', true);
                this.recordWebcamStrike(
                    'static_image_suspected',
                    'Camera feed looks like a still image. Live face and eye movement are required.'
                );
                this.stagnantStreak = 0;
                return false;
            }

            if (this.noBlinkStreak >= 8) {
                this.setFaceStatus('Blink naturally so eyes can be verified', true);
                this.recordWebcamStrike(
                    'no_blink',
                    'No natural eye blink was detected for too long. Live eye tracking is required.'
                );
                this.noBlinkStreak = 0;
                this.lastBlinkAt = Date.now();
                return false;
            }

            return true;
        },

        markPresent: function (label) {
            this.noFaceStreak = 0;
            this.extraPersonStreak = 0;
            this.awayAfterWarnStreak = 0;
            this.lastPresentAt = Date.now();
            this.setFaceStatus(label, false);
            // Always clear identity lock when presence is accepted after a profile match.
            if (this.identityLocked || (document.body && document.body.classList.contains('exam-identity-locked'))) {
                this.unlockIdentity(label);
            } else if (this.lockType === 'webcam') {
                this.webcamWarningShown = false;
                this.lockType = null;
                this.hideWarning();
            }
            if (!this.referenceReady) {
                this.captureReference();
            }
        },

        handleAwayFromSeat: function () {
            this.extraPersonStreak = 0;
            this.noFaceStreak += 1;
            this.setFaceStatus('Not at the seat', true);
            // ~6–8 seconds away before first warning (3 samples at ~2.5s)
            if (!this.webcamWarningShown && this.noFaceStreak >= 3) {
                this.webcamWarningShown = true;
                this.awayAfterWarnStreak = 0;
                this.recordWebcamStrike('no_face', 'You left the seat. Sit down in front of the camera to continue.');
                return;
            }
            // Already warned and still away — escalate and stop the exam.
            if (this.webcamWarningShown && this.lockType === 'webcam') {
                this.awayAfterWarnStreak = (this.awayAfterWarnStreak || 0) + 1;
                if (this.awayAfterWarnStreak >= 3) {
                    this.recordWebcamStrike('no_face', 'Still not at the seat after the warning.');
                }
            }
        },

        handleCoveredCamera: function () {
            this.extraPersonStreak = 0;
            this.noFaceStreak += 1;
            this.setFaceStatus('Camera is too dark or covered.', true);
            if (!this.webcamWarningShown && this.noFaceStreak >= 3) {
                this.webcamWarningShown = true;
                this.awayAfterWarnStreak = 0;
                this.recordWebcamStrike('camera_covered', 'Camera looks covered. Uncover it to continue.');
                return;
            }
            if (this.webcamWarningShown && this.lockType === 'webcam') {
                this.awayAfterWarnStreak = (this.awayAfterWarnStreak || 0) + 1;
                if (this.awayAfterWarnStreak >= 3) {
                    this.recordWebcamStrike('camera_covered', 'Camera is still covered after the warning.');
                }
            }
        },

        setFaceStatus: function (text, isError) {
            var el = document.getElementById('proctoringFaceStatus');
            if (!el) {
                return;
            }
            el.textContent = text;
            el.style.background = isError ? '#c62828' : '#2e7d32';
        },

        recordViolation: function (type, message) {
            if (!this.started || this.ended) {
                return;
            }
            var now = Date.now();
            if (now - this.lastViolationAt < 1500 && (type === 'tab_switch' || type === 'fullscreen_exit')) {
                return;
            }
            this.lastViolationAt = now;
            this.violationCount += 1;
            this.updateBadge();
            this.logEvent(type, message);

            var remaining = Math.max(0, (this.config.maxViolations || 5) - this.violationCount);
            if (remaining <= 0) {
                this.forceEnd('submit');
                return;
            }
            if (type === 'fullscreen_exit') {
                this.lockToExam('fullscreen', 'You left fullscreen. Click Return to Exam to continue.');
                return;
            }
            if (type === 'tab_switch') {
                this.lockToExam('focus', 'You switched away from the exam. Click Return to Exam to continue in fullscreen.');
                return;
            }
            this.showWarning('Proctoring warning: ' + message + '. Stay in this window and click Return to Exam.', false);
        },

        recordWebcamStrike: function (type, message) {
            if (!this.started || this.ended) {
                return;
            }
            var now = Date.now();
            if (now - this.lastWebcamStrikeAt < 5000) {
                return;
            }
            this.lastWebcamStrikeAt = now;
            this.webcamStrikeCount += 1;
            // Count seat/camera issues on the same warning badge students see (0 / 5).
            this.violationCount += 1;
            this.updateBadge();
            this.logEvent(type, message);
            this.captureSnapshot();

            var maxWebcam = this.config.maxWebcamStrikes || 3;
            var maxAll = this.config.maxViolations || 5;
            if (this.webcamStrikeCount >= maxWebcam || this.violationCount >= maxAll) {
                this.forceEnd('cancel');
                return;
            }

            // Keep identity lock sticky; do not overwrite it with a generic webcam lock.
            if (!this.identityLocked) {
                this.lockType = 'webcam';
            } else {
                this.lockType = 'identity';
            }
            this.webcamWarningShown = true;
            this.awayAfterWarnStreak = 0;
            var remaining = Math.max(0, maxWebcam - this.webcamStrikeCount);
            if (this.identityLocked) {
                this.showWarning(
                    'Face still does not match your profile photo. Questions stay hidden. '
                    + remaining + ' more seat/camera warning(s) will cancel the exam.',
                    false
                );
            } else {
                this.showWarning(
                    'Webcam warning: ' + message
                    + ' Return to your seat with your face visible. '
                    + remaining + ' more seat/camera warning(s) will cancel the exam.',
                    false
                );
            }
        },

        updateBadge: function () {
            var badge = document.getElementById('proctoringViolationCount');
            if (badge) {
                badge.textContent = this.violationCount;
            }
        },

        showWarning: function (text, hideResume) {
            this.warningOpen = !hideResume;
            var box = document.getElementById('proctoringWarning');
            var msg = document.getElementById('proctoringWarningText');
            var resumeBtn = document.getElementById('proctoringResumeBtn');
            if (msg) {
                msg.textContent = text;
            }
            if (resumeBtn) {
                resumeBtn.style.display = hideResume ? 'none' : 'inline-block';
            }
            if (box) {
                box.style.display = 'flex';
            }
        },

        hideWarning: function () {
            this.warningOpen = false;
            var box = document.getElementById('proctoringWarning');
            if (box) {
                box.style.display = 'none';
            }
        },

        forceEnd: function (mode) {
            this.ended = true;
            this.identityLocked = false;
            if (document.body) {
                document.body.classList.remove('exam-identity-locked');
            }
            this.stopCamera();
            if (this.faceTimer) {
                clearInterval(this.faceTimer);
                this.faceTimer = null;
            }
            if (mode === 'cancel') {
                this.showWarning('Exam cancelled due to webcam proctoring violations.', true);
            } else {
                this.showWarning('Too many proctoring violations. The exam is being submitted.', true);
            }
            var callback = mode === 'cancel' ? this.config.onForceCancel : this.config.onForceEnd;
            if (typeof callback === 'function') {
                setTimeout(callback, 1400);
            }
        },

        startSnapshots: function () {
            var self = this;
            this.captureSnapshot();
            this.snapshotTimer = setInterval(function () {
                self.captureSnapshot();
            }, this.config.snapshotInterval || 120000);
        },

        stopCamera: function () {
            if (this.snapshotTimer) {
                clearInterval(this.snapshotTimer);
                this.snapshotTimer = null;
            }
            if (this.faceTimer) {
                clearInterval(this.faceTimer);
                this.faceTimer = null;
            }
            if (this.lockTimer) {
                clearInterval(this.lockTimer);
                this.lockTimer = null;
            }
            if (this.stream) {
                this.stream.getTracks().forEach(function (track) {
                    track.stop();
                });
            }
        },

        captureSnapshot: function () {
            if (!this.video || !this.canvas || !this.started || !this.config.snapshotUrl) {
                return;
            }
            if (!this.video.videoWidth) {
                return;
            }
            var width = 320;
            var height = Math.round((this.video.videoHeight / this.video.videoWidth) * width) || 240;
            this.canvas.width = width;
            this.canvas.height = height;
            var ctx = this.canvas.getContext('2d');
            ctx.drawImage(this.video, 0, 0, width, height);
            var dataUrl = this.canvas.toDataURL('image/jpeg', 0.55);
            this.post(this.config.snapshotUrl, {
                exam_user_id: this.config.examUserId,
                exam_id: this.config.examId,
                snapshot: dataUrl
            });
        },

        logEvent: function (eventType, message) {
            this.post(this.config.eventUrl, {
                exam_user_id: this.config.examUserId,
                exam_id: this.config.examId,
                event_type: eventType,
                message: message
            });
        },

        post: function (url, data) {
            if (!url || typeof window.jQuery === 'undefined') {
                return;
            }
            var payload = data || {};
            var token = this.config.csrfToken || window.examCsrfToken;
            if (token) {
                payload._token = token;
            }
            window.jQuery.ajax({
                url: url,
                method: 'POST',
                data: payload,
                headers: token ? { 'X-CSRF-TOKEN': token } : {}
            });
        },

        setStatus: function (el, text, isError) {
            if (!el) {
                return;
            }
            el.textContent = text;
            el.style.color = isError ? '#c62828' : '#2e7d32';
        }
    };

    window.RankProProctoring = RankProProctoring;
})(window, document);
