<?php
/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/11/2016
 * Time: 9:59 AM
 */

namespace ResultSetTable\Columns;


use ResultSetTable\Formatter;
use ResultSetTable\Renderable;
use ResultSetTable\Traits\Configure;
use ResultSetTable\Traits\FilterValue;
use ResultSetTable\Traits\QueryString;
use ResultSetTable\Traits\SortValue;

abstract class Column implements Renderable
{
    use SortValue;
    use Configure;
    use FilterValue;
    use QueryString;

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
        'raw',
        'name',
        'value',
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
     * @var string
     */
    protected $name;

    /**
     * @var
     */
    protected $value;

    /**
     * @var bool
     */
    protected $visible = true;

    /**
     * @var bool escape output
     */
    protected $raw = false;

    /**
     * @var Formatter
     */
    protected $formatter;

    /**
     * Column constructor.
     * @param array $configurableOptions
     */
    public function __construct( array $configurableOptions )
    {
        $this->configure($configurableOptions);
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

    /**
     * return string
     */
    protected function fetchRawValueFromDataSource()
    {
        if( isset($this->value)) {
            return $this->fetchDataFromValue();
        }
        
        if( is_array( $this->dataSource ) ) {
            return array_get($this->dataSource, $this->name);
        }
        
        if( is_object( $this->dataSource )) {
            return object_get($this->dataSource, $this->name);
        }
        
        return null;
    }
    
    protected function fetchDataFromValue()
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