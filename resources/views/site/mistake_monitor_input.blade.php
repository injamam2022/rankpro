<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Tag Error Reason | RankPro</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('web/images/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('web/images/favicon.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('web/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('web/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css" rel="stylesheet">
    <link href="{{ asset('web/bootstrap-5.0.2/css/bootstrap.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('web/css/jquery.bxslider.css') }}">
    <link href="{{ asset('web/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/dashboard-v2.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/progress-insights.css') }}" rel="stylesheet">
    <style>.calltoaction { display: none; }</style>
    @include('site.include.head_meta')
</head>
<body class="rp-dash-body">
    @include('site.include.body_meta')
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    @include('site.include.student_dashboard_header')

    <section id="dashboard">
        <div class="container-fluid">
            <div class="dashboardAll dashboardPh">
                <div class="menuBarBtn menuBarBtnOpen"><i class="fas fa-bars"></i></div>
                <div class="dashboardLeft dashboardLeftOff">
                    @include('site.include.student_left_menu')
                </div>
                <div class="dashboardRight">
                    <div class="dashboardRightBody">
                        <div class="mt-page" id="mtPage"
                             data-start="{{ $exam_result_id }}"
                             data-save="{{ route('mistake_monitor_input_reason') }}"
                             data-asset="{{ asset('') }}">

                            <div class="mt-top">
                                <a class="mt-back" href="{{ $back_url }}"><i class="fas fa-arrow-left"></i> Back to Mistake Monitor</a>
                                <span class="pr-pill {{ ($exam_mode_label ?? '') === 'Offline OMR' ? 'pr-pill--green' : 'pr-pill--blue' }}">{{ $exam_mode_label }}</span>
                            </div>

                            <div class="pr-card mt-hero">
                                <div class="pr-hero__title-wrap">
                                    <span class="pr-icon pr-icon--pink"><i class="fas fa-stethoscope"></i></span>
                                    <div>
                                        <h1 class="pr-hero__title">{{ $exam_user->name ?? 'Mock' }}</h1>
                                        <p class="pr-hero__sub">Review each incorrect question, tag why you missed it, and open the NCERT solution.</p>
                                    </div>
                                </div>
                                <div class="mt-count" id="mtCount"></div>
                            </div>

                            @if(empty($questions))
                                <div class="pr-card pr-empty">No incorrect answers were found for this mock in the current Mode / Subject.</div>
                            @else
                                <div class="pr-card">
                                    <div class="mt-palette" id="mtPalette"></div>
                                </div>

                                <div class="pr-card mt-qcard">
                                    <div class="mt-qhead">
                                        <div>
                                            <div class="mt-qno" id="mtQno"></div>
                                            <div class="mt-chips" id="mtChips"></div>
                                        </div>
                                        <div class="mt-status" id="mtStatus"></div>
                                    </div>
                                    <div class="mt-qtext" id="mtQuestion"></div>
                                    <div class="mt-opts" id="mtOpts"></div>

                                    <div class="mt-reason">
                                        <div class="mt-reason__label">Why did you get this wrong?</div>
                                        <div class="mt-reasons" id="mtReasons">
                                            @foreach($mistake_input_list as $reason)
                                                <button type="button" class="mt-reason-btn" data-id="{{ $reason->id }}">{{ $reason->name }}</button>
                                            @endforeach
                                        </div>
                                        <div class="mt-saved" id="mtSaved" hidden>Reason saved</div>
                                    </div>

                                    <div class="mt-actions">
                                        <button type="button" class="mt-btn mt-btn--pink" id="mtSolutionBtn"><i class="fas fa-book-open"></i> Answer Solution</button>
                                        <button type="button" class="mt-btn mt-btn--navy" id="mtVideoBtn"><i class="fas fa-play"></i> Video Solution</button>
                                    </div>
                                    <div class="mt-solution" id="mtSolution" hidden></div>
                                </div>

                                <div class="pr-card mt-stats">
                                    <div><b id="stAttempted">—</b><span>Students attempted</span></div>
                                    <div><b id="stCorrect">—</b><span>Answered correctly</span></div>
                                    <div><b id="stYou">—</b><span>Time spent by you</span></div>
                                    <div><b id="stAvg">—</b><span>Average time</span></div>
                                    <div><b id="stTop">—</b><span>Topper time</span></div>
                                </div>

                                <div class="mt-nav">
                                    <button type="button" class="mt-nav-btn" id="mtPrev"><i class="fas fa-chevron-left"></i> Previous</button>
                                    <span class="mt-nav-pos" id="mtPos"></span>
                                    <button type="button" class="mt-nav-btn" id="mtNext">Next <i class="fas fa-chevron-right"></i></button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="mtVideoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content mt-modal">
                <button type="button" class="mt-modal__close" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
                <iframe id="mtVideoFrame" width="100%" height="420" src="" allowfullscreen allow="autoplay; encrypted-media"></iframe>
            </div>
        </div>
    </div>

    @include('site.include.call_to_action')
    @include('site.include.how_to_use')

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('web/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('web/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('web/lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('web/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
    <script src="{{ asset('web/js/jquery.bxslider.js') }}"></script>
    <script src="{{ asset('web/js/main.js') }}"></script>
    <script>
    (function () {
        var questions = @json($questions ?? []);
        var root = document.getElementById('mtPage');
        if (!root || !questions.length) return;

        var startId = String(root.getAttribute('data-start') || '');
        var saveUrl = root.getAttribute('data-save');
        var assetBase = root.getAttribute('data-asset') || '';
        var csrf = document.querySelector('meta[name="csrf-token"]');
        csrf = csrf ? csrf.getAttribute('content') : '';
        var index = Math.max(0, questions.findIndex(function (q) { return String(q.id) === startId; }));
        var letters = {1: 'A', 2: 'B', 3: 'C', 4: 'D'};

        function current() { return questions[index]; }

        function optionHtml(text, isImage) {
            if (!text) return '';
            if (Number(isImage) === 1) {
                var src = String(text);
                if (!/^https?:\/\//i.test(src) && src.indexOf('/') !== 0) src = assetBase + 'uploads/question/' + src;
                return '<img src="'+src.replace(/"/g, '&quot;')+'" alt="">';
            }
            return text;
        }

        function videoSrc(url) {
            if (!url) return '';
            var m = String(url).match(/(?:youtu\.be\/|v=|embed\/)([A-Za-z0-9_-]{6,})/);
            if (m) return 'https://www.youtube.com/embed/' + m[1];
            return url;
        }

        function renderPalette() {
            var wrap = document.getElementById('mtPalette');
            wrap.innerHTML = questions.map(function (q, i) {
                var cls = 'mt-bubble';
                if (i === index) cls += ' is-on';
                if (q.mistake_input_id) cls += ' is-tagged';
                return '<button type="button" class="'+cls+'" data-i="'+i+'">Q'+(q.question_number || (i+1))+'</button>';
            }).join('');
            wrap.querySelectorAll('button').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    index = Number(btn.getAttribute('data-i'));
                    render();
                });
            });
        }

        function render() {
            var q = current();
            document.getElementById('mtCount').textContent = questions.length + ' incorrect';
            document.getElementById('mtQno').textContent = 'Q ' + (q.question_number || (index + 1));
            document.getElementById('mtQuestion').innerHTML = q.question_text || '';
            document.getElementById('mtPos').textContent = (index + 1) + ' of ' + questions.length;

            var chips = [];
            if (q.difficulty_label) chips.push('<span class="pr-chip pr-chip--red">'+q.difficulty_label+'</span>');
            if (q.subject) chips.push('<span class="pr-chip pr-chip--'+String(q.subject).toLowerCase()+'">'+q.subject+'</span>');
            if (q.chapter) chips.push('<span class="pr-chip">'+q.chapter+'</span>');
            document.getElementById('mtChips').innerHTML = chips.join('');
            document.getElementById('mtStatus').innerHTML = q.mistake_input_id
                ? '<span class="pr-pill pr-pill--green">Reason tagged</span>'
                : '<span class="pr-pill pr-pill--pink">Untagged</span>';

            var opts = '';
            [1,2,3,4].forEach(function (n) {
                var html = optionHtml(q['option'+n], q['is_option'+n+'_image']);
                if (!html) return;
                var cls = 'mt-opt';
                var mark = '';
                if (Number(q.correct) === n) { cls += ' is-correct'; mark = 'Correct'; }
                if (Number(q.answer) === n && Number(q.correct) !== n) { cls += ' is-wrong'; mark = 'Your pick'; }
                else if (Number(q.answer) === n) { mark = 'Your pick · Correct'; }
                opts += '<div class="'+cls+'"><span><b>'+letters[n]+'.</b> '+html+'</span>'+(mark ? '<em>'+mark+'</em>' : '')+'</div>';
            });
            document.getElementById('mtOpts').innerHTML = opts || '<div class="pr-empty">Options are not available for this question.</div>';

            document.querySelectorAll('.mt-reason-btn').forEach(function (btn) {
                btn.classList.toggle('is-on', String(q.mistake_input_id || '') === String(btn.getAttribute('data-id')));
            });

            document.getElementById('stAttempted').textContent = q.student_attempted || 0;
            document.getElementById('stCorrect').textContent = q.attempted_correct || 0;
            document.getElementById('stYou').textContent = q.your_time || '—';
            document.getElementById('stAvg').textContent = q.avg_time || '—';
            document.getElementById('stTop').textContent = q.topper_time || '—';

            var sol = document.getElementById('mtSolution');
            sol.hidden = true;
            sol.innerHTML = q.solution
                ? '<strong>NCERT / Answer explanation</strong><div>'+q.solution+'</div>'
                : '<strong>Answer explanation</strong><p>No written solution is stored for this question yet.</p>';
            document.getElementById('mtSolutionBtn').disabled = false;
            document.getElementById('mtVideoBtn').disabled = !q.video_link;

            document.getElementById('mtPrev').disabled = index <= 0;
            document.getElementById('mtNext').disabled = index >= questions.length - 1;
            document.getElementById('mtSaved').hidden = true;
            renderPalette();
        }

        document.getElementById('mtPrev').addEventListener('click', function () {
            if (index > 0) { index -= 1; render(); }
        });
        document.getElementById('mtNext').addEventListener('click', function () {
            if (index < questions.length - 1) { index += 1; render(); }
        });
        document.getElementById('mtSolutionBtn').addEventListener('click', function () {
            var box = document.getElementById('mtSolution');
            box.hidden = !box.hidden;
        });
        document.getElementById('mtVideoBtn').addEventListener('click', function () {
            var q = current();
            if (!q.video_link) return;
            document.getElementById('mtVideoFrame').src = videoSrc(q.video_link);
            var modalEl = document.getElementById('mtVideoModal');
            if (window.bootstrap && bootstrap.Modal) {
                var inst = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                inst.show();
            } else {
                $(modalEl).modal('show');
            }
        });
        document.getElementById('mtVideoModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('mtVideoFrame').src = '';
        });

        document.querySelectorAll('.mt-reason-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var q = current();
                var reasonId = btn.getAttribute('data-id');
                $.ajax({
                    url: saveUrl,
                    method: 'POST',
                    data: {
                        _token: csrf,
                        question_id: q.id,
                        mistake_input_id: reasonId,
                        user_exam_id: q.exam_user_id,
                        exam_id: q.exam_id
                    }
                }).done(function (res) {
                    if (!res || !res.ok) return;
                    q.mistake_input_id = Number(reasonId);
                    document.getElementById('mtSaved').hidden = false;
                    render();
                });
            });
        });

        render();
    })();
    </script>
</body>
</html>
