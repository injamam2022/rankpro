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
        lastPresentAt: 0,
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
                        self.graceUntil = Date.now() + 5000;
                        self.lastPresentAt = Date.now();
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
            this.graceUntil = Date.now() + 10000;
            this.lastPresentAt = Date.now();
            this.lastMotionAt = Date.now();
            this.noFaceStreak = 0;
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
            this.logEvent('started', 'Proctored exam started');
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
            }, 2000);
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
            if (brightness < 12) {
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

        detectFaces: function (input) {
            var self = this;
            var tryTiny = function (size, threshold) {
                if (!self.tinyReady) {
                    return Promise.resolve(0);
                }
                return window.faceapi.detectAllFaces(input, new window.faceapi.TinyFaceDetectorOptions({
                    inputSize: size,
                    scoreThreshold: threshold
                })).then(function (faces) {
                    return faces ? faces.length : 0;
                }).catch(function () {
                    return 0;
                });
            };
            var trySsd = function () {
                if (!self.ssdReady) {
                    return Promise.resolve(0);
                }
                return window.faceapi.detectAllFaces(input, new window.faceapi.SsdMobilenetv1Options({
                    minConfidence: 0.12
                })).then(function (faces) {
                    return faces ? faces.length : 0;
                }).catch(function () {
                    return 0;
                });
            };

            // Prefer Tiny first — more tolerant of blurry laptop webcams.
            return tryTiny(224, 0.05).then(function (count) {
                if (count > 0) {
                    return count;
                }
                return tryTiny(320, 0.08);
            }).then(function (count) {
                if (count > 0) {
                    return count;
                }
                return trySsd();
            });
        },

        checkWebcam: function () {
            var self = this;
            if (!this.started || this.ended || this.checking) {
                return;
            }
            if (Date.now() < this.graceUntil) {
                this.setFaceStatus('Camera on', false);
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
            var finish = function (faceCount) {
                self.checking = false;
                self.applyPresence(faceCount, stats);
            };

            if (!this.modelsReady) {
                finish(0);
                return;
            }

            var input = this.getDetectCanvas() || this.video;
            this.detectFaces(input).then(function (count) {
                if (count > 0) {
                    finish(count);
                    return;
                }
                var crop = self.getCenterCropCanvas();
                if (!crop) {
                    finish(0);
                    return;
                }
                return self.detectFaces(crop).then(function (cropCount) {
                    finish(cropCount);
                });
            }).catch(function () {
                finish(0);
            });
        },

        applyPresence: function (faceCount, stats) {
            if (faceCount >= 2) {
                this.noFaceStreak = 0;
                this.extraPersonStreak += 1;
                this.setFaceStatus('More than one person detected.', true);
                if (!this.webcamWarningShown && this.extraPersonStreak >= 10) {
                    this.webcamWarningShown = true;
                    this.recordWebcamStrike('multiple_faces', 'More than one person was visible. Sit alone to continue.');
                }
                return;
            }

            if (faceCount === 1) {
                this.markPresent('Face detected');
                return;
            }

            if (stats && stats.present) {
                this.markPresent(stats.reason === 'match' ? 'At the seat' : 'At the seat');
                return;
            }

            // Short misses while looking at the paper should not flip red.
            if (this.lastPresentAt && (Date.now() - this.lastPresentAt) < 8000) {
                this.setFaceStatus('At the seat', false);
                return;
            }

            this.handleAwayFromSeat();
        },

        markPresent: function (label) {
            this.noFaceStreak = 0;
            this.extraPersonStreak = 0;
            this.lastPresentAt = Date.now();
            this.setFaceStatus(label, false);
            if (this.lockType === 'webcam') {
                this.webcamWarningShown = false;
                this.webcamStrikeCount = 0;
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
            // ~16–20 seconds away before warning overlay
            if (!this.webcamWarningShown && this.noFaceStreak >= 8) {
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
            if (now - this.lastWebcamStrikeAt < 8000) {
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
