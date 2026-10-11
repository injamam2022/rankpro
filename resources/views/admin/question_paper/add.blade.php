@extends('layouts.backend')


@section('css_after')
<style>
    .qb-mix { background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:18px 18px 10px; }
    .qb-mix__head { display:flex; justify-content:space-between; align-items:flex-start; gap:12px; margin-bottom:12px; }
    .qb-mix__head h5 { margin:0; font-weight:700; }
    .qb-mix__hint { margin:4px 0 0; font-size:12px; color:#64748b; max-width:640px; }
    .qb-mix__grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:12px; }
    .qb-mix__card { background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:12px; }
    .qb-mix__card.is-focus { border-color:#7c3aed; box-shadow:0 0 0 3px rgba(124,58,237,.12); }
    .qb-mix__card label { font-weight:600; margin-bottom:6px; }
    .qb-mix__bar { height:6px; background:#e2e8f0; border-radius:99px; overflow:hidden; margin-top:8px; }
    .qb-mix__bar span { display:block; height:100%; width:0; background:#7c3aed; }
    .qb-mix__card.is-hard .qb-mix__bar span { background:#dc2626; }
    .qb-mix__card.is-medium .qb-mix__bar span { background:#d97706; }
    .qb-mix__card.is-easy .qb-mix__bar span { background:#16a34a; }
    .qb-mix__meta { display:flex; justify-content:space-between; font-size:12px; color:#64748b; margin-top:6px; }
    .qb-mix__sum { margin-top:10px; font-size:13px; font-weight:600; }
    .qb-mix__sum.is-bad { color:#dc2626; }
    .qb-mix__sum.is-ok { color:#15803d; }
    .qb-types { margin-top:8px; }
    .qb-types__head { display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:8px; flex-wrap:wrap; }
    .qb-types__head h5 { margin:0; font-weight:700; }
    .qb-type { background:#fff; border:1px solid #e2e8f0; border-radius:8px; padding:8px 10px 10px; height:100%; }
    .qb-type.is-zero { opacity:.55; }
    .qb-type.is-focus { border-color:#7c3aed; }
    .qb-type label { font-size:12px; margin-bottom:4px; line-height:1.25; }
    .qb-type small { display:block; font-size:11px; color:#64748b; margin-top:4px; }
    .qb-warn { font-size:12px; color:#b45309; margin-top:8px; }
    .qb-types-total { font-size:13px; font-weight:600; }
</style>
@endsection

@section('content')

    <div class="container-xl px-5">
        <div class="d-flex mt-10 mb-4 align-items-center">
            <h1 class="page-header mb-0">Add Question Bank</h1>
        </div>
        <div class="row gx-5">
            <div class="col-lg-12">
                <form action="{{route('admin.question_paper.save')}}" method="post"  enctype="multipart/form-data" onsubmit="return formValidation();">
                    @csrf
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="name" type="text" name="name" placeholder=""  value="" />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="no_of_question">Total No. Of Questions <span class="text-danger">*</span></label>
                            <input class="form-control" id="no_of_question" type="number" name="no_of_question" placeholder=""  value="" onkeyup="changeTotalMarkForExam();changeTotalTimeForExam();"/>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="marks_per_question">Marks Per Question <span class="text-danger">*</span></label>
                            <input class="form-control" id="marks_per_question" type="number" name="marks_per_question" placeholder=""  value=""  onkeyup="changeTotalMarkForExam();"/>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="totals_marks_for_exam">Totals Marks for Exam</label>
                            <input class="form-control" id="totals_marks_for_exam" type="number" name="totals_marks_for_exam" placeholder=""  value="" readonly/>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="time_per_question">Time Per Question (In minutes) <span class="text-danger">*</span></label>
                            <input class="form-control" id="time_per_question" type="text" name="time_per_question" placeholder=""  value="" onkeyup="changeTotalTimeForExam();"/>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="total_time_for_exam">Total Time for Exam (In minutes)</label>
                            <input class="form-control" id="total_time_for_exam" type="text" name="total_time_for_exam" placeholder=""  value="" readonly />
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label class="form-label" for="negative_marking_applicable">Negative marking applicable</label>
                            <div class="form-control" style="display:flex;">
                                <div class="form-check">
                                    <input class="form-check-input" id="negative_marking_applicable_active" type="radio" name="negative_marking_applicable" checked value="1"  onchange="changeNegativeMarkingApplicable(1)"/>
                                    <label class="form-check-label" for="negative_marking_applicable_active">Yes</label>
                                </div>
                                <div class="form-check" style="margin-left: 15px;">
                                    <input class="form-check-input" id="negative_marking_applicable_inactive" type="radio" name="negative_marking_applicable" value="0" onchange="changeNegativeMarkingApplicable(0)" />
                                    <label class="form-check-label" for="negative_marking_applicable_inactive">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6" id="negative_marking_per_question_div">
                            <label class="form-label" for="negative_marking_per_question">Negative marking per incorrect Answer</label>
                            <input class="form-control" id="negative_marking_per_question" type="text" name="negative_marking_per_question" placeholder=""  value="" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <div class="qb-mix">
                                <div class="qb-mix__head">
                                    <div>
                                        <h5>Difficulty <span class="text-danger">*</span></h5>
                                        <p class="qb-mix__hint">Choose one. The paper will pick questions of that difficulty only.</p>
                                    </div>
                                </div>
                                <div class="rp-diff-radios">
                                    <label class="rp-diff is-hard">
                                        <input type="radio" name="difficulty" value="hard">
                                        <span>Hard</span>
                                    </label>
                                    <label class="rp-diff is-medium">
                                        <input type="radio" name="difficulty" value="medium">
                                        <span>Medium</span>
                                    </label>
                                    <label class="rp-diff is-easy">
                                        <input type="radio" name="difficulty" value="easy">
                                        <span>Easy</span>
                                    </label>
                                </div>
                                <div class="qb-mix__sum" id="level_message"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">

                        <div class="col-sm-12 alert alert-success" style="padding:30px;margin-top:20px;">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h4 class="" style="font-weight: blod;">
                                        Subject List
                                    </h4>
                                </div>
                                <div class="col-sm-6" style="text-align:right;">
                                    <a href="javascript:void(0);">
                                        <img src="{{asset('')}}image/add-box.png" style="" onclick="addNewSubject();">
                                    </a>
                                </div>
                            </div>
                            <div id="subject_list_view">
                                <div class="row mt-2" id="subject_view_0">
                                    <div class="col-sm-2">
                                        <label class="form-label" for="subject_id_0">Subject <span class="text-danger">*</span></label>
                                        <select class="form-control subject_id" id="subject_id_0" name="subject_id[0]" onchange="changeSubject(0);">
                                            <option value="">Select Subject</option>
                                            @foreach($subject_list as $key => $value)
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="form-label" for="chapter_id_0">Chapter</label>
                                        <select class="form-control chapter_id" id="chapter_id_0" name="chapter_id[0]" onchange="changeChapter(0);">
                                            <option value="">Select Chapter</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="form-label" for="topic_id_0">Topic</label>
                                        <select class="form-control topic_id" id="topic_id_0" name="topic_id[0]" onchange="changeTopic(0);">
                                            <option value="">Select Topic</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="form-label" for="sub_topic_id_0">Sub Topic</label>
                                        <select class="form-control sub_topic_id" id="sub_topic_id_0" name="sub_topic_id[0]">
                                            <option value="">Select Sub Topic</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="form-label" for="total_no_of_question_0">No Of questions <span class="text-danger">*</span></label>
                                        <input class="form-control total_no_of_question" id="total_no_of_question_0" type="number" name="total_no_of_question[0]" placeholder=""  value="" />
                                    </div>
                                    <div class="col-sm-1" style="margin-top: 25px;">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">


                        <div class="col-sm-6">
                            <label class="form-label" for="exampleFormControlInput">Status</label>
                            <div class="form-control" style="display:flex;">
                                <div class="form-check">
                                    <input class="form-check-input" id="status_active" type="radio" name="status" checked value="1" />
                                    <label class="form-check-label" for="status_active">Active</label>
                                </div>
                                <div class="form-check" style="margin-left: 15px;">
                                    <input class="form-check-input" id="status_inactive" type="radio" name="status" value="0" />
                                    <label class="form-check-label" for="status_inactive">Inactive</label>
                                </div>
                            </div>
                        </div>

                        
                        <div class="col-lg-12 mt-5 text-center">
                            <a href="javascript:void(0);" onclick="history.go(-1);" class="rp-act rp-act-ghost cancelButton">
                                <i class="material-icons">close</i>
                                Cancel
                            </a>
                            <button class="rp-act rp-act-add" type="submit">
                                <i class="material-icons">check</i>
                                Submit
                            </button>
                        </div>
                        <div class="mb-5">
                            &nbsp;
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@section('js_after')
    <script type="text/javascript">
        var subject_count = 0;
        var globalData = {
            subject_list : <?php echo json_encode($subject_list);?>,
        };
        var qbTypesLocked = false;
        var qbLinking = false;
        var qbPreviewTimer = null;
        var qbMixUrl = @json(route('admin.question_paper.mix_preview'));

        function qbNum(id) {
            var el = document.getElementById(id);
            var n = parseFloat(el && el.value ? el.value : 0);
            return isNaN(n) ? 0 : n;
        }

        function qbTotalQuestions() {
            return parseInt(document.getElementById('no_of_question').value || 0, 10) || 0;
        }

        function qbSplitCounts(total, weights) {
            var keys = Object.keys(weights);
            var out = {};
            var sum = 0;
            keys.forEach(function (k) {
                sum += Math.max(0, Number(weights[k]) || 0);
                out[k] = 0;
            });
            if (total <= 0 || keys.length === 0) return out;
            if (sum <= 0) {
                out[keys[0]] = total;
                return out;
            }
            var frac = [];
            keys.forEach(function (k) {
                var exact = total * (Math.max(0, Number(weights[k]) || 0) / sum);
                out[k] = Math.floor(exact);
                frac.push({ k: k, f: exact - out[k] });
            });
            var remain = total - keys.reduce(function (s, k) { return s + out[k]; }, 0);
            frac.sort(function (a, b) { return b.f - a.f; });
            for (var i = 0; i < frac.length && remain > 0; i++) {
                out[frac[i].k]++;
                remain--;
            }
            return out;
        }

        function qbUpdateMixUi() {
            var hard = qbNum('hard_level');
            var medium = qbNum('medium_level');
            var easy = qbNum('easy_level');
            var totalPct = hard + medium + easy;
            var n = qbTotalQuestions();
            var counts = qbSplitCounts(n, { hard: hard, medium: medium, easy: easy });
            ['hard', 'medium', 'easy'].forEach(function (k) {
                var bar = document.getElementById('mix_bar_' + k);
                var countEl = document.getElementById('mix_count_' + k);
                var card = document.getElementById('mix_card_' + k);
                var pct = k === 'hard' ? hard : (k === 'medium' ? medium : easy);
                if (bar) bar.style.width = Math.max(0, Math.min(100, pct)) + '%';
                if (countEl) countEl.textContent = (counts[k] || 0) + ' questions';
                if (card) card.classList.remove('is-focus');
            });
            var focus = 'medium';
            if (hard >= medium && hard >= easy) focus = 'hard';
            else if (easy > hard && easy > medium) focus = 'easy';
            var focusCard = document.getElementById('mix_card_' + focus);
            if (focusCard && totalPct > 0) focusCard.classList.add('is-focus');

            var msg = document.getElementById('level_message');
            if (msg) {
                if (!n) {
                    msg.textContent = 'Enter total questions, then set the mix.';
                    msg.className = 'qb-mix__sum';
                } else if (Math.round(totalPct) !== 100) {
                    msg.textContent = 'Difficulty total is ' + totalPct + '%. It must be 100%.';
                    msg.className = 'qb-mix__sum is-bad';
                } else {
                    var label = focus.charAt(0).toUpperCase() + focus.slice(1);
                    msg.textContent = '100% · ' + n + ' questions · focus ' + label + ' (' + (counts[focus] || 0) + '). Types below follow this mix.';
                    msg.className = 'qb-mix__sum is-ok';
                }
            }
            qbUpdateTypeTotal();
        }

        function qbUpdateTypeTotal() {
            var n = qbTotalQuestions();
            var sum = 0;
            document.querySelectorAll('input.question_type_list').forEach(function (input) {
                if (input.value) sum += parseInt(input.value, 10) || 0;
            });
            var label = document.getElementById('question_type_total_label');
            if (label) label.textContent = sum + ' / ' + n;
            var msg = document.getElementById('question_type_message');
            if (msg) {
                msg.textContent = (n && sum && sum !== n) ? ('Question type counts must equal ' + n + '.') : '';
            }
        }

        function qbLinkDifficulty(changed) {
            if (qbLinking) return;
            qbLinking = true;
            var keys = ['hard', 'medium', 'easy'];
            var ids = { hard: 'hard_level', medium: 'medium_level', easy: 'easy_level' };
            var vals = {
                hard: qbNum('hard_level'),
                medium: qbNum('medium_level'),
                easy: qbNum('easy_level')
            };
            var v = Math.max(0, Math.min(100, vals[changed]));
            document.getElementById(ids[changed]).value = v === 0 && document.getElementById(ids[changed]).value === '' ? '' : v;
            vals[changed] = v;
            var rest = 100 - v;
            var others = keys.filter(function (k) { return k !== changed; });
            var otherSum = others.reduce(function (s, k) { return s + vals[k]; }, 0);
            if (otherSum <= 0) {
                document.getElementById(ids[others[0]]).value = rest;
                document.getElementById(ids[others[1]]).value = 0;
            } else {
                var split = qbSplitCounts(rest, { a: vals[others[0]], b: vals[others[1]] });
                document.getElementById(ids[others[0]]).value = split.a;
                document.getElementById(ids[others[1]]).value = split.b;
            }
            qbLinking = false;
            qbUpdateMixUi();
            qbSchedulePreview();
        }

        function qbCollectSections() {
            var subject_id = {};
            document.querySelectorAll('select.subject_id').forEach(function (el) {
                var m = el.id.match(/subject_id_(\d+)/);
                if (m) subject_id[m[1]] = el.value;
            });
            var payload = {
                _token: document.querySelector('input[name="_token"]').value,
                no_of_question: qbTotalQuestions(),
                hard_level: qbNum('hard_level'),
                medium_level: qbNum('medium_level'),
                easy_level: qbNum('easy_level'),
                subject_id: {},
                chapter_id: {},
                topic_id: {},
                sub_topic_id: {},
                total_no_of_question: {}
            };
            Object.keys(subject_id).forEach(function (idx) {
                payload.subject_id[idx] = document.getElementById('subject_id_' + idx).value;
                payload.chapter_id[idx] = document.getElementById('chapter_id_' + idx).value;
                payload.topic_id[idx] = document.getElementById('topic_id_' + idx).value;
                payload.sub_topic_id[idx] = document.getElementById('sub_topic_id_' + idx).value;
                payload.total_no_of_question[idx] = document.getElementById('total_no_of_question_' + idx).value;
            });
            return payload;
        }

        function qbSchedulePreview() {
            clearTimeout(qbPreviewTimer);
            qbPreviewTimer = setTimeout(qbFetchPreview, 280);
        }

        function qbFetchPreview() {
            qbUpdateMixUi();
            if (!qbTotalQuestions() || Math.round(qbNum('hard_level') + qbNum('medium_level') + qbNum('easy_level')) !== 100) {
                return;
            }
            $.ajax({
                url: qbMixUrl,
                method: 'POST',
                data: qbCollectSections(),
                dataType: 'json'
            }).done(function (res) {
                ['hard', 'medium', 'easy'].forEach(function (k) {
                    var poolEl = document.getElementById('mix_pool_' + k);
                    if (poolEl && res.difficulty && res.difficulty[k]) {
                        poolEl.textContent = 'pool ' + res.difficulty[k].pool;
                    }
                });
                if (!qbTypesLocked && res.types) {
                    res.types.forEach(function (t) {
                        var input = document.getElementById('question_type_' + t.id);
                        var card = document.getElementById('qb_type_card_' + t.id);
                        var pool = document.getElementById('qb_type_pool_' + t.id);
                        if (input) input.value = t.count > 0 ? t.count : '';
                        if (card) {
                            card.classList.toggle('is-zero', !t.count);
                            card.classList.toggle('is-focus', t.count > 0 && t.pool && t.pool.hard >= t.pool.easy && t.pool.hard >= t.pool.medium);
                        }
                        if (pool) {
                            pool.textContent = t.pool.total
                                ? ('pool ' + t.pool.total + ' · H' + t.pool.hard + ' M' + t.pool.medium + ' E' + t.pool.easy)
                                : 'none in pool';
                        }
                    });
                    qbUpdateTypeTotal();
                }
                var warn = document.getElementById('qb_mix_warnings');
                if (warn) warn.textContent = (res.warnings || []).join(' ');
            });
        }

        ['hard_level', 'medium_level', 'easy_level'].forEach(function (id) {
            var key = id.replace('_level', '');
            var el = document.getElementById(id);
            if (!el) return;
            el.addEventListener('input', function () { qbLinkDifficulty(key); });
            el.addEventListener('change', function () { qbLinkDifficulty(key); });
        });
        var noQ = document.getElementById('no_of_question');
        if (noQ) {
            noQ.addEventListener('input', function () {
                qbUpdateMixUi();
                qbSchedulePreview();
            });
        }
        document.querySelectorAll('input.question_type_list').forEach(function (input) {
            input.addEventListener('input', function () {
                qbTypesLocked = true;
                qbUpdateTypeTotal();
            });
        });
        var resetBtn = document.getElementById('qbResetTypes');
        if (resetBtn) {
            resetBtn.addEventListener('click', function () {
                qbTypesLocked = false;
                qbFetchPreview();
            });
        }
        document.addEventListener('input', function (e) {
            if (e.target && e.target.classList.contains('total_no_of_question')) {
                qbSchedulePreview();
            }
        });
        function changeTotalMarkForExam(){
            var no_of_question = document.getElementById('no_of_question').value;
            var marks_per_question = document.getElementById('marks_per_question').value;
            if(no_of_question && marks_per_question){
                document.getElementById('totals_marks_for_exam').value = no_of_question * marks_per_question;
            }else{
                document.getElementById('totals_marks_for_exam').value = "";
            }
        }

        function changeTotalTimeForExam(){
            var no_of_question = document.getElementById('no_of_question').value;
            var time_per_question = document.getElementById('time_per_question').value;
            if(no_of_question && time_per_question){
                document.getElementById('total_time_for_exam').value = no_of_question * time_per_question;
            }else{
                document.getElementById('total_time_for_exam').value = "";
            }
        }

        function changeNegativeMarkingApplicable(type){
            if(type){
                document.getElementById('negative_marking_per_question_div').style.display = "block";
            }else{
                document.getElementById('negative_marking_per_question_div').style.display = "none";
            }
        }

        function addNewSubject(){
            subject_count = subject_count + 1;
            var iHtml = `<div class="row mt-2" id="subject_view_`+subject_count+`">
                    <div class="col-sm-2">
                    <label for="subject_id">Subject</label>
                    <select class="form-control subject_id" id="subject_id_`+subject_count+`" name="subject_id[`+subject_count+`]" onchange="changeSubject(`+subject_count+`);">
                    <option value="">Select Subject</option>`;
            globalData.subject_list.forEach(function(val){
                iHtml = iHtml+`<option value="`+val.id+`">`+val.name+`</option>`;
            });
            iHtml = iHtml+`
                    </select>
                </div>
                <div class="col-sm-2">
                    <label class="form-label" for="chapter_id_`+subject_count+`">Chapter</label>
                    <select class="form-control chapter_id" id="chapter_id_`+subject_count+`" name="chapter_id[`+subject_count+`]" onchange="changeChapter(`+subject_count+`);">
                        <option value="">Select Chapter</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label class="form-label" for="topic_id_`+subject_count+`">Topic</label>
                    <select class="form-control topic_id" id="topic_id_`+subject_count+`" name="topic_id[`+subject_count+`]" onchange="changeTopic(`+subject_count+`);">
                        <option value="">Select Topic</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label class="form-label" for="sub_topic_id_`+subject_count+`">Sub Topic</label>
                    <select class="form-control sub_topic_id" id="sub_topic_id_`+subject_count+`" name="sub_topic_id[`+subject_count+`]">
                        <option value="">Select Sub Topic</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label for="total_no_of_question_`+subject_count+`">No Of questions per subject</label>
                    <input class="form-control total_no_of_question" id="total_no_of_question_`+subject_count+`" type="number" name="total_no_of_question[`+subject_count+`]" value="">
                </div>
                <div class="col-sm-1" style="margin-top: 25px;">
                    <img src="{{asset('')}}image/remove-box.png" alt="" onclick="removeSubject(`+subject_count+`);">
                </div>
                </div>`;
            $("#subject_list_view").append(iHtml);
        }

        function removeSubject(index) {
            document.getElementById("subject_view_"+index).remove();
        }

        function changeSubject(indd) {
            var data = {};
            data.subject_id = document.getElementById('subject_id_'+indd).value;

            $.get("{{route('admin.common.chapter')}}", data)
              .done(function( response ) {
                response = JSON.parse(response);
                
                var role_modal_body = `<option value="">Select Chapter</option>`;
                response.result.forEach(function(val){
                  role_modal_body = role_modal_body + `<option value="`+val.id+`">
                    `+val.name+`
                  </option>`
                });
                document.getElementById('chapter_id_'+indd).innerHTML = role_modal_body;
                qbSchedulePreview();
              });
            qbSchedulePreview();
        }

        function changeChapter(indd){

            var data = {};
            data.subject_id = document.getElementById('subject_id_'+indd).value;
            data.chapter_id = document.getElementById('chapter_id_'+indd).value;

            $.get("{{route('admin.common.topic')}}", data)
              .done(function( response ) {
                response = JSON.parse(response);
                
                var role_modal_body = `<option value="">Select Topic</option>`;
                response.result.forEach(function(val){
                  role_modal_body = role_modal_body + `<option value="`+val.id+`">
                    `+val.name+`
                  </option>`
                });
                document.getElementById('topic_id_'+indd).innerHTML = role_modal_body;
                qbSchedulePreview();
              });
            qbSchedulePreview();
        }

        function changeTopic(indd){

            var data = {};
            data.subject_id = document.getElementById('subject_id_'+indd).value;
            data.chapter_id = document.getElementById('chapter_id_'+indd).value;
            data.topic_id = document.getElementById('topic_id_'+indd).value;

            $.get("{{route('admin.common.sub_topic')}}", data)
              .done(function( response ) {
                response = JSON.parse(response);
                
                var role_modal_body = `<option value="">Select Sub Topic</option>`;
                response.result.forEach(function(val){
                  role_modal_body = role_modal_body + `<option value="`+val.id+`">
                    `+val.name+`
                  </option>`
                });
                document.getElementById('sub_topic_id_'+indd).innerHTML = role_modal_body;
                qbSchedulePreview();
              });
            qbSchedulePreview();
        }

        const allowedTypes = ["image/png", "image/jpeg", "image/jpg"];
        const maxSize1 = 1024 * 1024;

        function formValidation(){
            var data = {};
            var successFlag = true;
            var wanted = parseInt(document.getElementById('no_of_question').value || 0, 10) || 0;

            data.name = document.getElementById('name').value;
            if(data.name){
                document.getElementById('name').classList.remove('dangerBoader');
            }else{
                document.getElementById('name').classList.add('dangerBoader');
                successFlag = false;
            }
            
            data.no_of_question = document.getElementById('no_of_question').value;
            if(data.no_of_question){
                document.getElementById('no_of_question').classList.remove('dangerBoader');
            }else{
                document.getElementById('no_of_question').classList.add('dangerBoader');
                successFlag = false;
            }
            
            data.marks_per_question = document.getElementById('marks_per_question').value;
            if(data.marks_per_question){
                document.getElementById('marks_per_question').classList.remove('dangerBoader');
            }else{
                document.getElementById('marks_per_question').classList.add('dangerBoader');
                successFlag = false;
            }
            
            data.time_per_question = document.getElementById('time_per_question').value;
            if(data.time_per_question){
                document.getElementById('time_per_question').classList.remove('dangerBoader');
            }else{
                document.getElementById('time_per_question').classList.add('dangerBoader');
                successFlag = false;
            }

            var difficultyChecked = document.querySelector('input[name="difficulty"]:checked');
            var levelMsg = document.getElementById('level_message');
            if (levelMsg) levelMsg.textContent = '';
            if (!difficultyChecked) {
                if (levelMsg) {
                    levelMsg.textContent = 'Select Hard, Medium or Easy.';
                    levelMsg.className = 'qb-mix__sum is-bad';
                }
                successFlag = false;
            }
            
            var inputs = document.querySelectorAll('input.total_no_of_question');
            var sectionSum = 0;
            if (inputs.length === 1 && !inputs[0].value && wanted) {
                inputs[0].value = wanted;
            }
            inputs.forEach(input => {
                if(input.value){
                    input.classList.remove('dangerBoader');
                    sectionSum += parseInt(input.value, 10) || 0;
                }else{
                    input.classList.add('dangerBoader');
                    successFlag = false;
                }
            });
            if (wanted && sectionSum && sectionSum !== wanted) {
                document.getElementById('level_message').innerHTML = "Subject question counts (" + sectionSum + ") must equal total (" + wanted + ").";
                document.getElementById('level_message').className = 'qb-mix__sum is-bad';
                successFlag = false;
            }
            
            var subject_id = document.querySelectorAll('select.subject_id');
            console.log(subject_id);
            subject_id.forEach(input => {
                console.log(input.value);
                if(input.value){
                    input.classList.remove('dangerBoader');
                }else{
                    input.classList.add('dangerBoader');
                    successFlag = false;
                }
            });
            
            return successFlag;
        }
    </script>
@endsection