XsdBuilder
==========

Installation
------------

You can easily install this package over composer

```shell script
composer require 'davidbadura/xsd-builder'
```

Example
-------

### PHP Code

```php
<?php

use DavidBadura\XsdBuilder\Attribute;
use DavidBadura\XsdBuilder\Builder;
use DavidBadura\XsdBuilder\ComplexType;
use DavidBadura\XsdBuilder\Element;
use DavidBadura\XsdBuilder\Key;
use DavidBadura\XsdBuilder\KeyRef;

/* Library */

$builder = new Builder();
$library = new ComplexType();
$builder->addElement(Element::complexType('library', $library));

/* Books */

$books = new ComplexType();
$booksEl = Element::complexType('books', $books);
$booksEl->setKey(Key::create('book-id', 'book', ['@identifier']));
$booksEl->addKeyRef(KeyRef::create('author-ref', 'book-id', 'book', 'author'));
$library->addElement($booksEl);

/* Book */

$book = new ComplexType();
$book->addElement(Element::string('isbn'));
$book->addElement(Element::string('title'));
$book->addElement(Element::string('author'));

$id = Attribute::string('identifier');
$id->setUse('required');
$book->addAttribute($id);

$bookEl = Element::complexType('book', $book);
$bookEl->setMinOccurs(0);
$bookEl->setUnbounded(true);

$books->addElement($bookEl);

/* Authors */

$authors = new ComplexType();
$authorsEl = Element::complexType('authors', $authors);
$authorsEl->setKey(Key::create('author-id', 'author', ['@identifier']));
$library->addElement($authorsEl);

/* Author */

$author = new ComplexType();
$author->addElement(Element::string('name'));
$author->addAttribute(Attribute::string('identifier'));

$authorEl = Element::complexType('author', $author);
$authorEl->setMinOccurs(0);
$authorEl->setUnbounded(true);

$authors->addElement($authorEl);

echo $builder->toString();
```

### Result

```xml
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">
  <xs:element name="library">
    <xs:complexType>
      <xs:sequence>
        <xs:element name="books">
          <xs:complexType>
            <xs:sequence>
              <xs:element name="book" maxOccurs="unbounded" minOccurs="0">
                <xs:complexType>
                  <xs:sequence>
                    <xs:element name="isbn" type="xs:string"/>
                    <xs:element name="title" type="xs:string"/>
                    <xs:element name="author" type="xs:string"/>
                  </xs:sequence>
                  <xs:attribute name="identifier" type="xs:string" use="required"/>
                </xs:complexType>
              </xs:element>
            </xs:sequence>
          </xs:complexType>
          <xs:key name="book-id">
            <xs:selector xpath="book"/>
            <xs:field xpath="@identifier"/>
          </xs:key>
          <xs:keyref name="author-ref" refer="book-id">
            <xs:selector xpath="book"/>
            <xs:field xpath="author"/>
          </xs:keyref>
        </xs:element>
        <xs:element name="authors">
          <xs:complexType>
            <xs:sequence>
              <xs:element name="author" maxOccurs="unbounded" minOccurs="0">
                <xs:complexType>
                  <xs:sequence>
                    <xs:element name="name" type="xs:string"/>
                  </xs:sequence>
                  <xs:attribute name="identifier" type="xs:string"/>
                </xs:complexType>
              </xs:element>
            </xs:sequence>
          </xs:complexType>
          <xs:key name="author-id">
            <xs:selector xpath="author"/>
            <xs:field xpath="@identifier"/>
          </xs:key>
        </xs:element>
      </xs:sequence>
    </xs:complexType>
  </xs:element>
</xs:schema>
```
