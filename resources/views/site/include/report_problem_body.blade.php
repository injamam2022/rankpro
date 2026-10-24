@php
    $tab = $tab ?? 'all';
    $faqs = $faqs ?? [];
    $faqTopics = $faqTopics ?? ['All'];
    $categories = $categories ?? [];
    $tickets = $tickets ?? [];
    $examOptions = $examOptions ?? [];
    $catKeys = array_keys($categories);
    $firstCat = $catKeys[0] ?? 'Mock Test Queries';
    $openCount = collect($tickets)->count();
@endphp

<div class="rp-help" id="rpHelp" data-tab="{{ $tab }}" data-categories='@json($categories)'>
    <div class="pr-card pr-hero">
        <div class="pr-hero__top">
            <div class="pr-hero__title-wrap">
                <span class="pr-icon pr-icon--blue"><i class="far fa-question-circle"></i></span>
                <div>
                    <div class="pr-hero__title-row">
                        <h1 class="pr-hero__title">Help, Support &amp; Report a Problem</h1>
                        <span class="pr-pill pr-pill--green">24x7 Academic Desk</span>
                    </div>
                </div>
            </div>
            <div class="rp-tabs" role="tablist">
                <button type="button" class="rp-tab {{ $tab === 'all' ? 'is-on-indigo' : '' }}" data-tab="all">Report &amp; FAQs</button>
                <button type="button" class="rp-tab {{ $tab === 'report' ? 'is-on-pink' : '' }}" data-tab="report">Add a Problem</button>
                <button type="button" class="rp-tab {{ $tab === 'faqs' ? 'is-on-blue' : '' }}" data-tab="faqs">List of FAQs</button>
                <button type="button" class="rp-tab {{ $tab === 'track' ? 'is-on-ink' : '' }}" data-tab="track">
                    Track Complaints <span class="rp-tab__count">{{ $openCount }}</span>
                </button>
            </div>
        </div>
        <div class="rp-help-kpis">
            <div class="rp-help-kpi rp-help-kpi--blue">
                <i class="far fa-clock"></i>
                <div>
                    <span>Average Resolution Time</span>
                    <b>Under 4 Hours</b>
                </div>
            </div>
            <div class="rp-help-kpi rp-help-kpi--green">
                <i class="fas fa-check-circle"></i>
                <div>
                    <span>NCERT Academic Verification</span>
                    <b>Verified by Senior Faculty</b>
                </div>
            </div>
            <div class="rp-help-kpi rp-help-kpi--purple">
                <i class="fas fa-shield-alt"></i>
                <div>
                    <span>Scorecard Adjustment Policy</span>
                    <b>Instant Key Re-calculation</b>
                </div>
            </div>
        </div>
    </div>

    <div class="rp-pane" data-pane="faqs" @if(!in_array($tab, ['all','faqs'], true)) hidden @endif>
        <div class="pr-card rp-faq-tools">
            <div class="rp-faq-search">
                <i class="fas fa-search"></i>
                <input type="search" id="faqSearch" placeholder="Search FAQs (e.g. negative marking, OMR bubbling, timer freeze)...">
            </div>
            <div class="rp-faq-topics">
                <span>Topic:</span>
                @foreach($faqTopics as $topic)
                    <button type="button" class="rp-topic {{ $topic === 'All' ? 'is-on' : '' }}" data-topic="{{ $topic }}">{{ $topic === 'All' ? 'All' : $topic }}</button>
                @endforeach
            </div>
        </div>

        <div class="pr-card rp-faq-list" id="faqList">
            @foreach($faqs as $i => $faq)
                <div class="rp-faq {{ $i === 0 ? 'is-open' : '' }}" data-category="{{ $faq['category'] }}" data-q="{{ strtolower($faq['question'].' '.$faq['answer']) }}">
                    <button type="button" class="rp-faq__btn">
                        <div>
                            <span class="pr-chip">{{ $faq['category'] }}</span>
                            <h3>{{ $faq['question'] }}</h3>
                        </div>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="rp-faq__body">
                        <p>{{ $faq['answer'] }}</p>
                        <div class="rp-faq__foot">
                            <span>Was this helpful? ({{ $faq['helpful'] }} students found this helpful)</span>
                            <span class="is-green">✓ Verified Answer</span>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="pr-empty" id="faqEmpty" hidden>No FAQs matching that search. Try another question or report your problem directly.</div>
        </div>
    </div>

    <div class="rp-pane" data-pane="report" @if(!in_array($tab, ['all','report'], true)) hidden @endif>
        <div class="pr-card">
            <div class="pr-card__head">
                <h2 class="pr-card__title">Submit an Academic or Website Problem</h2>
                <span class="pr-pill pr-pill--blue">Response within 4 Hours</span>
            </div>

            @if(session('success'))
                <div class="rp-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="rp-error">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('report_problem_save') }}" method="post" enctype="multipart/form-data" class="rp-form" id="problemForm" onsubmit="return rpValidate()">
                @csrf
                <div class="rp-form-grid">
                    <div>
                        <label>1. Problem Category:</label>
                        <select name="category" id="rpCategory" required>
                            @foreach($categories as $cat => $subs)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label>2. Specific Subcategory:</label>
                        <select name="subcategory" id="rpSubcategory" required>
                            @foreach(($categories[$firstCat] ?? []) as $sub)
                                <option value="{{ $sub }}">{{ $sub }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="rp-mock-box" id="rpMockBox">
                    <div class="rp-form-grid rp-form-grid--3">
                        <div class="is-wide">
                            <label>Related Mock Exam Series:</label>
                            <select name="mock_exam">
                                <option value="">Select a mock (optional)</option>
                                @forelse($examOptions as $exam)
                                    <option value="{{ $exam['label'] }}">{{ $exam['label'] }}</option>
                                @empty
                                    <option value="NEET Grand Mock">NEET Grand Mock</option>
                                @endforelse
                            </select>
                        </div>
                        <div>
                            <label>Question # (Optional):</label>
                            <input type="text" name="question_number" placeholder="e.g. Q42 or Q18">
                        </div>
                    </div>
                </div>

                <div class="rp-form-grid">
                    <div>
                        <label>Subject / Discipline:</label>
                        <select name="subject_label">
                            <option value="Physics">Physics</option>
                            <option value="Chemistry">Chemistry</option>
                            <option value="Biology - Botany">Biology - Botany</option>
                            <option value="Biology - Zoology">Biology - Zoology</option>
                            <option value="General / Platform & UI">General / Platform &amp; UI</option>
                        </select>
                    </div>
                    <div>
                        <label>Issue Urgency / Priority:</label>
                        <select name="priority">
                            <option value="Low">Low — General feedback</option>
                            <option value="Medium" selected>Medium — Standard correction query</option>
                            <option value="High">High — Key or score calculation affecting rank</option>
                            <option value="Urgent">Urgent — Live test session blocked</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label>Query Title / Brief Summary:</label>
                    <input type="text" name="title" id="rpTitle" placeholder="e.g. Key discrepancy in Rotational Motion rolling cylinder question Q18" required>
                </div>
                <div>
                    <label>Detailed Description &amp; Reproduction Steps:</label>
                    <textarea name="message" id="rpMessage" rows="4" placeholder="Explain the discrepancy clearly. Include NCERT chapter/page citations if questioning an official key or formula..." required></textarea>
                </div>
                <div>
                    <label>Attach Screenshot or Question Reference Photo:</label>
                    <div class="rp-file">
                        <label class="rp-file__btn">
                            <i class="fas fa-paperclip"></i>
                            <span id="rpFileLabel">Upload Screenshot (PNG, JPG, PDF)</span>
                            <input type="file" name="attachment" id="rpFile" accept=".png,.jpg,.jpeg,.pdf" hidden>
                        </label>
                        <button type="button" class="rp-file__clear" id="rpFileClear" hidden>Remove</button>
                    </div>
                </div>
                <div class="rp-captcha">
                    <div>
                        <span id="captcha_id"></span>
                        <a href="javascript:void(0)" onclick="changeCapture()"><i class="bi bi-arrow-clockwise"></i></a>
                    </div>
                    <input type="text" id="captcha_text" name="captcha_text" placeholder="Enter the code">
                </div>
                <div class="rp-form__actions">
                    <button type="button" class="rp-btn-ghost" id="rpClear">Clear Form</button>
                    <button type="submit" class="rp-btn-pink">Submit Query to Academic Desk</button>
                </div>
            </form>
        </div>
    </div>

    <div class="rp-pane" data-pane="track" @if($tab !== 'track') hidden @endif>
        <div class="pr-card rp-track-tools">
            <div class="pr-card__title"><i class="fas fa-filter"></i> Filter Status:</div>
            <div class="mm-seg mm-seg--dark" id="ticketStatusSeg">
                @foreach(['All','Open','In Review','Resolved'] as $st)
                    <button type="button" class="{{ $st === 'All' ? 'is-on' : '' }}" data-status="{{ $st }}">{{ $st }}</button>
                @endforeach
            </div>
        </div>

        <div id="ticketList">
            @forelse($tickets as $i => $ticket)
                <div class="rp-ticket {{ $i === 0 ? 'is-open' : '' }}" data-status="{{ $ticket['status'] }}">
                    <button type="button" class="rp-ticket__head">
                        <div>
                            <div class="rp-ticket__tags">
                                <span class="pr-chip pr-chip--blue">{{ $ticket['number'] }}</span>
                                <span class="pr-chip">{{ $ticket['category'] }}</span>
                                <span class="rp-ticket__sub">{{ $ticket['subcategory'] }}</span>
                                @if(in_array($ticket['priority'], ['High','Urgent'], true))
                                    <span class="pr-chip pr-chip--red">{{ $ticket['priority'] }}</span>
                                @endif
                            </div>
                            <h3>{{ $ticket['title'] }}</h3>
                            <div class="rp-ticket__date">Submitted on {{ $ticket['created'] }}</div>
                        </div>
                        <div class="rp-ticket__status">
                            @if($ticket['status'] === 'Resolved')
                                <span class="pr-pill pr-pill--green"><i class="fas fa-check-circle"></i> Resolved</span>
                            @elseif($ticket['status'] === 'In Review')
                                <span class="pr-pill pr-pill--amber"><i class="far fa-clock"></i> In Review by Academic Team</span>
                            @else
                                <span class="pr-pill pr-pill--blue"><i class="far fa-clock"></i> Open</span>
                            @endif
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </button>
                    <div class="rp-ticket__body">
                        <div class="rp-ticket__desc">
                            <div class="rp-ticket__label">Query Description:</div>
                            <p>{{ $ticket['description'] }}</p>
                        </div>
                        @if($ticket['mock'] || $ticket['question'] || $ticket['subject'])
                            <div class="rp-ticket__meta">
                                @if($ticket['mock']) <span>Mock: <b>{{ $ticket['mock'] }}</b></span> @endif
                                @if($ticket['question']) <span>· Question: <b>{{ $ticket['question'] }}</b></span> @endif
                                @if($ticket['subject']) <span>· Subject: <b>{{ $ticket['subject'] }}</b></span> @endif
                            </div>
                        @endif
                        @if($ticket['attachment'])
                            <a class="rp-ticket__file" href="{{ $ticket['attachment'] }}" target="_blank" rel="noopener">
                                <i class="fas fa-paperclip"></i> Attachment: {{ $ticket['attachment_name'] }}
                            </a>
                        @endif
                        @if($ticket['resolution'])
                            <div class="rp-ticket__res">
                                <strong><i class="fas fa-check-circle"></i> Resolution &amp; Academic Response:</strong>
                                <p>{{ $ticket['resolution'] }}</p>
                            </div>
                        @endif
                        <div class="rp-ticket__thread">
                            <div class="rp-ticket__label">Communication History:</div>
                            @foreach($ticket['messages'] as $msg)
                                <div class="rp-msg {{ $msg['from'] === 'user' ? 'is-user' : 'is-staff' }}">
                                    <div>
                                        <b>{{ $msg['name'] }} ({{ $msg['from'] === 'user' ? 'You' : 'Academic Faculty' }})</b>
                                        <span>{{ $msg['at'] }}</span>
                                    </div>
                                    <p>{{ $msg['text'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @empty
                <div class="pr-card pr-empty">No complaint tickets yet. Submit a query from Add a Problem.</div>
            @endforelse
            <div class="pr-card pr-empty" id="ticketEmpty" hidden>No complaint tickets found under that status.</div>
        </div>
    </div>
</div>

<script>
(function () {
    var root = document.getElementById('rpHelp');
    if (!root) return;
    var categories = {};
    try { categories = JSON.parse(root.getAttribute('data-categories') || '{}'); } catch (e) { categories = {}; }
    var mockCats = ['Mock Test Queries', 'Question & Solution Discrepancy', 'Scorecard & All-India Ranking'];

    function setTab(tab) {
        root.setAttribute('data-tab', tab);
        root.querySelectorAll('.rp-tab').forEach(function (btn) {
            btn.classList.remove('is-on-indigo', 'is-on-pink', 'is-on-blue', 'is-on-ink');
            if (btn.getAttribute('data-tab') === tab) {
                if (tab === 'all') btn.classList.add('is-on-indigo');
                else if (tab === 'report') btn.classList.add('is-on-pink');
                else if (tab === 'faqs') btn.classList.add('is-on-blue');
                else btn.classList.add('is-on-ink');
            }
        });
        root.querySelectorAll('.rp-pane').forEach(function (pane) {
            var name = pane.getAttribute('data-pane');
            var show = (tab === 'all' && (name === 'faqs' || name === 'report')) || name === tab;
            pane.hidden = !show;
        });
        var url = new URL(window.location.href);
        url.searchParams.set('tab', tab);
        history.replaceState({}, '', url.toString());
    }

    root.querySelectorAll('.rp-tab').forEach(function (btn) {
        btn.addEventListener('click', function () { setTab(btn.getAttribute('data-tab')); });
    });

    var search = document.getElementById('faqSearch');
    var topic = 'All';
    function filterFaqs() {
        var q = (search && search.value || '').toLowerCase().trim();
        var any = false;
        document.querySelectorAll('#faqList .rp-faq').forEach(function (el) {
            var cat = el.getAttribute('data-category') || '';
            var hay = el.getAttribute('data-q') || '';
            var ok = (topic === 'All' || cat === topic) && (!q || hay.indexOf(q) !== -1);
            el.hidden = !ok;
            if (ok) any = true;
        });
        var empty = document.getElementById('faqEmpty');
        if (empty) empty.hidden = any;
    }
    document.querySelectorAll('.rp-topic').forEach(function (btn) {
        btn.addEventListener('click', function () {
            topic = btn.getAttribute('data-topic');
            document.querySelectorAll('.rp-topic').forEach(function (b) { b.classList.toggle('is-on', b === btn); });
            filterFaqs();
        });
    });
    if (search) search.addEventListener('input', filterFaqs);

    document.querySelectorAll('.rp-faq__btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var item = btn.closest('.rp-faq');
            var open = item.classList.contains('is-open');
            document.querySelectorAll('.rp-faq').forEach(function (el) { el.classList.remove('is-open'); });
            if (!open) item.classList.add('is-open');
        });
    });

    var catSel = document.getElementById('rpCategory');
    var subSel = document.getElementById('rpSubcategory');
    var mockBox = document.getElementById('rpMockBox');
    function fillSubs() {
        var cat = catSel.value;
        var list = categories[cat] || [];
        subSel.innerHTML = '';
        list.forEach(function (s) {
            var opt = document.createElement('option');
            opt.value = s; opt.textContent = s;
            subSel.appendChild(opt);
        });
        mockBox.hidden = mockCats.indexOf(cat) === -1;
    }
    if (catSel) { catSel.addEventListener('change', fillSubs); fillSubs(); }

    var file = document.getElementById('rpFile');
    var fileLabel = document.getElementById('rpFileLabel');
    var fileClear = document.getElementById('rpFileClear');
    if (file) {
        file.addEventListener('change', function () {
            var name = file.files && file.files[0] ? file.files[0].name : '';
            fileLabel.textContent = name ? ('File: ' + name) : 'Upload Screenshot (PNG, JPG, PDF)';
            fileClear.hidden = !name;
        });
        fileClear.addEventListener('click', function () {
            file.value = '';
            fileLabel.textContent = 'Upload Screenshot (PNG, JPG, PDF)';
            fileClear.hidden = true;
        });
    }
    var clearBtn = document.getElementById('rpClear');
    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            document.getElementById('rpTitle').value = '';
            document.getElementById('rpMessage').value = '';
            document.querySelector('[name="question_number"]').value = '';
            if (file) { file.value = ''; fileLabel.textContent = 'Upload Screenshot (PNG, JPG, PDF)'; fileClear.hidden = true; }
        });
    }

    var statusFilter = 'All';
    document.querySelectorAll('#ticketStatusSeg button').forEach(function (btn) {
        btn.addEventListener('click', function () {
            statusFilter = btn.getAttribute('data-status');
            document.querySelectorAll('#ticketStatusSeg button').forEach(function (b) { b.classList.toggle('is-on', b === btn); });
            var any = false;
            document.querySelectorAll('#ticketList .rp-ticket').forEach(function (el) {
                var ok = statusFilter === 'All' || el.getAttribute('data-status') === statusFilter;
                el.hidden = !ok;
                if (ok) any = true;
            });
            var empty = document.getElementById('ticketEmpty');
            if (empty) empty.hidden = any;
        });
    });
    document.querySelectorAll('.rp-ticket__head').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var item = btn.closest('.rp-ticket');
            item.classList.toggle('is-open');
        });
    });
})();

var g_captcha_id = '';
function genRandomString(length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    for (var i = 0; i < length; i++) result += characters.charAt(Math.floor(Math.random() * characters.length));
    return result;
}
function changeCapture() {
    g_captcha_id = genRandomString(6);
    var el = document.getElementById('captcha_id');
    if (el) el.textContent = g_captcha_id;
}
function rpValidate() {
    var ok = true;
    var title = document.getElementById('rpTitle');
    var msg = document.getElementById('rpMessage');
    var cap = document.getElementById('captcha_text');
    [title, msg, cap].forEach(function (el) { if (el) el.classList.remove('dangerBoader'); });
    if (title && !title.value.trim()) { title.classList.add('dangerBoader'); ok = false; }
    if (msg && !msg.value.trim()) { msg.classList.add('dangerBoader'); ok = false; }
    if (cap && cap.value !== g_captcha_id) { cap.classList.add('dangerBoader'); ok = false; }
    return ok;
}
changeCapture();
</script>
