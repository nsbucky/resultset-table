<?php

/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/13/2016
 * Time: 11:30 AM
 */
class ColumnTest extends PHPUnit_Framework_TestCase
{

    public function testSetsConfigValues()
    {
        $options = [
            'visible'=>false,
        ];

        $column = new MyColumn( $options );

        $this->assertFalse( $column->isVisible() );
    }

    public function testGetValueFromDataSource()
    {
        $dataSource = [
            'test'=>'balls'
        ];

        $column = new MyColumn(['name'=>'test']);

        $column->setDataSource( $dataSource );

        $value = $column->getValue();

        $this->assertEquals( 'balls', $value );
    }

    public function testGetValueFromDataSourceObject()
    {
        $dataSource = new stdClass();
        $dataSource->test = 'balls';

        $column = new MyColumn(['name'=>'test']);

        $column->setDataSource( $dataSource );

        $value = $column->getValue();

        $this->assertEquals( 'balls', $value );
    }
    public function testGetValueFromClosure()
    {
        $dataSource = new stdClass();
        $dataSource->test = 'balls';

        $column = new MyColumn([
            'name'=>'test',
            'value'=>function($data){
                return strtoupper( $data->test );
            }
        ]);

        $column->setDataSource( $dataSource );

        $value = $column->getValue();

        $this->assertEquals( 'BALLS', $value );
    }

    public function testRenderReturnsString()
    {
        $dataSource = [
            'test'=>'balls'
        ];

        $column = new MyColumn(['name'=>'test']);

        $column->setDataSource( $dataSource );

        $value = $column->render();

        $this->assertEquals( 'balls', $value );
    }

    public function testRenderReturnsEscapedString()
    {
        $dataSource = [
            'test'=>'<p>balls'
        ];

        $column = new MyColumn(['name'=>'test']);

        $column->setDataSource( $dataSource );

        $value = $column->render();

        $this->assertEquals( '&lt;p&gt;balls', $value );
    }

    public function testRenderReturnsRaw()
    {
        $dataSource = [
            'test'=>'<p>balls'
        ];

        $column = new MyColumn(['name'=>'test', 'raw'=>1]);

        $column->setDataSource( $dataSource );

        $value = $column->render();

        $this->assertEquals( '<p>balls', $value );
    }
    
    public function testReturnsFormattedValue()
    {
        $dataSource = [
            'test'=>'1230.4'
        ];

        $column = new MyColumn(['name'=>'test']);
        $column->setFormatter( new \ResultSetTable\Formats\Money() );

        $column->setDataSource( $dataSource );

        $value = $column->render();

        $this->assertEquals( '$1,230.40', $value );
    }
}

class MyColumn extends \ResultSetTable\Columns\Column
{
    public function getValue()
    {
        return $this->fetchRawValueFromDataSource();
    }

}
