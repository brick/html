<?php

namespace Brick\Html;

/**
 * An HTML5 tag.
 *
 * It is assumed that all texts are UTF-8 encoded.
 * Tag names and attribute names will be converted to lowercase.
 */
class Tag
{
    /**
     * The list of void elements, that cannot have any contents.
     *
     * @see https://www.w3.org/TR/html51/syntax.html#void-elements
     */
    private const VOID_ELEMENTS = [
        'area',
        'base',
        'br',
        'col',
        'embed',
        'hr',
        'img',
        'input',
        'keygen',
        'link',
        'menuitem',
        'meta',
        'param',
        'source',
        'track',
        'wbr'
    ];

    /**
     * The tag name.
     *
     * @var string
     */
    private $name;

    /**
     * Whether the tag name matches a void element.
     *
     * @var bool
     */
    private $isVoid;

    /**
     * The tag attributes, as an associative array.
     *
     * @var array
     */
    private $attributes = [];

    /**
     * The HTML content.
     *
     * @var string
     */
    private $content = '';

    /**
     * Tag constructor.
     *
     * @param string $name The tag name.
     */
    public function __construct(string $name)
    {
        $name = strtolower($name);

        $this->name   = $name;
        $this->isVoid = in_array($name, self::VOID_ELEMENTS, true);
    }

    /**
     * Returns the tag name.
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Returns the tag attributes, as an associative array.
     *
     * @return array
     */
    public function getAttributes() : array
    {
        return $this->attributes;
    }

    /**
     * Returns whether the tag has an attribute with the given name.
     *
     * @param string $name The attribute name.
     *
     * @return bool
     */
    public function hasAttribute(string $name) : bool
    {
        return isset($this->attributes[strtolower($name)]);
    }

    /**
     * Returns the value of the attribute with the given name, if any.
     *
     * @param string $name The attribute name.
     *
     * @return string|null The value, or null if there is no such attribute.
     */
    public function getAttribute(string $name) : ?string
    {
        return $this->attributes[strtolower($name)] ?? null;
    }

    /**
     * Sets an attribute.
     *
     * @param string $name  The attribute name.
     * @param string $value The attribute value.
     *
     * @return Tag This instance, for chaining.
     */
    public function setAttribute(string $name, string $value) : Tag
    {
        $this->attributes[strtolower($name)] = $value;

        return $this;
    }

    /**
     * Sets several attributes.
     *
     * @param array $attributes The attributes, as an associative array of names to values.
     *
     * @return Tag This instance, for chaining.
     */
    public function setAttributes(array $attributes) : Tag
    {
        foreach ($attributes as $name => $value) {
            $this->attributes[strtolower($name)] = (string) $value;
        }

        return $this;
    }

    /**
     * Removes an attribute.
     *
     * If the attribute does not exist, this method does nothing.
     *
     * @param string $name The attribute name.
     *
     * @return Tag This instance, for chaining.
     */
    public function removeAttribute(string $name) : Tag
    {
        unset($this->attributes[strtolower($name)]);

        return $this;
    }

    /**
     * Empties the content of this tag.
     *
     * @return Tag This instance, for chaining.
     *
     * @throws \LogicException If this tag is a void element.
     */
    public function empty() : Tag
    {
        if ($this->isVoid) {
            throw new \LogicException('Void elements cannot have any contents.');
        }

        $this->content = '';

        return $this;
    }

    /**
     * Sets the text content of this tag.
     *
     * @param string $content The text content.
     *
     * @return Tag This instance, for chaining.
     *
     * @throws \LogicException If this tag is a void element.
     */
    public function setTextContent(string $content) : Tag
    {
        if ($this->isVoid) {
            throw new \LogicException('Void elements cannot have any contents.');
        }

        $this->content = htmlspecialchars($content, ENT_NOQUOTES | ENT_HTML5);

        return $this;
    }

    /**
     * Sets the HTML content of this tag.
     *
     * @param string $content The HTML content.
     *
     * @return Tag This instance, for chaining.
     *
     * @throws \LogicException If this tag is a void element.
     */
    public function setHtmlContent(string $content) : Tag
    {
        if ($this->isVoid) {
            throw new \LogicException('Void elements cannot have any contents.');
        }

        $this->content = $content;

        return $this;
    }

    /**
     * Appends text content to this tag.
     *
     * @param string $content The text content.
     *
     * @return Tag This instance, for chaining.
     *
     * @throws \LogicException If this tag is a void element.
     */
    public function appendTextContent(string $content) : Tag
    {
        if ($this->isVoid) {
            throw new \LogicException('Void elements cannot have any contents.');
        }

        $this->content .= htmlspecialchars($content, ENT_NOQUOTES | ENT_HTML5);

        return $this;
    }

    /**
     * Appends HTML content to this tag.
     *
     * @param string $content The HTML content.
     *
     * @return Tag This instance, for chaining.
     *
     * @throws \LogicException If this tag is a void element.
     */
    public function appendHtmlContent(string $content) : Tag
    {
        if ($this->isVoid) {
            throw new \LogicException('Void elements cannot have any contents.');
        }

        $this->content .= $content;

        return $this;
    }

    /**
     * Appends a tag to the contents of this tag.
     *
     * @param Tag $tag The tag to append.
     *
     * @return Tag This instance, for chaining.
     *
     * @throws \LogicException If this tag is a void element.
     */
    public function append(Tag $tag) : Tag
    {
        if ($this->isVoid) {
            throw new \LogicException('Void elements cannot have any contents.');
        }

        $this->content .= $tag->render();

        return $this;
    }

    /**
     * Returns whether this tag has no content.
     *
     * @return bool True if empty, false if this tag has any content.
     */
    public function isEmpty() : bool
    {
        return $this->content === '';
    }

    /**
     * Renders the opening tag.
     *
     * @return string
     */
    public function renderOpeningTag() : string
    {
        $result = '<' . $this->name;

        foreach ($this->attributes as $name => $value) {
            $result .= sprintf(' %s="%s"', $name, htmlspecialchars($value, ENT_COMPAT | ENT_HTML5));
        }

        $result .= '>';

        return $result;
    }

    /**
     * Renders the closing tag.
     *
     * @return string
     *
     * @throws \LogicException If this tag is a void element.
     */
    public function renderClosingTag() : string
    {
        if ($this->isVoid) {
            throw new \LogicException('Void elements do not have a closing tag.');
        }

        return sprintf('</%s>', $this->name);
    }

    /**
     * Renders the tag.
     *
     * @return string
     */
    public function render() : string
    {
        if ($this->isVoid) {
            return $this->renderOpeningTag();
        }

        return $this->renderOpeningTag() . $this->content . $this->renderClosingTag();
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->render();
    }
}
