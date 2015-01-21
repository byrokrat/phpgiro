<?php

namespace ledgr\giro;

use DOMDocument;

class DtdValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testNoRootNode()
    {
        $validator = new DtdValidator('rootNodeName', '');
        $domDocument = new DOMDocument;
        $domDocument->loadXML('<differentNodeName></differentNodeName>');
        $this->assertFalse($validator->isValid($domDocument));
        $this->assertTrue($validator->getError() != '');
    }

    public function testValidate()
    {
        $validator = new DtdValidator('foo', '<!ELEMENT foo (#PCDATA)>');
        $domDocument = new DOMDocument;
        $domDocument->loadXML('<foo></foo>');
        $this->assertTrue($validator->isValid($domDocument));
    }

    public function testInvalidXML()
    {
        $validator = new DtdValidator('foo', '<!ELEMENT foo (bar)><!ELEMENT bar (#PCDATA)>');
        $domDocument = new DOMDocument;
        $domDocument->loadXML('<foo></foo>');
        $this->assertFalse($validator->isValid($domDocument));
        $this->assertTrue($validator->getError() != '');
    }

    public function testEmptyError()
    {
        $validator = new DtdValidator('foo', '');
        $this->assertTrue($validator->getError() == '');
    }
}
