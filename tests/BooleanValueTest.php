<?php

/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/15/2016
 * Time: 8:45 AM
 */
class BooleanValueTest extends PHPUnit_Framework_TestCase
{
    public function testRendersLabel()
    {
        $column = new \ResultSetTable\Columns\BooleanValue([
            'name'=>'test'
        ]);

        $column->setDataSource([
            'test'=>1
        ]);

        $actual = $column->render();

        #file_put_contents('test.html', $actual);

        $html = new DOMDocument();
        $html->loadHTML( $actual );

        $span = $html->getElementsByTagName('span');

        $this->assertEquals('Yes', $span->item(0)->nodeValue);
    }
}
