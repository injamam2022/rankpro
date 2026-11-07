<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    @php
        $scholarshipFaqs = [
            ['q' => 'Is there any fee to register or take the test?', 'a' => 'No. Registration is completely free.'],
            ['q' => 'Who can register?', 'a' => 'Anyone who can appear for NEET: students in Class 10, Class 10+2 (Class 11 and 12) and Class 12+ (12th pass and beyond).'],
            ['q' => 'How is my scholarship decided?', 'a' => 'By your score percentage, using the reward bands shown above.'],
            ['q' => 'What if I score below 60%?', 'a' => 'You still get a detailed result analysis that shows your weak areas and next steps.'],
            ['q' => 'Will I get guidance after the test?', 'a' => 'Yes. You can get guidance based on your performance and preparation level.'],
            ['q' => 'How do I register?', 'a' => 'Click “Register Free”, fill in your details and submit. It takes about a minute.'],
            ['q' => 'How will I appear for the test?', 'a' => 'After you register, our team contacts you on WhatsApp or call to confirm your test mode (online or offline), date and time. You then attempt the 3-hour paper and receive your result and scholarship.'],
        ];
        $faqSchema = [
            '@type' => 'FAQPage',
            '@id' => route('scholarship') . '#faq',
            'mainEntity' => array_map(function ($item) {
                return [
                    '@type' => 'Question',
                    'name' => $item['q'],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a']],
                ];
            }, $scholarshipFaqs),
        ];
    @endphp
    @include('site.include.seo', ['seo' => ['schema' => [$faqSchema]]])
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="shortcut icon" href="{{ asset('web/images/favicon.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('web/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('web/bootstrap-5.0.2/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/redesign.css') }}" rel="stylesheet">
    <link href="{{ asset('web/css/scholarship.css') }}" rel="stylesheet">
    @include('site.include.head_meta')
