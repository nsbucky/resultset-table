<?php

/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/12/2016
 * Time: 6:40 AM
 */
class FilterValueTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var MyFilter
     */
    private $filter;
    
    public function setUp()
    {
        $this->filter = new MyFilter();
    }

    public function testGetFilterTextInput()
    {
        $this->filter->setFilter(true);
        $this->filter->setInput([
            'test'=>'bar'
        ]);

        $actual = $this->filter->getFilter();
        $expected = '<div class="rst-filter-container">'.
            '<input type="text" name="test" style="width:100%" class="rst-filter-input input-small form-control" value="bar">'.
            '</div>';

        $this->assertEquals( $expected, $actual);
    }
    
    public function testGetFilterDropDown()
    {
        $this->filter->setFilter([
            0=>'foo',
            1=>'bar',
        ]);

        $this->filter->setInput([
            'test' => 1,
        ]);

        $actual = $this->filter->getFilter();

        $expected = '<select name="test" class="form-control rst-filter-select">'.
                    '<option value="0" >foo</option>'.
                    '<option value="1" selected="selected">bar</option>'.
                    '</select>';
        
        $this->assertEquals($expected, $actual);
    }
}

class MyFilter
{
    use \ResultSetTable\Traits\FilterValue;
    use \ResultSetTable\Traits\QueryString;

    protected $name = 'test';
}
