<?php

/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/18/2016
 * Time: 6:37 AM
 */
class PaginateTest extends PHPUnit_Framework_TestCase
{
    private $table;
    private $paginator;

    public function setUp()
    {
        $data = [
            [
                'foo'=>'bar',
            ]
        ];

        $this->paginator = new \Illuminate\Pagination\LengthAwarePaginator($data, 1, 20);

        $this->table = new \ResultSetTable\Table($this->paginator);
        $this->table->addColumn('foo');
    }

    public function testBaseUrl()
    {
        $url = 'http://yahoo.com';

        $decorator = new \ResultSetTable\Decorators\Paginate( $this->table, $this->paginator  );

        $decorator->setBaseUrl($url);

        $this->assertEquals($url, $decorator->getBaseUrl());

        $_GET = ['foo'=>'buns'];

        $this->assertEquals($url.'?foo=buns', $decorator->currentPageUrl());

        $this->assertEquals($url.'?foo=bar', $decorator->currentPageUrl(['foo'=>'bar']));

        $this->assertEquals($url.'?test=bar', $decorator->currentPageUrl(['test'=>'bar'],['foo']));

        $_GET = [];
    }
    
    public function testItemsPerPage()
    {
        $decorator = new \ResultSetTable\Decorators\Paginate( $this->table, $this->paginator  );
        $actual = $decorator->renderItemsPerPage();

        #file_put_contents('test.html', $actual);

        $html = new DOMDocument();
        $html->loadHTML($actual);


    }

    public function testBuildDownloadLink()
    {
        $decorator = new \ResultSetTable\Decorators\Paginate( $this->table, $this->paginator  );
        $decorator->setDownloadable(true);
        $decorator->setDownloadKey('_d');

        $a = '<a href="?_d=1">Download Results</a>';

        $this->assertEquals($a, $decorator->buildDownloadLink());
    }

    public function testGetFiltersEmpty()
    {
        $decorator = new \ResultSetTable\Decorators\Paginate( $this->table, $this->paginator );
        $actual = $decorator->renderFilters();

        file_put_contents('test.html', $actual);

        $html = new DOMDocument();
        $html->loadHTML($actual);

        $forms = $html->getElementsByTagName('form');

        $form = $forms->item(0);

        $this->assertTrue($form->hasChildNodes() );
        $this->assertEquals(1, $form->childNodes->length);
        $this->assertEquals('button', $form->childNodes->item(0)->nodeName);
    }
    
    public function testGetFilterInput()
    {
        $table = new \ResultSetTable\Table($this->paginator);
        $table->addColumn('foo')->setFilter(true);
        
        $decorator = new \ResultSetTable\Decorators\Paginate( $table, $this->paginator );
        $actual = $decorator->renderFilters();

        #file_put_contents('test.html', $actual);

        $html = new DOMDocument();
        $html->loadHTML($actual);

        $forms = $html->getElementsByTagName('form');

        $form = $forms->item(0);

        $this->assertTrue($form->hasChildNodes() );
        $this->assertEquals(2, $form->childNodes->length);
        $this->assertEquals('button', $form->childNodes->item(1)->nodeName);

        // input
        $input = $html->getElementsByTagName('input')->item(0);
        $this->assertEquals('foo', $input->attributes->getNamedItem('name')->nodeValue);
        $this->assertEmpty($input->attributes->getNamedItem('value')->nodeValue);
    }

    public function testRender()
    {
        $decorator = new \ResultSetTable\Decorators\Paginate( $this->table, $this->paginator );
        $actual = $decorator->render();

        file_put_contents('test.html', $actual);

        $html = new DOMDocument();
        $html->loadHTML($actual);
    }
}
