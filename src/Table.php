<?php
/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/11/2016
 * Time: 9:56 AM
 */

namespace ResultSetTable;

use Assert\Assertion;
use ResultSetTable\Buttons\Action;
use ResultSetTable\Buttons\Button;
use ResultSetTable\Columns\Column;
use ResultSetTable\Columns\DefaultColumn;
use ResultSetTable\Contracts\Renderable;
use ResultSetTable\Rows\Row;
use ResultSetTable\Traits\Configure;

class Table implements Renderable
{
    use Configure;

    private $dataSource;

    /**
     * @var Column[]
     */
    private $columns = [ ];

    /**
     * @var Button[]
     */
    private $buttons = [ ];

    /**
     * @var Row[]
     */
    private $rows = [ ];

    /**
     * @var array
     */
    protected $configurableOptions = [ ];

    /**
     * @var string
     */
    protected $tableCss = 'table table-striped table-hover';

    /**
     * @var string
     */
    protected $tableId = 'my-result-table';

    protected $tdCss;

    protected $thCss;

    protected $rowCss;

    /**
     * Table constructor.
     * @param \IteratorAggregate|array $dataSource
     */
    public function __construct( $dataSource, array $options = [ ] )
    {
        $this->dataSource = $dataSource;

        $this->configure( $options );
    }

    /**
     * @param $config
     * @param array $options
     * @return $this
     */
    public function addColumn( $config, array $options = [ ] )
    {
        if( $config instanceof Column ) {
            $this->columns[] = $config;

            return $this;
        }

        if( is_scalar( $config ) ) {
            list( $name, $format ) = $this->detectFormatting( $config );

            $options = array_merge( [
                'name'      => $name,
                'formatter' => $format,
            ], $options );

            $this->columns[] = new DefaultColumn( $options );
        }

        if( is_array( $config ) ) {
            $this->columns[] = new DefaultColumn( $config );
        }

        return $this;
    }

    /**
     * @param $config
     * @param array $options
     * @return $this
     */
    public function addButton( $config, array $options = [ ] )
    {
        if( $config instanceof Button ) {
            $this->buttons[] = $config;

            return $this;
        }

        if( is_array( $config ) ) {
            $this->buttons[] = new Action( $config );
        }

        return $this;
    }

    /**
     * @param $config
     * @param array $options
     * @return $this
     */
    public function addRow( $config, array $options = [ ] )
    {
        if( $config instanceof Row ) {
            $this->rows[] = $config;

            return $this;
        }

        return $this;
    }

    /**
     * @param $name
     * @return array|void
     */
    protected function detectFormatting( $name )
    {
        if( strpos( $name, ':' ) === false ) {
            return [$name, null];
        }

        list( $name, $format ) = explode( ':', $name );

        $formatClass = "\\ResultSetTable\\Formats\\" . ucfirst( $format );
        $format      = new $formatClass;

        return [ $name, $format ];
    }

    /**
     * @param $dataSource     
     * @param $cellTag
     * @param array $columns
     */
    public function buildSection( $dataSource, $cellTag, array $columns )
    {
        Assertion::scalar( $cellTag );
        Assertion::inArray( $cellTag, [ 'th', 'td' ] );

        $cols = [ ];

        foreach( $columns as $column ) {

            $cellCss = $cellTag == 'td' ? $this->getTdCss() : $this->getThCss();

            $cols[] = sprintf( '<%s class="%s">%s</%1$s>', $cellTag, $cellCss, $column );
        }

        return sprintf( '<tr class="%s">%s</tr>', $this->getRowCss($dataSource), implode( PHP_EOL, $cols ) );
    }

    /**
     * @return string
     */
    public function render()
    {
        $thead = [];
        $tbody = [];

        $firstTime = true;

        foreach( $this->dataSource as $dataSource ) {
            $this->applyDataSourceToColumns($dataSource);

            if( $firstTime ) {
                $thead[] = $this->buildSection( $dataSource, 'th', $this->buildHeaders() );
                $firstTime = false;
            }

            $tbody[] = $this->buildSection( $dataSource,'td', $this->buildCells() );
        }

        return sprintf(
            '<table class="%s" id="%s"><thead>%s</thead><tbody>%s</tbody></table>',
            $this->getTableCss(),
            $this->getTableId(),
            implode( PHP_EOL, $thead ) ,
            implode( PHP_EOL, $tbody )
        );
    }

    protected function buildCells()
    {
        $cells = [];

        foreach ($this->columns as $column) {
            if( ! $column->isVisible() ) {
                continue;
            }

            $cells[] = $column->render();
        }

        return $cells;
    }

    /**
     * @return array
     */
    protected function buildHeaders()
    {
        $headers = [];

        foreach ($this->columns as $column) {
            if( ! $column->isVisible() ) {
                continue;
            }

            $headers[] = $column->getHeader();
        }

        return $headers;
    }

    /**
     * @param $dataSource
     */
    protected function applyDataSourceToColumns($dataSource)
    {
        foreach($this->columns as $column) {
            $column->setDataSource($dataSource);
        }
    }

    /**
     * @return string
     */
    public function getTableCss()
    {
        return $this->tableCss;
    }

    /**
     * @param string $tableCss
     */
    public function setTableCss( $tableCss )
    {
        Assertion::string( $tableCss );

        $this->tableCss = $tableCss;
    }

    /**
     * @return string
     */
    public function getTableId()
    {
        return $this->tableId;
    }

    /**
     * @param string $tableId
     */
    public function setTableId( $tableId )
    {
        Assertion::string( $tableId );

        $this->tableId = $tableId;
    }

    /**
     * @return mixed
     */
    public function getTdCss()
    {
        return $this->tdCss;
    }

    /**
     * @param mixed $tdCss
     */
    public function setTdCss( $tdCss )
    {
        Assertion::string( $tdCss );

        $this->tdCss = $tdCss;
    }

    /**
     * @return mixed
     */
    public function getThCss()
    {
        return $this->thCss;
    }

    /**
     * @param mixed $thCss
     */
    public function setThCss( $thCss )
    {
        Assertion::string( $thCss );

        $this->thCss = $thCss;
    }

    /**
     * @return mixed
     */
    public function getRowCss()
    {
        if( $this->rowCss instanceof \Closure) {
            $f = $this->rowCss;

            return $f($this->dataSource);
        }

        return $this->rowCss;
    }

    /**
     * @param mixed $rowCss
     */
    public function setRowCss( $rowCss )
    {
        $this->rowCss = $rowCss;
    }


}