<?php
namespace iio\swegiro;

class XMLWriterTest extends \PHPUnit_Framework_TestCase
{
    public function testCurrentElement()
    {
        $writer = new XMLWriter;
        $this->assertEquals(
            '',
            $writer->currentElement(),
            'No current element, should return empty string'
        );

        $writer->startElement('foo');
        $writer->startElement('bar');

        $this->assertEquals(
            'bar',
            $writer->currentElement(),
            'bar should now be current element'
        );

        $writer->endElement();

        $this->assertEquals(
            'foo',
            $writer->currentElement(),
            'foo should now be current element'
        );

        $writer->endElement();

        $this->assertEquals(
            '',
            $writer->currentElement(),
            'No current element, should return empty string'
        );
    }

    public function testGetXML()
    {
        $writer = new XMLWriter;

        $this->assertEquals(
            '<?xml version="1.0" encoding="UTF-8"?>',
            trim($writer->getXML()),
            'Nothing written should return only the xml declaration'
        );

        $writer->startElement('foo');
        $writer->startElement('bar');
        $writer->text('foobar');
        $writer->endElement();
        $writer->endElement();

        $this->assertEquals(
            "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<foo><bar>foobar</bar></foo>",
            trim($writer->getXML())
        );
    }

    public function testClear()
    {
        $writer = new XMLWriter;
        $writer->startElement('bar');
        $writer->text('foobar');
        $writer->endElement();

        $writer->clear();

        $writer->startElement('foo');
        $writer->text('text');
        $writer->endElement();

        $this->assertEquals(
            "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<foo>text</foo>",
            trim($writer->getXML())
        );
    }
}
