<?php

namespace Tests\Unit;

use App\Support\Html;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class HtmlTest extends TestCase
{
    #[DataProvider('dangerous')]
    public function test_removes_anything_that_can_run_code(string $input, string $expected): void
    {
        $this->assertSame($expected, Html::clean($input));
    }

    public static function dangerous(): array
    {
        return [
            'script tag'          => ['<p>Hi</p><script>alert(1)</script>', '<p>Hi</p>'],
            'event handler'       => ['<p onclick="steal()">Hi</p>', '<p>Hi</p>'],
            'image onerror'       => ['<img src=x onerror=alert(1)>text', 'text'],
            'javascript link'     => ['<a href="javascript:alert(1)">x</a>', '<a>x</a>'],
            'data link'           => ['<a href="data:text/html;base64,PHNjcmlwdD4=">x</a>', '<a>x</a>'],
            'iframe and form'     => ['<iframe src="//evil"></iframe><form action="/x"><input></form>ok', 'ok'],
            'svg handler'         => ['<svg onload=alert(1)></svg>after', 'after'],
            'unsafe css'          => ['<div style="position:fixed">x</div>', '<div>x</div>'],
        ];
    }

    public function test_keeps_the_formatting_editors_use(): void
    {
        $html = '<p>Hello <b>bold</b> <i>it</i> <u>u</u> <a href="https://example.com/a">link</a></p><ul><li>one</li></ul><h2>Title</h2>';

        $this->assertSame($html, Html::clean($html));
        $this->assertSame('<span style="background-color:rgb(255,255,0);">hi</span>', Html::clean('<span style="background-color: rgb(255,255,0)">hi</span>'));
    }

    public function test_leaves_plain_text_and_empty_values_alone(): void
    {
        $this->assertSame('just words', Html::clean('just words'));
        $this->assertNull(Html::clean(null));
        $this->assertSame('', Html::clean(''));
    }
}
