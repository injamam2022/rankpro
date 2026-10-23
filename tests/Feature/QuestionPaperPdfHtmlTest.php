<?php

namespace Tests\Feature;

use App\Services\QuestionPaperPdfService;
use Tests\TestCase;

class QuestionPaperPdfHtmlTest extends TestCase
{
    private function service()
    {
        return new class extends QuestionPaperPdfService {
            public function run(string $html): string
            {
                return $this->prepareHtml($html);
            }
        };
    }

    public function test_prepare_html_resolves_local_images_and_strips_padding()
    {
        $dir = public_path('uploads/question');
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $name = '_pdf_test_img.png';
        $path = $dir.DIRECTORY_SEPARATOR.$name;
        file_put_contents($path, str_repeat('P', 64));

        try {
            $out = $this->service()->run('<p style="margin:18px;padding:12px">Q <img src="/uploads/question/'.$name.'" width="400"></p>');
            $this->assertStringContainsString(str_replace('\\', '/', public_path('uploads/question/'.$name)), $out);
            $this->assertStringNotContainsString('margin:18px', $out);
            $this->assertStringNotContainsString('padding:12px', $out);
            $this->assertStringContainsString('max-width:100%', $out);
        } finally {
            @unlink($path);
        }
    }

    public function test_prepare_html_keeps_formula_images_inline()
    {
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAA0AAAATCAMAAAB86XelAAAAAXNSR0IArs4c6QAAAFpQTFRFAAAAAAAAAABmADpmADqQAGa2OgAAOjqQOmaQOpDbZgAAZgA6Zrb/kDoAkDpmkGYAkGaQkLb/kNv/tmY6tv//25A625Bm27Zm2////7Zm/9uQ/9u2//+2///bz1f2PwAAAAF0Uk5TAEDm2GYAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAAZdEVYdFNvZnR3YXJlAE1pY3Jvc29mdCBPZmZpY2V/7TVxAAAAXElEQVQoU6WMRxKAIAxFiQUbiF2Kuf81DWZQt45vkZmfX4T4zC6htqnlKxta9drAfiHlCk5s0ULTsBjpeAkA5eU4Mo9uSk9SLovlxEqxG9Q8wqBWYXiyM+Rx5hcnBOMDjAjVv0IAAAAASUVORK5CYII=');
        $html = '<p>K= 4<img src="data:image/png;base64,'.base64_encode($png).'" width="13" height="19">. The force</p>';
        $out = $this->service()->run($html);

        $this->assertStringContainsString('display:inline', $out);
        $this->assertStringContainsString('vertical-align:middle', $out);
        $this->assertStringContainsString('width="13"', $out);
        $this->assertStringNotContainsString('data:image', $out);
        $this->assertStringNotContainsString('max-width:100%', $out);
    }

    public function test_prepare_html_collapses_nbsp_padding()
    {
        $out = $this->service()->run('<p>9.00&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>');

        $this->assertStringContainsString('9.00', $out);
        $this->assertStringNotContainsString('&nbsp;&nbsp;', $out);
    }

    public function test_prepare_html_flattens_mathml_to_text()
    {
        $html = '<p>For <math xmlns="http://www.w3.org/1998/Math/MathML"><mn>298</mn><mi>K</mi></math>, the value of is</p>';
        $out = $this->service()->run($html);

        $this->assertStringContainsString('298', $out);
        $this->assertStringContainsString('K', $out);
        $this->assertStringNotContainsString('<math', $out);
    }
}
