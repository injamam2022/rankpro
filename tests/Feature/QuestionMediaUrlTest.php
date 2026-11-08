<?php

namespace Tests\Feature;

use Tests\TestCase;

class QuestionMediaUrlTest extends TestCase
{
    public function test_missing_file_falls_back_to_production_url()
    {
        config(['app.question_media_fallback' => 'https://rankpro.co.in/public']);

        $url = question_media_url('6ac22ed9e8709_1791110873.png');

        $this->assertSame(
            'https://rankpro.co.in/public/uploads/question/6ac22ed9e8709_1791110873.png',
            $url
        );
    }

    public function test_existing_file_uses_local_asset()
    {
        $dir = public_path('uploads/question');
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $name = '_test_media_local.png';
        $path = $dir.'/'.$name;
        file_put_contents($path, 'x');

        try {
            $url = question_media_url($name);
            $this->assertStringContainsString('uploads/question/'.$name, $url);
            $this->assertStringNotContainsString('rankpro.co.in', $url);
        } finally {
            @unlink($path);
        }
    }

    public function test_html_rewrites_relative_img_src()
    {
        config(['app.question_media_fallback' => 'https://rankpro.co.in/public']);

        $html = '<p>Q <img src="missing.png" alt="x"></p>';
        $out = question_html_media($html);

        $this->assertStringContainsString(
            'https://rankpro.co.in/public/uploads/question/missing.png',
            $out
        );
    }

    public function test_data_uri_is_kept()
    {
        $data = 'data:image/png;base64,'.str_repeat('A', 3000);

        $this->assertSame($data, question_media_url($data));
        $this->assertStringContainsString($data, question_html_media('<img src="'.$data.'">'));
    }

    public function test_html_adds_dev_and_production_fallbacks()
    {
        config([
            'app.question_media_fallback' => 'https://rankpro.co.in/public',
            'app.question_media_fallbacks' => 'https://dev.rankpro.co.in/public,https://rankpro.co.in/public',
        ]);

        $out = question_html_media('<p>Q <img src="diagram.png" alt="x"></p>');

        $this->assertStringContainsString('https://rankpro.co.in/public/uploads/question/diagram.png', $out);
        $this->assertStringContainsString('https://dev.rankpro.co.in/public/uploads/question/diagram.png', $out);
        $this->assertStringContainsString('onerror=', $out);
    }

    public function test_img_tag_tries_dev_when_production_is_primary()
    {
        config([
            'app.question_media_fallback' => 'https://rankpro.co.in/public',
            'app.question_media_fallbacks' => 'https://dev.rankpro.co.in/public,https://rankpro.co.in/public',
        ]);

        $tag = question_img_tag('diagram.png', ['alt' => 'q']);

        $this->assertStringContainsString('src="https://rankpro.co.in/public/uploads/question/diagram.png"', $tag);
        $this->assertStringContainsString('data-qfb="', $tag);
        $this->assertStringContainsString('https://dev.rankpro.co.in/public/uploads/question/diagram.png', $tag);
    }
}
