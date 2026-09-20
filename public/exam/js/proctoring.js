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
        ssdReady: false,
        tinyReady: false,
        lastFaceAt: 0,
        graceUntil: 0,
        warningOpen: false,
        webcamWarningShown: false,
        lockType: null,
        lockTimer: null,
        guardsBound: false,
        resumeGraceUntil: 0,

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
                    self.resumeGraceUntil = Date.now() + 2000;
                    if (self.lockType === 'webcam') {
                        self.noFaceStreak = 0;
                        self.extraPersonStreak = 0;
                        self.webcamWarningShown = false;
                        self.graceUntil = Date.now() + 4000;
                    }
                    self.lockType = null;
                    self.hideWarning();
                    setTimeout(function () {
                        if (self.started && !self.ended && !self.isFullscreen()) {
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
            var ssd = window.faceapi.nets.ssdMobilenetv1.loadFromUri(url).then(function () {
                self.ssdReady = true;
            }).catch(function () {
                self.ssdReady = false;
            });
            var tiny = window.faceapi.nets.tinyFaceDetector.loadFromUri(url).then(function () {
                self.tinyReady = true;
            }).catch(function () {
                self.tinyReady = false;
            });
            Promise.all([ssd, tiny]).then(function () {
                self.modelsReady = !!(self.ssdReady || self.tinyReady);
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
            this.graceUntil = Date.now() + 8000;
            this.lastFaceAt = Date.now();
            document.body.classList.add('exam-proctored-active');
            var overlay = document.getElementById('proctoringGate');
            if (overlay) {
                overlay.style.display = 'none';
            }
            this.bindExamGuards();
            this.startLockWatch();
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
                if (document.hidden && self.started && !self.ended) {
                    self.lockToExam('focus', 'You switched away from the exam. Click Return to Exam to continue in fullscreen.');
                    self.recordViolation('tab_switch', 'Student switched tab or minimized the window');
                }
            });

            window.addEventListener('blur', function () {
                if (self.started && !self.ended && !self.warningOpen) {
                    self.lockToExam('focus', 'The exam window lost focus. Click Return to Exam to continue in fullscreen.');
                    self.recordViolation('tab_switch', 'Exam window lost focus');
                }
            });

            var onFullscreenLeave = function () {
                if (!self.isFullscreen() && self.started && !self.ended) {
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

        lockToExam: function (type, message) {
            if (!this.started || this.ended) {
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
                if (!self.started || self.ended) {
                    return;
                }
                if (Date.now() < (self.resumeGraceUntil || 0)) {
                    return;
                }
                if (Date.now() < self.graceUntil && !self.lockType) {
                    return;
                }
                if (!self.isFullscreen() && self.lockType !== 'webcam') {
                    self.lockToExam('fullscreen', 'Stay in fullscreen. Click Return to Exam to continue.');
                }
            }, 700);
        },

        startFaceMonitor: function () {
            var self = this;
            this.checkWebcam();
            this.faceTimer = setInterval(function () {
                self.checkWebcam();
            }, 1500);
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

        getCenterCropCanvas: function () {
            if (!this.video || !this.video.videoWidth) {
                return null;
            }
            if (!this.cropCanvas) {
                this.cropCanvas = document.createElement('canvas');
            }
            var vw = this.video.videoWidth;
            var vh = this.video.videoHeight;
            var cw = Math.round(vw * 0.72);
            var ch = Math.round(vh * 0.72);
            var sx = Math.round((vw - cw) / 2);
            var sy = Math.round((vh - ch) * 0.28);
            this.cropCanvas.width = 400;
            this.cropCanvas.height = Math.round(400 * ch / cw) || 300;
            this.cropCanvas.getContext('2d').drawImage(this.video, sx, sy, cw, ch, 0, 0, this.cropCanvas.width, this.cropCanvas.height);
            return this.cropCanvas;
        },

        getFrameStats: function () {
            var empty = { brightness: 255, center: 255, variance: 255, covered: false };
            if (!this.video || !this.canvas || !this.video.videoWidth) {
                return empty;
            }
            this.canvas.width = 80;
            this.canvas.height = 60;
            var ctx = this.canvas.getContext('2d');
            ctx.drawImage(this.video, 0, 0, 80, 60);
            var data = ctx.getImageData(0, 0, 80, 60).data;
            var total = 0;
            var count = 0;
            var centerTotal = 0;
            var centerCount = 0;
            var values = [];
            for (var y = 0; y < 60; y += 2) {
                for (var x = 0; x < 80; x += 2) {
                    var i = ((y * 80) + x) * 4;
                    var lum = (data[i] + data[i + 1] + data[i + 2]) / 3;
                    total += lum;
                    count += 1;
                    values.push(lum);
                    if (x >= 20 && x < 60 && y >= 12 && y < 48) {
                        centerTotal += lum;
                        centerCount += 1;
                    }
                }
            }
            var brightness = count ? (total / count) : 255;
            var center = centerCount ? (centerTotal / centerCount) : brightness;
            var varSum = 0;
            for (var v = 0; v < values.length; v += 1) {
                var diff = values[v] - brightness;
                varSum += diff * diff;
            }
            var variance = values.length ? (varSum / values.length) : 0;
            return {
                brightness: brightness,
                center: center,
                variance: variance,
                covered: brightness < 10 && center < 12 && variance < 16
            };
        },

        detectFaces: function (input) {
            var self = this;
            var tryTiny = function () {
                if (!self.tinyReady) {
                    return Promise.resolve(0);
                }
                return window.faceapi.detectAllFaces(input, new window.faceapi.TinyFaceDetectorOptions({
                    inputSize: 416,
                    scoreThreshold: 0.15
                })).then(function (faces) {
                    return faces ? faces.length : 0;
                }).catch(function () {
                    return 0;
                });
            };
            if (this.ssdReady) {
                return window.faceapi.detectAllFaces(input, new window.faceapi.SsdMobilenetv1Options({
                    minConfidence: 0.28
                })).then(function (faces) {
                    if (faces && faces.length) {
                        return faces.length;
                    }
                    return tryTiny();
                }).catch(function () {
                    return tryTiny();
                });
            }
            return tryTiny();
        },

        checkWebcam: function () {
            var self = this;
            if (!this.started || this.ended) {
                return;
            }
            if (Date.now() < this.graceUntil) {
                this.setFaceStatus('Camera on', false);
                return;
            }

            var stats = this.getFrameStats();
            if (stats.covered) {
                this.handleCoveredCamera();
                return;
            }

            if (!this.modelsReady) {
                this.setFaceStatus('Checking camera...', false);
                return;
            }

            var input = this.getDetectCanvas() || this.video;
            this.detectFaces(input).then(function (count) {
                if (count > 0) {
                    self.handleFaceResult(count);
                    return;
                }
                var crop = self.getCenterCropCanvas();
                if (!crop) {
                    self.handleAwayFromSeat();
                    return;
                }
                return self.detectFaces(crop).then(function (cropCount) {
                    if (cropCount > 0) {
                        self.handleFaceResult(cropCount);
                    } else {
                        self.handleAwayFromSeat();
                    }
                });
            }).catch(function () {
                self.handleAwayFromSeat();
            });
        },

        markPresent: function (label) {
            this.noFaceStreak = 0;
            this.extraPersonStreak = 0;
            this.lastFaceAt = Date.now();
            this.setFaceStatus(label, false);
            if (this.lockType === 'webcam') {
                this.webcamWarningShown = false;
                this.webcamStrikeCount = 0;
                this.lockType = null;
                this.hideWarning();
            }
        },

        handleAwayFromSeat: function () {
            this.extraPersonStreak = 0;
            this.noFaceStreak += 1;
            if (this.lastFaceAt && (Date.now() - this.lastFaceAt) < 6000) {
                this.setFaceStatus('Face detected', false);
                return;
            }
            this.setFaceStatus('Not at the seat', true);
            if (!this.webcamWarningShown && this.noFaceStreak >= 6) {
                this.webcamWarningShown = true;
                this.recordWebcamStrike('no_face', 'You left the seat. Sit down in front of the camera to continue.');
            }
        },

        handleCoveredCamera: function () {
            this.extraPersonStreak = 0;
            this.noFaceStreak += 1;
            this.setFaceStatus('Camera is too dark or covered.', true);
            if (!this.webcamWarningShown && this.noFaceStreak >= 8) {
                this.webcamWarningShown = true;
                this.recordWebcamStrike('camera_covered', 'Camera looks covered. Uncover it to continue.');
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
                if (!this.webcamWarningShown && this.extraPersonStreak >= 8) {
                    this.webcamWarningShown = true;
                    this.recordWebcamStrike('multiple_faces', 'More than one person was visible. Sit alone to continue.');
                }
                return;
            }
            if (faceCount < 1) {
                this.handleAwayFromSeat();
                return;
            }
            this.markPresent('Face detected');
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
            if (now - this.lastWebcamStrikeAt < 6000) {
                return;
            }
            this.lastWebcamStrikeAt = now;
            this.webcamStrikeCount += 1;
            this.logEvent(type, message);
            this.captureSnapshot();
            this.lockType = 'webcam';
            this.showWarning('Webcam warning: ' + message + ' Sitting and reading the exam will not cancel the test.', false);
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
