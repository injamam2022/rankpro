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
        modelsReady: false,
        tinyOnly: false,
        graceUntil: 0,
        warningOpen: false,
        webcamWarningShown: false,

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
                    self.requestCamera();
                });
            }
            if (startBtn) {
                startBtn.addEventListener('click', function () {
                    self.startExam();
                });
            }
            if (resumeBtn) {
                resumeBtn.addEventListener('click', function () {
                    self.enterFullscreen();
                    self.noFaceStreak = 0;
                    self.extraPersonStreak = 0;
                    self.webcamWarningShown = false;
                    self.graceUntil = Date.now() + 10000;
                    self.hideWarning();
                });
            }
        },

        loadModels: function () {
            var self = this;
            if (!window.faceapi || !this.config.modelUrl) {
                return;
            }
            window.faceapi.nets.ssdMobilenetv1.loadFromUri(this.config.modelUrl).then(function () {
                self.modelsReady = true;
            }).catch(function () {
                window.faceapi.nets.tinyFaceDetector.loadFromUri(self.config.modelUrl).then(function () {
                    self.tinyOnly = true;
                    self.modelsReady = true;
                }).catch(function () {
                    self.modelsReady = false;
                });
            });
        },

        requestCamera: function () {
            var self = this;
            var statusEl = document.getElementById('proctoringCameraStatus');
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                this.setStatus(statusEl, 'Camera is not supported in this browser.', true);
                return;
            }
            navigator.mediaDevices.getUserMedia({ video: true, audio: false }).then(function (stream) {
                self.stream = stream;
                if (self.video) {
                    self.video.srcObject = stream;
                    self.video.play();
                }
                self.setStatus(statusEl, 'Camera connected. Keep your face clearly visible.', false);
                var startBtn = document.getElementById('proctoringStartBtn');
                if (startBtn) {
                    startBtn.disabled = false;
                }
                stream.getVideoTracks().forEach(function (track) {
                    track.addEventListener('ended', function () {
                        if (self.started && !self.ended) {
                            self.recordWebcamStrike('camera_lost', 'Camera was disconnected');
                        }
                    });
                });
            }).catch(function () {
                self.setStatus(statusEl, 'Camera permission is required to start this exam.', true);
            });
        },

        startExam: function () {
            if (!this.stream) {
                this.requestCamera();
                return;
            }
            this.enterFullscreen();
            this.started = true;
            this.ended = false;
            this.graceUntil = Date.now() + 4000;
            document.body.classList.add('exam-proctored-active');
            var overlay = document.getElementById('proctoringGate');
            if (overlay) {
                overlay.style.display = 'none';
            }
            this.bindExamGuards();
            this.logEvent('started', 'Proctored exam started');
            this.startSnapshots();
            this.startFaceMonitor();
            if (typeof window.beginExamTimer === 'function') {
                window.beginExamTimer();
            }
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
            var self = this;

            document.addEventListener('visibilitychange', function () {
                if (document.hidden && !self.warningOpen) {
                    self.recordViolation('tab_switch', 'Student switched tab or minimized the window');
                }
            });

            window.addEventListener('blur', function () {
                if (!self.warningOpen) {
                    self.recordViolation('tab_switch', 'Exam window lost focus');
                }
            });

            document.addEventListener('fullscreenchange', function () {
                if (!self.isFullscreen() && self.started && !self.ended) {
                    self.recordViolation('fullscreen_exit', 'Student left fullscreen');
                    self.showWarning('Please return to fullscreen to continue the exam.');
                }
            });
            document.addEventListener('webkitfullscreenchange', function () {
                if (!self.isFullscreen() && self.started && !self.ended) {
                    self.recordViolation('fullscreen_exit', 'Student left fullscreen');
                    self.showWarning('Please return to fullscreen to continue the exam.');
                }
            });

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

        startFaceMonitor: function () {
            var self = this;
            this.checkWebcam();
            this.faceTimer = setInterval(function () {
                self.checkWebcam();
            }, 2000);
        },

        getDetectCanvas: function () {
            if (!this.video || !this.video.videoWidth) {
                return null;
            }
            if (!this.detectCanvas) {
                this.detectCanvas = document.createElement('canvas');
            }
            var width = 480;
            var height = Math.round((this.video.videoHeight / this.video.videoWidth) * width) || 360;
            this.detectCanvas.width = width;
            this.detectCanvas.height = height;
            this.detectCanvas.getContext('2d').drawImage(this.video, 0, 0, width, height);
            return this.detectCanvas;
        },

        getBrightness: function () {
            if (!this.video || !this.canvas || !this.video.videoWidth) {
                return 255;
            }
            this.canvas.width = 80;
            this.canvas.height = 60;
            var ctx = this.canvas.getContext('2d');
            ctx.drawImage(this.video, 0, 0, 80, 60);
            var data = ctx.getImageData(0, 0, 80, 60).data;
            var total = 0;
            var count = 0;
            for (var i = 0; i < data.length; i += 16) {
                total += (data[i] + data[i + 1] + data[i + 2]) / 3;
                count += 1;
            }
            return count ? (total / count) : 255;
        },

        checkWebcam: function () {
            var self = this;
            if (!this.started || this.ended) {
                return;
            }
            if (Date.now() < this.graceUntil) {
                this.setFaceStatus('Look at the camera.', false);
                return;
            }
            if (!this.modelsReady) {
                this.setFaceStatus('Starting face check...', false);
                return;
            }

            var brightness = this.getBrightness();
            if (brightness < 14) {
                this.handleMissingFace('camera_covered', 'Camera looks covered or too dark.');
                return;
            }

            var input = this.getDetectCanvas() || this.video;
            var options = this.tinyOnly
                ? new window.faceapi.TinyFaceDetectorOptions({ inputSize: 416, scoreThreshold: 0.4 })
                : new window.faceapi.SsdMobilenetv1Options({ minConfidence: 0.5 });

            window.faceapi.detectAllFaces(input, options).then(function (faces) {
                self.handleFaceResult(faces ? faces.length : 0);
            }).catch(function () {
                self.handleMissingFace('no_face', 'Face not visible. Look at the camera.');
            });
        },

        handleMissingFace: function (type, statusText) {
            this.extraPersonStreak = 0;
            this.noFaceStreak += 1;
            this.setFaceStatus(statusText, true);
            if (!this.webcamWarningShown && this.noFaceStreak >= 5) {
                this.webcamWarningShown = true;
                this.recordWebcamStrike(type, 'Face was not visible on webcam. Return and look at the camera to continue.');
                return;
            }
            if (this.webcamWarningShown && this.noFaceStreak >= 20) {
                this.forceEnd('cancel');
            }
        },

        handleFaceResult: function (faceCount) {
            if (!this.started || this.ended) {
                return;
            }
            if (faceCount >= 2) {
                this.noFaceStreak = 0;
                this.extraPersonStreak += 1;
                this.setFaceStatus('More than one person detected.', true);
                if (!this.webcamWarningShown && this.extraPersonStreak >= 5) {
                    this.webcamWarningShown = true;
                    this.recordWebcamStrike('multiple_faces', 'More than one person was visible. Sit alone and look at the camera to continue.');
                    return;
                }
                if (this.webcamWarningShown && this.extraPersonStreak >= 15) {
                    this.forceEnd('cancel');
                }
                return;
            }
            if (faceCount < 1) {
                this.handleMissingFace('no_face', 'Face not visible. Look at the camera.');
                return;
            }

            this.noFaceStreak = 0;
            this.extraPersonStreak = 0;
            this.setFaceStatus('Face detected', false);
            if (this.webcamWarningShown || this.warningOpen) {
                this.webcamWarningShown = false;
                this.webcamStrikeCount = 0;
                this.hideWarning();
                this.graceUntil = Date.now() + 8000;
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
            this.showWarning('Proctoring warning: ' + message + '. ' + remaining + ' warning(s) left before the exam is submitted.', false);
        },

        recordWebcamStrike: function (type, message) {
            if (!this.started || this.ended) {
                return;
            }
            var now = Date.now();
            if (now - this.lastWebcamStrikeAt < 6000) {
                return;
            }
            this.lastWebcamStrikeAt = now;
            this.webcamStrikeCount += 1;
            this.violationCount += 1;
            this.updateBadge();
            this.logEvent(type, message);
            this.captureSnapshot();
            this.showWarning('Webcam warning: ' + message + ' The exam is cancelled only if you stay away.', false);
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
            }, this.config.snapshotInterval || 45000);
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
            window.jQuery.ajax({
                url: url,
                method: 'POST',
                data: data
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
