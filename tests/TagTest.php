<?php

namespace Brick\Html\Tests;

use Brick\Html\Tag;

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

        $this->assertInstanceOf(Tag::class, $tag->removeAttribute('class'));
        $this->assertNull($tag->getAttribute('class'));
    }

    /**
     * @expectedException        \LogicException
     * @expectedExceptionMessage Void elements cannot have any contents.
     */
    public function testEmptyWithVoidElementShouldReturnLogicException()
    {
        $tag = new Tag('img');
        $tag->empty();
    }

    /**
     * @expectedException        \LogicException
     * @expectedExceptionMessage Void elements cannot have any contents.
     */
    public function testSetTextContentWithVoidElementShouldReturnLogicException()
    {
        $tag = new Tag('img');
        $tag->setTextContent('text_content');
    }

    /**
     * @expectedException        \LogicException
     * @expectedExceptionMessage Void elements cannot have any contents.
     */
    public function testSetHtmlContentWithVoidElementShouldReturnLogicException()
    {
        $tag = new Tag('img');
        $tag->setHtmlContent('<p>html_content</p>');
    }

    /**
     * @expectedException        \LogicException
     * @expectedExceptionMessage Void elements cannot have any contents.
     */
    public function testAppendTextContentWithVoidElementShouldReturnLogicException()
    {
        $tag = new Tag('img');
        $tag->appendTextContent('this_is_appended_text_content');
    }

    public function testAppendTextContent()
    {
        $tag = new Tag('p');
        $appendTextContent = 'this_is_appended_text_content';
        $tag->appendTextContent($appendTextContent);

        $this->assertSame('<p>' . $appendTextContent . '</p>', $tag->render());
    }

    /**
     * @expectedException        \LogicException
     * @expectedExceptionMessage Void elements cannot have any contents.
     */
    public function testAppendHtmlContentWithVoidElementShouldReturnLogicException()
    {
        $tag = new Tag('img');
        $tag->appendHtmlContent('<span>this_is_appended_html_content</span>');
    }

    public function testAppendHtmlContent()
    {
        $tag = new Tag('p');
        $appendHtmlContent = '<span>this_is_appended_text_content</span>';
        $tag->appendHtmlContent($appendHtmlContent);

        $this->assertSame('<p>' . $appendHtmlContent . '</p>', $tag->render());
    }

    /**
     * @expectedException        \LogicException
     * @expectedExceptionMessage Void elements cannot have any contents.
     */
    public function testAppendWithVoidElementShouldReturnLogicException()
    {
        $tag = new Tag('img');
        $tag->append(new Tag('img'));
    }

    public function testIsEmptyShouldReturnTrue()
    {
        $tag = new Tag('p');
        $tag->appendTextContent('append_text_content');
        $tag->empty();

        $this->assertTrue($tag->isEmpty());
    }

    public function testIsEmptyShouldReturnFale()
    {
        $tag = new Tag('p');
        $tag->appendTextContent('append_text_content');

        $this->assertFalse($tag->isEmpty());
    }

    /**
     * @expectedException        \LogicException
     * @expectedExceptionMessage Void elements do not have a closing tag.
     */
    public function testRenderClosingTag()
    {
        $tag = new Tag('img');
        $tag->renderClosingTag();
    }

    public function testTagInstanceShouldReturnRenderedString()
    {
        $tag = new Tag('p');
        $appendTextContent = 'append_text_content';
        $tag->appendTextContent($appendTextContent);
        $tag->render();

        $this->assertSame('<p>' . $appendTextContent . '</p>', (string)$tag);
    }
}
