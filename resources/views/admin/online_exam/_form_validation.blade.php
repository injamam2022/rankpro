<script type="text/javascript">
    const allowedTypes = ["image/png", "image/jpeg", "image/jpg"];
    const maxSize1 = 1024 * 1024;

    function markRequired(id, ok) {
        var el = document.getElementById(id);
        if (!el) {
            return ok;
        }
        if (ok) {
            el.classList.remove('dangerBoader');
        } else {
            el.classList.add('dangerBoader');
        }
        return ok;
    }

    function optionalImageOk(id) {
        var el = document.getElementById(id);
        if (!el || !el.files || !el.files[0]) {
            if (el) el.classList.remove('dangerBoader');
            return true;
        }
        var file = el.files[0];
        var ok = allowedTypes.includes(file.type) && file.size <= maxSize1;
        return markRequired(id, ok);
    }

    function formValidation(){
        var successFlag = true;
        var examCode = (document.getElementById('exam_code') || {}).value || '';
        var name = (document.getElementById('name') || {}).value || '';
        var examDate = (document.getElementById('exam_date') || {}).value || '';
        var examTime = (document.getElementById('exam_time') || {}).value || '';
        var examEndDate = (document.getElementById('exam_end_date') || {}).value || '';
        var examEndTime = (document.getElementById('exam_end_time') || {}).value || '';
        var price = (document.getElementById('price') || {}).value || '';
        var questionPaper = (document.getElementById('question_paper_id') || {}).value || '';
        var batch = document.getElementById('batch_id');
        var batchValue = '';
        if (batch) {
            if (batch.multiple) {
                batchValue = Array.prototype.filter.call(batch.options, function (opt) { return opt.selected && opt.value; }).length ? '1' : '';
            } else {
                batchValue = batch.value;
            }
        }

        successFlag = markRequired('exam_code', !!examCode.trim()) && successFlag;
        successFlag = markRequired('name', !!name.trim()) && successFlag;
        successFlag = optionalImageOk('exam_logo') && successFlag;
        successFlag = optionalImageOk('landing_icon') && successFlag;
        successFlag = markRequired('exam_date', !!examDate) && successFlag;
        successFlag = markRequired('exam_time', !!examTime) && successFlag;
        successFlag = markRequired('price', !!price.trim()) && successFlag;
        successFlag = markRequired('batch_id', !!batchValue) && successFlag;
        successFlag = markRequired('question_paper_id', !!questionPaper) && successFlag;

        if (examEndDate || examEndTime) {
            var endOk = !!(examEndDate && examEndTime);
            successFlag = markRequired('exam_end_date', !!examEndDate) && successFlag;
            successFlag = markRequired('exam_end_time', !!examEndTime) && successFlag;
            if (endOk && examDate && examTime) {
                var startAt = new Date(examDate + 'T' + examTime);
                var endAt = new Date(examEndDate + 'T' + examEndTime);
                if (!(endAt > startAt)) {
                    markRequired('exam_end_date', false);
                    markRequired('exam_end_time', false);
                    successFlag = false;
                }
            }
        } else {
            markRequired('exam_end_date', true);
            markRequired('exam_end_time', true);
        }

        return successFlag;
    }
</script>
