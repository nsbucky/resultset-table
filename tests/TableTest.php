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
    ];

    public function testBuildSection()
    {
        $table = new \ResultSetTable\Table( $this->dataSource );

        $columns = [
            new \ResultSetTable\Columns\DefaultColumn(['name'=>'foo'])
        ];

        $section = $table->buildSection( $this->dataSource, 'thead', 'th', $columns );

        var_dump( $section );
    }

    public function testRender()
    {

    }

    public function testRowCss()
    {

    }

    public function testDetectFormat()
    {

    }
}
