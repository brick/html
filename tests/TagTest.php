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
        $tag = new Tag('IMG');
        $this->assertSame('<img>', $tag->render());

        $tag->setAttributes([
            'ID' => 123,
            'Src' => 'IMAGE.PNG',
            'Data-Chars' => '"\'<>'
        ]);
        $this->assertSame('<img id="123" src="IMAGE.PNG" data-chars="&quot;\'&lt;&gt;">', $tag->render());
    }
}
