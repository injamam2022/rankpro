@php
    $showSubject = $show_subject ?? true;
    $showChapter = $show_chapter ?? false;
    $showTopic = $show_topic ?? false;
    $subjectId = $subject_id ?? request('subject_id');
    $chapterId = $chapter_id ?? request('chapter_id');
    $topicId = $topic_id ?? request('topic_id');
    $subjects = $subject_list ?? collect();
    $chapters = $chapter_list ?? collect();
    $topics = $topic_list ?? collect();
    $chapterLabel = function ($row) {
        $name = $row->name ?? '';
        $level = (int) ($row->class_level ?? 0);
        if ($level === 11) {
            return $name.' (Class 11)';
        }
        if ($level === 12) {
            return $name.' (Class 12)';
        }
        return $name;
    };
@endphp
@if($showSubject)
    <div class="admin-list-field">
        <label for="subject_id">Subject</label>
        <select class="form-control" id="subject_id" name="subject_id"
                onchange="rpTaxonomySubmit(this.form, ['chapter_id','topic_id'])">
            <option value="">All subjects</option>
            @foreach($subjects as $value)
                <option value="{{ $value->id }}" @selected((string) $value->id === (string) $subjectId)>{{ $value->name }}</option>
            @endforeach
        </select>
    </div>
@endif
@if($showChapter)
    <div class="admin-list-field">
        <label for="chapter_id">Chapter</label>
        <select class="form-control" id="chapter_id" name="chapter_id"
                onchange="rpTaxonomySubmit(this.form, ['topic_id'])">
            <option value="">All chapters</option>
            @foreach($chapters as $value)
                <option value="{{ $value->id }}" @selected((string) $value->id === (string) $chapterId)>{{ $chapterLabel($value) }}</option>
            @endforeach
        </select>
    </div>
@endif
@if($showTopic)
    <div class="admin-list-field">
        <label for="topic_id">Topic</label>
        <select class="form-control" id="topic_id" name="topic_id"
                onchange="this.form.submit()">
            <option value="">All topics</option>
            @foreach($topics as $value)
                <option value="{{ $value->id }}" @selected((string) $value->id === (string) $topicId)>{{ $value->name }}</option>
            @endforeach
        </select>
    </div>
@endif
