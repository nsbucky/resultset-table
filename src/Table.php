<?php
/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/11/2016
 * Time: 9:56 AM
 */

namespace ResultSetTable;

use ResultSetTable\Buttons\Button;
use ResultSetTable\Columns\Column;
use ResultSetTable\Rows\Row;
use ResultSetTable\Traits\Configure;

class Table implements Renderable
{
    use Configure;

    private $dataSource;

    /**
     * @var Column[]
     */
    private $columns = [];

    /**
     * @var Button[]
     */
    private $buttons = [];

    /**
     * @var Row[]
     */
    private $rows = [];

    /**
     * @var array
     */
    protected $configurableOptions = [];

    /**
     * @var string
     */
    protected $tableCss = 'table table-striped table-hover';

    /**
     * @var string
     */
    protected $tableId = 'my-result-table';

    /**
     * Table constructor.
     * @param \IteratorAggregate|array $dataSource
     */
    public function __construct( $dataSource, array $options = [] )
    {
        $this->dataSource = $dataSource;

        $this->configure( $options );
    }

    /**
     * @param $config
     * @param array $options
     * @return $this
     */
    public function addColumn( $config, array $options = [] )
    {
        if( $config instanceof Column ) {
            $this->columns[] = $config;
            return $this;
        }

        return $this;
    }

    /**
     * @param $config
     * @param array $options
     * @return $this
     */
    public function addButton( $config, array $options = [] )
    {
        if( $config instanceof Button ) {
            $this->buttons[] = $config;

            return $this;
        }

        return $this;
    }

    /**
     * @param $config
     * @param array $options
     * @return $this
     */
    public function addRow( $config, array $options = [] )
    {
        if( $config instanceof Row ) {
            $this->rows[] = $config;
            
            return $this;
        }
        
        return $this;
    }

    public function render()
    {
        // TODO: Implement render() method.
    }
}