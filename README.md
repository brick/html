# Brick\Html

<img src="https://raw.githubusercontent.com/brick/brick/master/logo.png" alt="" align="left" height="64">

A very simple HTML 5 generation library.

[![Build Status](https://secure.travis-ci.org/brick/html.svg?branch=master)](http://travis-ci.org/brick/html)
[![Coverage Status](https://coveralls.io/repos/github/brick/html/badge.svg?branch=master)](https://coveralls.io/github/brick/html?branch=master)
[![Latest Stable Version](https://poser.pugx.org/brick/html/v/stable)](https://packagist.org/packages/brick/html)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](http://opensource.org/licenses/MIT)

## Installation

This library is installable via [Composer](https://getcomposer.org/):

```bash
composer require brick/html
```

## Requirements

This library requires PHP 7.1 or later.

## Project status & release process

This library is still under development.

The current releases are numbered `0.x.y`. When a non-breaking change is introduced (adding new methods, optimizing existing code, etc.), `y` is incremented.

**When a breaking change is introduced, a new `0.x` version cycle is always started.**

It is therefore safe to lock your project to a given release cycle, such as `0.1.*`.

If you need to upgrade to a newer release cycle, check the [release history](https://github.com/brick/html/releases) for a list of changes introduced by each further `0.x.0` version.

## Introduction

This library contains a single class, `Tag`, that represents an HTML tag. You construct a `Tag` using a tag name:

```php
use Brick\Html\Tag;

$div = new Tag('div');
```

### Attributes

You can pass an optional associative array of attributes to the constructor:

```php
$div = new Tag('div', [
    'id' => 'main',
    'class' => 'block',
]);
```

Or you can set attributes later:

```php
$tag->setAttributes([
    'id' => 'main',
    'class' => 'block',
]);
```

Or:

```php
$tag->setAttribute('id', 'main')
    ->setAttribute('class', 'block');
```

You can also remove attributes:

```php
$tag->removeAttribute('id');
```

### Content

You can set the content of a `Tag`, provided that it's not a *void* tag such as `<br>`, `<input>`, etc.

You can set or append a plain text content:

```php
$tag->setTextContent('Hello, world!');
$tag->appendTextContent("\nWhat's up?");
```

Or set/append a HTML content:

```php
$tag->setHtmlContent('Hello, <b>world!</b>');
$tag->appendHtmlContent("<br>What's up?");
```

You can also append the content of another `Tag`:

```php
$tag->append($otherTag);
```

You can remove the content of a `Tag`, and check if the `Tag` has an empty content:

```php
$tag->empty();
$tag->isEmpty(); // true
```

If you try to modify the content of a void tag, you'll get a `LogicException`.

### Rendering a tag

You can render a tag by using its `render()` method, or just casting it to string:

```php
echo $tag; // will output something like <div id="main">Hello, world!</div>
```

### Encoding

All texts (attributes, content) are expected to be valid UTF-8.
