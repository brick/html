<?php

namespace Brick\Html\Tests;

use Brick\Html\Tag;

use LogicException;
use PHPUnit\Framework\TestCase;

class TagTest extends TestCase
{
    public function testTag()
    {
        $tag = new Tag('P');

        $this->assertSame('p', $tag->getName());
        $this->assertSame('<p></p>', $tag->render());

        $tag->setAttribute('Class', 'foo');
        $this->assertSame(['class' => 'foo'], $tag->getAttributes());
        $this->assertSame('<p class="foo"></p>', $tag->render());

        $tag->setTextContent('"Hello" Ol\' <World> & others');
        $this->assertSame('<p class="foo">"Hello" Ol\' &lt;World&gt; &amp; others</p>', $tag->render());

        $tag->setHtmlContent('Hello<br class="foo">World');
        $this->assertSame('<p class="foo">Hello<br class="foo">World</p>', $tag->render());

        $tag->empty();
        $this->assertSame('<p class="foo"></p>', $tag->render());

        $tag->append(new Tag('br'));
        $this->assertSame('<p class="foo"><br></p>', $tag->render());
    }

    public function testVoidTag()
    {
        $tag = new Tag('IMG', ['SRC' => 'TEST.PNG']);
        $this->assertSame('<img src="TEST.PNG">', $tag->render());

        $tag->setAttributes([
            'ID' => 123,
            'Src' => 'IMAGE.PNG',
            'Data-Chars' => '"\'<>'
        ]);
        $this->assertSame('<img src="IMAGE.PNG" id="123" data-chars="&quot;\'&lt;&gt;">', $tag->render());
    }

    public function testHasAttribute()
    {
        $tag = new Tag('p');
        $tag->setAttributes([
            'class' => 'class_name'
        ]);

        $this->assertTrue($tag->hasAttribute('class'));
        $this->assertFalse($tag->hasAttribute('invalid_attribute'));
    }

    public function testGetAttribute()
    {
        $tag = new Tag('p');
        $tag->setAttributes([
            'class' => 'class_name'
        ]);

        $this->assertSame('class_name', $tag->getAttribute('class'));
        $this->assertNull($tag->getAttribute('invalid_attribute'));
    }

    public function testRemoveAttribute()
    {
        $tag = new Tag('p');
        $tag->setAttributes([
            'class' => 'class_name'
        ]);

        $this->assertSame($tag, $tag->removeAttribute('class'));
        $this->assertNull($tag->getAttribute('class'));
    }

    public function testEmptyOnVoidElement()
    {
        $tag = new Tag('img');

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Void elements cannot have any contents.');

        $tag->empty();
    }

    public function testSetTextContentOnVoidElement()
    {
        $tag = new Tag('img');

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Void elements cannot have any contents.');

        $tag->setTextContent('text_content');
    }

    public function testSetHtmlContentOnVoidElement()
    {
        $tag = new Tag('img');

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Void elements cannot have any contents.');

        $tag->setHtmlContent('<p>html_content</p>');
    }

    public function testAppendTextContentOnVoidElement()
    {
        $tag = new Tag('img');

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Void elements cannot have any contents.');

        $tag->appendTextContent('this_is_appended_text_content');
    }

    public function testAppendTextContent()
    {
        $tag = new Tag('p');
        $appendTextContent = 'this_is_appended_text_content';

        $this->assertSame($tag, $tag->appendTextContent($appendTextContent));
        $this->assertSame('<p>' . $appendTextContent . '</p>', $tag->render());
    }

    public function testAppendHtmlContentOnVoidElement()
    {
        $tag = new Tag('img');

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Void elements cannot have any contents.');

        $tag->appendHtmlContent('<span>this_is_appended_html_content</span>');
    }

    public function testAppendHtmlContent()
    {
        $tag = new Tag('p');
        $appendHtmlContent = '<span>this_is_appended_text_content</span>';

        $this->assertSame($tag, $tag->appendHtmlContent($appendHtmlContent));
        $this->assertSame('<p>' . $appendHtmlContent . '</p>', $tag->render());
    }

    public function testAppendOnVoidElement()
    {
        $tag = new Tag('img');

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Void elements cannot have any contents.');

        $tag->append(new Tag('img'));
    }

    public function testIsEmptyShouldReturnTrue()
    {
        $tag = new Tag('p');
        $tag->appendTextContent('append_text_content');

        $this->assertSame($tag, $tag->empty());
        $this->assertTrue($tag->isEmpty());
    }

    public function testIsEmptyShouldReturnFalse()
    {
        $tag = new Tag('p');
        $tag->appendTextContent('append_text_content');

        $this->assertFalse($tag->isEmpty());
    }

    public function testRenderClosingTagOnVoidElement()
    {
        $tag = new Tag('img');

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Void elements do not have a closing tag.');

        $tag->renderClosingTag();
    }

    public function testTagInstanceShouldReturnRenderedString()
    {
        $tag = new Tag('p');
        $appendTextContent = 'append_text_content';
        $tag->appendTextContent($appendTextContent);

        $this->assertSame('<p>' . $appendTextContent . '</p>', (string) $tag);
    }
}
