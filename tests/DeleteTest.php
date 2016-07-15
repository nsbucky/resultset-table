<?php

/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/15/2016
 * Time: 8:15 AM
 */
class DeleteTest extends PHPUnit_Framework_TestCase
{
    public function testHiddenFields()
    {
        $button = new \ResultSetTable\Buttons\Delete('index.html');

        $actual = $button->render();

        #file_put_contents('test.html', $actual);

        $html = new DOMDocument();
        $html->loadHTML($actual);

        $form = $html->getElementsByTagName('form');

        $this->assertEquals(1, $form->length);

        $butt = $html->getElementsByTagName('button')->item(0);

        $att = $butt->attributes;

        $this->assertEquals('Delete', $butt->nodeValue);
        $this->assertTrue($butt->hasAttributes());

        $class = $att->getNamedItem('class');

        $this->assertEquals('btn btn-xs btn-danger', $class->nodeValue);

        $click = $att->getNamedItem('onclick');

        $this->assertEquals('return confirm(\'Are you sure?\');', $click->nodeValue);

        $input = $html->getElementsByTagName('input')->item(0);

        $name = $input->attributes->getNamedItem('name')->nodeValue;

        $this->assertEquals('_method', $name);

        $name = $input->attributes->getNamedItem('value')->nodeValue;

        $this->assertEquals('DELETE', $name);
    }
}