</head>
<body>
    @include('site.include.body_meta')
    @include('site.include.header')

    <div class="sch">
        <div class="sch-bar">Score more, pay less. Take the Rank Pro Scholarship Test free.<a href="#register">Register now</a></div>

        <header class="sch-hero">
            <div class="sch-wrap">
                <p class="sch-eyebrow">NEET UG · AI-Powered Prep · Scholarship Test</p>
                <h1>Experience <span class="sch-em">NEET</span> before NEET</h1>
                <p class="sch-sub">Test yourself in a real NEET like environment before you appear for the actual NEET. Get rewarded as you perform more.</p>
                <p class="sch-sub" style="margin-top:14px;font-weight:600">Open to every student who can appear for NEET.<br>Enrol for FREE</p>
                <p style="margin-top:24px"><a class="sch-btn" href="#register">Take the Scholarship Test</a></p>
                <svg class="sch-art" viewBox="0 0 760 380" role="img" aria-label="A NEET answer sheet beside a rising scholarship ladder reaching 100 percent">
                    <rect width="760" height="380" fill="#f3efff"/>
                    <rect x="80" y="40" width="230" height="300" rx="18" fill="#fff" stroke="#e4defa" stroke-width="2"/>
                    <text x="104" y="84" font-size="20" font-weight="800" fill="#5b3fc4">NEET</text>
                    <rect x="104" y="98" width="182" height="4" rx="2" fill="#e4defa"/>
                    <g fill="none" stroke="#5b3fc4" stroke-width="2">
                        <circle cx="120" cy="140" r="10"/><circle cx="160" cy="140" r="10" fill="#5b3fc4"/><circle cx="200" cy="140" r="10"/><circle cx="240" cy="140" r="10"/>
                        <circle cx="120" cy="180" r="10"/><circle cx="160" cy="180" r="10"/><circle cx="200" cy="180" r="10" fill="#5b3fc4"/><circle cx="240" cy="180" r="10"/>
                        <circle cx="120" cy="220" r="10" fill="#5b3fc4"/><circle cx="160" cy="220" r="10"/><circle cx="200" cy="220" r="10"/><circle cx="240" cy="220" r="10"/>
                        <circle cx="120" cy="260" r="10"/><circle cx="160" cy="260" r="10"/><circle cx="200" cy="260" r="10"/><circle cx="240" cy="260" r="10" fill="#5b3fc4"/>
                        <circle cx="120" cy="300" r="10"/><circle cx="160" cy="300" r="10" fill="#5b3fc4"/><circle cx="200" cy="300" r="10"/><circle cx="240" cy="300" r="10"/>
                    </g>
                    <path d="M340 200 L388 200 M372 184 L388 200 L372 216" fill="none" stroke="#e5197d" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    <g>
                        <rect x="410" y="280" width="46" height="60" rx="10" fill="#5b3fc4" opacity=".25"/>
                        <rect x="470" y="245" width="46" height="95" rx="10" fill="#5b3fc4" opacity=".4"/>
                        <rect x="530" y="210" width="46" height="130" rx="10" fill="#5b3fc4" opacity=".6"/>
                        <rect x="590" y="175" width="46" height="165" rx="10" fill="#5b3fc4" opacity=".8"/>
                        <rect x="650" y="140" width="46" height="200" rx="10" fill="#e5197d"/>
                    </g>
                    <text x="673" y="122" text-anchor="middle" font-size="20" font-weight="800" fill="#e5197d">100%</text>
                    <path d="M404 340 H702" stroke="#e4defa" stroke-width="3" stroke-linecap="round"/>
                </svg>
            </div>
        </header>
        <hr class="sch-rule">

        <section class="sch-section" id="rewards">
            <div class="sch-wrap sch-center">
                <p class="sch-eyebrow">Mega rewards for toppers</p>
                <h2>What if your score could <span class="sch-em">cut your fees?</span></h2>
                <p class="sch-sub">The more you score in the test, the bigger your scholarship.</p>
                <div class="sch-grid sch-g5">
                    <div class="sch-card sch-tier sch-best"><div class="sch-score">Score a 100%,</div><div class="sch-pct">100%</div><div class="sch-score">Scholarship</div></div>
                    <div class="sch-card sch-tier"><div class="sch-score">Score 99–90%,</div><div class="sch-pct">75%</div><div class="sch-score">Scholarship</div></div>
                    <div class="sch-card sch-tier"><div class="sch-score">Score 89–80%,</div><div class="sch-pct">50%</div><div class="sch-score">Scholarship</div></div>
                    <div class="sch-card sch-tier"><div class="sch-score">Score 79–70%,</div><div class="sch-pct">25%</div><div class="sch-score">Scholarship</div></div>
                    <div class="sch-card sch-tier"><div class="sch-score">Score 69–60%,</div><div class="sch-pct">10%</div><div class="sch-score">Scholarship</div></div>
                </div>
                <p class="sch-sub sch-note">Score means your percentage of marks. The scholarship applies as a discount on the Rank Pro course fee. Terms apply.</p>
                <p style="margin-top:20px"><a class="sch-btn" href="#register">Register Free</a></p>
            </div>
        </section>
        <hr class="sch-rule">

        <section class="sch-section">
            <div class="sch-wrap sch-center">
                <p class="sch-eyebrow">What you get</p>
                <h2>Every student leaves with <span class="sch-em">more than a score</span></h2>
                <div class="sch-grid sch-g3" style="text-align:left">
                    <div class="sch-card"><div class="sch-n">01</div><h3>Strength &amp; weakness analysis</h3><p>See exactly which chapters to fix first.</p></div>
                    <div class="sch-card"><div class="sch-n">02</div><h3>Detailed result analysis</h3><p>Accuracy, time per question and weak areas.</p></div>
                    <div class="sch-card"><div class="sch-n">03</div><h3>Kota faculty questions</h3><p>NCERT-based questions with detailed solutions.</p></div>
                    <div class="sch-card"><div class="sch-n">04</div><h3>1:1 mentorship</h3><p>Guidance from faculty and NEET rankers.</p></div>
                    <div class="sch-card"><div class="sch-n">05</div><h3>Real NEET experience</h3><p>Sit a NEET-like paper and learn your rank level.</p></div>
                    <div class="sch-card"><div class="sch-n">06</div><h3>Scholarship reward</h3><p>Your score decides your discount on Rank Pro.</p></div>
                </div>
            </div>
        </section>
        <hr class="sch-rule">

        <section class="sch-section">
            <div class="sch-wrap sch-center">
                <p class="sch-eyebrow">Real results · Real rankers</p>
                <h2>Aspirants who trained on Rank Pro and <span class="sch-em">cracked NEET</span></h2>
                <div class="sch-rankers">
                    <div class="sch-card sch-rank"><span class="sch-air">AIR 1714</span><h3>Sugata Chowdhury</h3><div class="sch-marks">697 <small>score</small></div><p>Medical College, Kolkata</p></div>
                    <div class="sch-card sch-rank"><span class="sch-air">AIR 2633</span><h3>Nilangshuk Dutta</h3><div class="sch-marks">695 <small>score</small></div><p>Medical College, Kolkata</p></div>
                    <div class="sch-card sch-rank"><span class="sch-air">AIR 4400</span><h3>Annsha Singh</h3><div class="sch-marks">686 <small>score</small></div><p>NEET 2024 · IPGMER</p></div>
                    <div class="sch-card sch-rank"><span class="sch-air">AIR 7032</span><h3>Arpan Goswami</h3><div class="sch-marks">680 <small>score</small></div><p>Medical College, Kolkata</p></div>
                </div>
            </div>
        </section>
        <hr class="sch-rule">

        <section class="sch-section">
            <div class="sch-wrap sch-center">
                <p class="sch-eyebrow">Test details</p>
                <h2>Test <span class="sch-em">details</span></h2>
                <div class="sch-grid sch-g4">
                    <div class="sch-card"><div class="sch-ico">🖥️</div><h3>Mode of test</h3><p>Online or offline. Our team confirms the mode and slot with you.</p></div>
                    <div class="sch-card"><div class="sch-ico">⏱️</div><h3>Test duration</h3><p>3 hours, NEET-style paper</p></div>
                    <div class="sch-card"><div class="sch-ico">✅</div><h3>Registration</h3><p>Free. No application fee, no complicated process.</p></div>
                    <div class="sch-card"><div class="sch-ico">📊</div><h3>Result &amp; reward</h3><p>Your score, analysis and scholarship are shared after the test.</p></div>
                </div>
                <div class="sch-card sch-deadline">
                    <p class="sch-eyebrow">Scholarship duration</p>
                    <h3>Last date: <span>December 5</span></h3>
                </div>
            </div>
        </section>
        <hr class="sch-rule">

        <section class="sch-section">
            <div class="sch-wrap sch-center">
                <p class="sch-eyebrow">Eligibility</p>
                <h2>Who is this opportunity <span class="sch-em">for?</span></h2>
                <p class="sch-sub">Anyone who can appear for NEET can take this test. If you are determined and committed to becoming a doctor, this is for you.</p>
                <div class="sch-grid sch-g3" style="text-align:left">
                    <div class="sch-card"><div class="sch-n">CLASS 10</div><h3>Starting your NEET journey</h3><p>Dreaming of becoming a doctor? Experience a NEET-style paper early and begin with clarity.</p></div>
                    <div class="sch-card"><div class="sch-n">CLASS 10+2</div><h3>Class 11 and 12</h3><p>Check your NEET readiness, find your weak chapters and fix them in time.</p></div>
                    <div class="sch-card"><div class="sch-n">CLASS 12+</div><h3>12th pass &amp; beyond</h3><p>Making a comeback? Get an honest check of where you stand now.</p></div>
                </div>
            </div>
        </section>
        <hr class="sch-rule">

        <section class="sch-section" id="how">
            <div class="sch-wrap sch-center">
                <h2>How to take the test?</h2>
                <p class="sch-sub">Just 4 simple steps</p>
                <div class="sch-grid sch-g4" style="text-align:left">
                    <div class="sch-card"><div class="sch-n">01</div><h3>Register free</h3><p>Fill in the short form below with your name, phone number and class.</p></div>
                    <div class="sch-card"><div class="sch-n">02</div><h3>Confirm your slot</h3><p>Our team contacts you on WhatsApp or call to confirm your test mode, date and time.</p></div>
                    <div class="sch-card"><div class="sch-n">03</div><h3>Take the test</h3><p>Attempt the 3-hour NEET-style paper in the mode and slot confirmed for you.</p></div>
                    <div class="sch-card"><div class="sch-n">04</div><h3>Get result &amp; reward</h3><p>Receive your score, detailed analysis and scholarship.</p></div>
                </div>
                <p class="sch-done">That’s it. No fee. No complicated process.</p>
                <p style="margin-top:16px"><a class="sch-btn" href="#register">Register Free</a></p>
            </div>
        </section>
        <hr class="sch-rule">

        <section class="sch-section" id="register">
            <div class="sch-wrap sch-center">
                <h2>Register for the <span class="sch-em">Scholarship Test</span></h2>
                <p class="sch-sub">Fill in your details and our team will contact you on WhatsApp or call to confirm your test slot.</p>

                @if ($errors->any())
                    <div class="sch-alert" style="margin-top:24px">Please check the highlighted fields and try again.</div>
                @endif

                <form class="sch-form" id="schForm" method="post" action="{{ route('scholarship.register') }}" data-wa="{{ \App\Support\WebsiteSettings::digits('whatsapp') }}">
                    @csrf
                    <label>Full Name *
                        <input name="name" value="{{ old('name') }}" required autocomplete="name">
                        @error('name')<span class="sch-err">{{ $message }}</span>@enderror
                    </label>
                    <label>Phone Number *
                        <input name="phone" type="tel" value="{{ old('phone') }}" required autocomplete="tel" inputmode="tel" pattern="[0-9+\-\s()]{8,20}" title="Enter a valid phone number.">
                        @error('phone')<span class="sch-err">{{ $message }}</span>@enderror
                    </label>
                    <label>Class / Status *
                        <select name="class" required>
                            <option value="">Select</option>
                            @foreach (['Class 10', 'Class 10+2 (Class 11 / 12)', 'Class 12+ (12th pass & beyond)'] as $classOption)
                                <option value="{{ $classOption }}" {{ old('class') === $classOption ? 'selected' : '' }}>{{ $classOption }}</option>
                            @endforeach
                        </select>
                        @error('class')<span class="sch-err">{{ $message }}</span>@enderror
                    </label>
                    <label>City
                        <input name="city" value="{{ old('city') }}" autocomplete="address-level2">
                        @error('city')<span class="sch-err">{{ $message }}</span>@enderror
                    </label>
                    <label>Preferred mode
                        <select name="mode">
                            @foreach (['Online', 'Offline'] as $modeOption)
                                <option value="{{ $modeOption }}" {{ old('mode', 'Online') === $modeOption ? 'selected' : '' }}>{{ $modeOption }}</option>
                            @endforeach
                        </select>
                        @error('mode')<span class="sch-err">{{ $message }}</span>@enderror
                    </label>
                    <label>Email Address
                        <input name="email" type="email" value="{{ old('email') }}" autocomplete="email">
                        @error('email')<span class="sch-err">{{ $message }}</span>@enderror
                    </label>
                    <label>Message (Optional)
                        <textarea name="message" rows="3">{{ old('message') }}</textarea>
                        @error('message')<span class="sch-err">{{ $message }}</span>@enderror
                    </label>
                    <button class="sch-btn" type="submit">Register for the Test</button>
                </form>
                <p class="sch-sub sch-ok" id="schOk" @if (!session('scholarship_ok')) hidden @endif>
                    Thanks! Your registration is saved.
                    @if (session('scholarship_wa'))
                        <a href="{{ session('scholarship_wa') }}" target="_blank" rel="noopener noreferrer">Send it on WhatsApp</a>
                        so we can confirm your slot.
                    @else
                        WhatsApp should open with your details. Just press send and we will confirm your slot.
                    @endif
                </p>
            </div>
        </section>
        <hr class="sch-rule">

        <section class="sch-section" id="faq">
            <div class="sch-wrap">
                <div class="sch-center">
                    <p class="sch-eyebrow">Frequently asked</p>
                    <h2>Frequently asked <span class="sch-em">questions</span></h2>
                </div>
                @foreach ($scholarshipFaqs as $item)
                    <details>
                        <summary>{{ $item['q'] }}</summary>
                        <p>{{ $item['a'] }}</p>
                    </details>
                @endforeach
            </div>
        </section>
    </div>

    @include('site.include.footer')
    @include('site.include.call_to_action')
    @include('site.include.back_to_top')

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('web/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('web/js/main.js') }}"></script>
    <script>
        (function () {
            var form = document.getElementById('schForm');
            var ok = document.getElementById('schOk');
            if (!form) return;
            form.addEventListener('submit', function (event) {
                if (!window.fetch) return;
                event.preventDefault();
                var data = new FormData(form);
                var text = [
                    'Scholarship Test Registration',
                    'Name: ' + (data.get('name') || ''),
                    'Phone: ' + (data.get('phone') || ''),
                    'Class: ' + (data.get('class') || ''),
                    'City: ' + (data.get('city') || '-'),
                    'Mode: ' + (data.get('mode') || ''),
                    'Email: ' + (data.get('email') || '-'),
                    'Message: ' + (data.get('message') || '-')
                ].join('\n');
                window.open('https://wa.me/' + form.dataset.wa + '?text=' + encodeURIComponent(text), '_blank');
                var button = form.querySelector('button[type="submit"]');
                button.disabled = true;
                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                }).then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data };
                    });
                }).then(function (result) {
                    button.disabled = false;
                    form.querySelectorAll('.sch-err').forEach(function (node) { node.remove(); });
                    if (!result.ok) {
                        var errors = (result.data && result.data.errors) || {};
                        Object.keys(errors).forEach(function (field) {
                            var input = form.querySelector('[name="' + field + '"]');
                            if (!input) return;
                            var note = document.createElement('span');
                            note.className = 'sch-err';
                            note.textContent = errors[field][0];
                            input.insertAdjacentElement('afterend', note);
                        });
                        return;
                    }
                    if (ok) {
                        ok.hidden = false;
                        ok.textContent = 'Thanks! WhatsApp should open with your details. Just press send and we will confirm your slot.';
                    }
                    form.reset();
                }).catch(function () {
                    button.disabled = false;
                    form.submit();
                });
            });
        })();
    </script>
</body>
</html>
