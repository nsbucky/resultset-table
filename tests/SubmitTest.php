<?php

/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/15/2016
 * Time: 7:43 AM
 */
class SubmitTest extends PHPUnit_Framework_TestCase
{
    public function testRendersForm()
    {
        $button = new \ResultSetTable\Buttons\Submit('index.html');

        $actual = $button->render();

        #file_put_contents('test.html', $actual);

        $html = new DOMDocument();
        $html->loadHTML($actual);

        $form = $html->getElementsByTagName('form');

        $this->assertEquals(1, $form->length);

        $butt = $html->getElementsByTagName('button')->item(0);

        $att = $butt->attributes;


        $this->assertEquals('Submit', $butt->nodeValue);
        $this->assertTrue($butt->hasAttributes());

        $class = $att->getNamedItem('class');

        $this->assertEquals('btn btn-xs btn-default', $class->nodeValue);

    }

    public function testHiddenFields()
    {
        $button = new \ResultSetTable\Buttons\Submit('index.html','Submit', 'post', [
            'hiddenFields'=>[
                'csrf'=>'1234abc'
            ]
        ]);

        $actual = $button->render();

        #file_put_contents('test.html', $actual);

        $html = new DOMDocument();
        $html->loadHTML($actual);

        $form = $html->getElementsByTagName('form');

        $this->assertEquals(1, $form->length);

        $butt = $html->getElementsByTagName('button')->item(0);

        $att = $butt->attributes;


        $this->assertEquals('Submit', $butt->nodeValue);
        $this->assertTrue($butt->hasAttributes());

        $class = $att->getNamedItem('class');

        $this->assertEquals('btn btn-xs btn-default', $class->nodeValue);
    }
}
