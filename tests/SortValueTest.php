<?php

/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/12/2016
 * Time: 7:17 AM
 */
class SortValueTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var MySort
     */
    private $sort;
    
    public function setUp()
    {
        $this->sort = new MySort();
    }

    public function testIsBeingSorted()
    {
        $this->sort->setInput( [
            'sort_dir'=>'asc'
        ] );

        $this->assertTrue($this->sort->isBeingSorted());
    }
    
    public function testGetCurrentSortDirection()
    {
        $this->sort->setInput( [
            'sort_dir'=>'asc'
        ] );

        $this->assertEquals( 'asc', $this->sort->getCurrentSortDirection() );

        $this->sort->setInput( [
            'sort_dir'=>'desc'
        ] );

        $this->assertEquals( 'desc', $this->sort->getCurrentSortDirection() );
    }

    public function testGetNextSortDirection()
    {
        $this->sort->setInput( [
            'sort_dir' => 'asc',
        ] );

        $this->assertEquals( 'desc', $this->sort->getNextSortDirection() );

        $this->sort->setInput( [
            'sort_dir' => 'desc',
        ] );

        $this->assertEquals( 'asc', $this->sort->getNextSortDirection() );
    }
}

class MySort
{
    use \ResultSetTable\Traits\SortValue;
    use \ResultSetTable\Traits\QueryString;
}
