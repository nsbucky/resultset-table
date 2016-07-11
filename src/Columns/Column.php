<?php
/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/11/2016
 * Time: 9:59 AM
 */

namespace ResultSetTable\Columns;


use ResultSetTable\Renderable;
use ResultSetTable\Traits\Configure;
use ResultSetTable\Traits\FilterValue;
use ResultSetTable\Traits\SortValue;

abstract class Column implements Renderable
{
    use SortValue;
    use Configure;
    use FilterValue;

    /**
     * @var array
     */
    protected $queryString = [];

    protected $dataSource;

    /**
     * @var array
     */
    protected $configurableOptions = [
        'sortQueryStringKey',
        'sortDirectionQueryStringKey',
        'sortableName',
        'sortDirection',
        'header',
        'visible',
        'raw'
    ];

    /**
     * @var bool
     */
    protected $sortable = false;

    /**
     * @var bool
     */
    protected $filter;

    /**
     * @var
     */
    protected $name;

    /**
     * @var bool
     */
    protected $visible = true;

    /**
     * @var bool escape output
     */
    protected $raw = false;

    /**
     * Column constructor.
     * @param array $configurableOptions
     */
    public function __construct( array $configurableOptions )
    {
        $this->configure($configurableOptions);
        $this->queryString = $_GET;
    }

    /**
     * @param $dataSource
     */
    public function setDataSource( $dataSource )
    {
        $this->dataSource = $dataSource;
    }

    /**
     * @return bool
     */
    public function isVisible()
    {
        return (bool) $this->visible;
    }

    /**
     * @return string
     */
    abstract public function getValue();
    
    protected function fetchRawValueFromDataSource()
    {
        
    }

    /**
     * @return string
     */
    public function render()
    {
        return $this->raw ? $this->getValue() : e( $this->getValue() );
    }
}