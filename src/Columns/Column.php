<?php
/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/11/2016
 * Time: 9:59 AM
 */

namespace ResultSetTable\Columns;


use ResultSetTable\Traits\Configure;
use ResultSetTable\Traits\Sortable;

abstract class Column
{
    use Sortable;
    use Configure;

    protected $queryString = [];

    protected $dataSource;

    protected $configurableOptions = [
        'sortQueryStringKey',
        'sortDirectionQueryStringKey',
        'sortableName',
        'sortDirection',
        'header'
    ];

    /**
     * @var bool
     */
    protected $sortable = false;

    /**
     * Column constructor.
     * @param array $configurableOptions
     */
    public function __construct( array $configurableOptions )
    {
        $this->configure($configurableOptions);
        $this->queryString = $_GET;
    }

    public function setDataSource( $dataSource )
    {
        $this->dataSource = $dataSource;
    }

    /**
     * @return string
     */
    abstract function getValue();
}