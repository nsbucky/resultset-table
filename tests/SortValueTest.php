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
        $this->sort->setSortableName('test');
    }

    public function testIsBeingSorted()
    {
        $this->sort->setInput( [
            'sort_dir'=>'asc',
            'sort'=>'test'
        ] );

        $this->assertTrue($this->sort->isBeingSorted());
    }
    
    public function testGetCurrentSortDirection()
    {
        $this->sort->setInput( [
            'sort_dir'=>'asc',
            'sort'=>'test'
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
            'sort'=>'test'
        ] );

        $this->assertEquals( 'desc', $this->sort->getNextSortDirection() );

        $this->sort->setInput( [
            'sort_dir' => 'desc',
            'sort'=>'test'
        ] );

        $this->assertEquals( 'asc', $this->sort->getNextSortDirection() );
    }

    public function testCreateSortableLink()
    {
        /*$this->sort->setInput( [
            'sort_dir' => 'desc',
            'sort'=>'test'
        ] );*/

        $link = $this->sort->createSortableLink( 'sort me' );
        
        $expected = '<a href="?sort=test&sort_dir=asc" class="grid-view-sort-asc"> sort me</a>';
        
        $this->assertEquals($expected, $link);
    }

    public function testCreateSortableLinkAsc()
    {
        $this->sort->setInput( [
            'sort_dir' => 'asc',
            'sort'=>'test'
        ] );

        $link = $this->sort->createSortableLink( 'sort me' );

        $expected = '<a href="?sort_dir=desc&sort=test" class="grid-view-sort-asc"><i class="fa fa-chevron-up"></i> sort me</a>';

        $this->assertEquals($expected, $link);
    }

    public function testCreateSortableLinkDesc()
    {
        $this->sort->setInput( [
            'sort_dir' => 'desc',
            'sort'=>'test'
        ] );

        $link = $this->sort->createSortableLink( 'sort me' );

        $expected = '<a href="?sort_dir=asc&sort=test" class="grid-view-sort-desc"><i class="fa fa-chevron-down"></i> sort me</a>';

        $this->assertEquals($expected, $link);
    }
}

class MySort
{
    use \ResultSetTable\Traits\SortValue;
    use \ResultSetTable\Traits\QueryString;

}
