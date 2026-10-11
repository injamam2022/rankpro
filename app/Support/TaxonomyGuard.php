<?php

namespace App\Support;

use App\Models\Question;
use App\Models\Question_paper_subject;
use App\Models\Sub_topic;
use App\Models\Topic;

class TaxonomyGuard
{
    public static function subjectInUse(int $id): int
    {
        return Question::where('subject_id', $id)->where('is_deleted', 0)->count()
            + Question_paper_subject::where('subject_id', $id)->count();
    }

    public static function chapterInUse(int $id): int
    {
        return Question::where('chapter_id', $id)->where('is_deleted', 0)->count()
            + Topic::where('chapter_id', $id)->count()
            + Question_paper_subject::where('chapter_id', $id)->count();
    }

    public static function topicInUse(int $id): int
    {
        return Question::where('topic_id', $id)->where('is_deleted', 0)->count()
            + Sub_topic::where('topic_id', $id)->count()
            + Question_paper_subject::where('topic_id', $id)->count();
    }

    public static function subTopicInUse(int $id): int
    {
        return Question::where('sub_topic_id', $id)->where('is_deleted', 0)->count()
            + Question_paper_subject::where('sub_topic_id', $id)->count();
    }
}
