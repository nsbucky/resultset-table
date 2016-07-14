<?php

/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/13/2016
 * Time: 4:15 PM
 */
class TableTest extends PHPUnit_Framework_TestCase
{

    private $dataSource = [
        [
            'foo'=>'bar'
        ],
        [
            'foo'=>'nuts'
        ],
    ];

    public function testBuildSection()
    {
        $table = new \ResultSetTable\Table( $this->dataSource );

        $column = new \ResultSetTable\Columns\DefaultColumn(['name'=>'foo']);

        $column->setDataSource($this->dataSource[0]);

        $columns = [
            $column->render(),
        ];

        $section = $table->buildSection( $this->dataSource[0], 'td', $columns );
        $expected = '<tr class=""><td class="">bar</td></tr>';
        
        $this->assertEquals($expected, $section);
    }

    public function testRenderSimpleTable()
    {
        $table = new \ResultSetTable\Table( $this->dataSource );

        $table->addColumn('foo');

        $actual = $table->render();

        #file_put_contents('test.html', $actual);

        $html = new DOMDocument();
        $html->loadHTML($actual);

        $this->assertEquals(1, $html->getElementsByTagName('tbody')->length);

        $this->assertEquals(1, $html->getElementsByTagName('thead')->length);

        $this->assertEquals(1, $html->getElementsByTagName('th')->length);

        $this->assertEquals(2, $html->getElementsByTagName('td')->length);

        $this->assertEquals('Foo', $html->getElementsByTagName('th')->item(0)->nodeValue);

        $this->assertEquals('bar', $html->getElementsByTagName('td')->item(0)->nodeValue);

        $this->assertEquals('nuts', $html->getElementsByTagName('td')->item(1)->nodeValue);

    }

    public function testRowCss()
    {
        $table = new \ResultSetTable\Table( $this->dataSource );

        $table->addColumn('foo');

        $table->setRowCss('balls');

        $actual = $table->render();

        #file_put_contents('test.html', $actual);

        $html = new DOMDocument();
        $html->loadHTML($actual);
        
        $tr = $html->getElementsByTagName('tr');
        $this->assertTrue( $tr->item(1)->hasAttributes());

        $trClass = $tr->item(1);

        $this->assertEquals('balls', $trClass->attributes->getNamedItem('class')->nodeValue);
    }

    public function testRowCssCallback()
    {
        $table = new \ResultSetTable\Table( $this->dataSource );

        $table->addColumn('foo');

        $table->setRowCss(function($data){

            if($data['foo'] == 'nuts') {
                return 'found';
            }

            return 'balls';
        });

        $actual = $table->render();

        #file_put_contents('test.html', $actual);

        $html = new DOMDocument();
        $html->loadHTML($actual);

        $tr = $html->getElementsByTagName('tr');

        $this->assertTrue( $tr->item(1)->hasAttributes());

        $trClass = $tr->item(1);

        $this->assertEquals('balls', $trClass->attributes->getNamedItem('class')->nodeValue);

        $trClass2 = $tr->item(2);

        $this->assertEquals('found', $trClass2->attributes->getNamedItem('class')->nodeValue);
    }

    public function testDetectFormat()
    {
        $table = new \ResultSetTable\Table( [
            [
                'dollar'=>123.4
            ]
        ] );

        $table->addColumn('dollar:money');
        
        $actual = $table->render();
        
        #file_put_contents('test.html', $actual);
        
        $column = $table->getColumns()[0];
        
        $this->assertInstanceOf('ResultSetTable\Formats\Money', $column->getFormatter());
    }

    public function testGrabsChildData()
    {
        $table = new \ResultSetTable\Table( [
            [
                'fabric'=>[
                    'color'=>'blue'
                ]
            ]
        ] );

        $table->addColumn('fabric.color');

        $actual = $table->render();

        #file_put_contents('test.html', $actual);

        $html = new DOMDocument();
        $html->loadHTML($actual);

        $tds = $html->getElementsByTagName('td');

        $this->assertEquals('blue', $tds->item(0)->nodeValue);
    }

    public function testButton()
    {
        
    }
}
