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
        if ($config instanceof Column) {
            $this->columns[] = $config;

            return $this;
        }

        if (is_scalar( $config )) {
            list( $name, $format ) = $this->detectFormatting( $config );

            $options = array_merge( [
                'name'      => $name,
                'formatter' => $format,
            ], $options );

            $this->columns[] = new DefaultColumn( $options );
        }

        if (is_array( $config )) {
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
        if ($config instanceof Button) {
            $this->buttons[] = $config;

            return $this;
        }

        if (is_array( $config )) {
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
        if ($config instanceof Row) {
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
        if (strpos( $name, ':' ) === false) {
            return [ $name, null ];
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
     * @param array $buttons
     * @return string
     */
    public function buildSection( $dataSource, $cellTag, array $columns, array $buttons = [] )
    {
        Assertion::scalar( $cellTag );
        Assertion::inArray( $cellTag, [ 'th', 'td' ] );

        $cols = [ ];

        foreach ($columns as $column) {

            $cellCss = $cellTag == 'td' ? $this->getTdCss() : $this->getThCss();

            $cols[] = sprintf( '<%s class="%s">%s</%1$s>', $cellTag, $cellCss, $column );
        }

        if( count($buttons) ) {
            $cols[] = implode(PHP_EOL, $buttons);
        }

        if( $cellTag == 'th' && count($this->buttons) > 0 ) {
            $cols[] = 'Actions';
        }

        $rowCss = $cellTag == 'td' ? $this->getRowCss( $dataSource ) : null;

        return sprintf( '<tr class="%s">%s</tr>', $rowCss, implode( PHP_EOL, $cols ) );
    }

    /**
     * @return string
     */
    public function render()
    {
        $thead = [ ];
        $tbody = [ ];

        $firstTime = true;

        foreach ($this->dataSource as $dataSource) {
            $this->applyDataSourceToColumns( $dataSource );
            $this->applyDataSourceToButtons( $dataSource );

            if ($firstTime) {
                $thead[]   = $this->buildSection( $dataSource, 'th', $this->buildHeaders() );
                $firstTime = false;
            }

            $tbody[] = $this->buildSection( $dataSource, 'td', $this->buildCells(), $this->buildButtons() );
        }

        return sprintf(
            '<table class="%s" id="%s"><thead>%s</thead><tbody>%s</tbody></table>',
            $this->getTableCss(),
            $this->getTableId(),
            implode( PHP_EOL, $thead ),
            implode( PHP_EOL, $tbody )
        );
    }

    protected function buildButtons()
    {
        $buttons = [];

        foreach( $this->buttons as $button) {
            if( ! $button->isVisible() ) {
                continue;
            }

            $buttons[] = $button->render();
        }

        return $buttons;
    }

    /**
     * @return array
     */
    protected function buildCells()
    {
        $cells = [ ];

        foreach ($this->columns as $column) {
            if (!$column->isVisible()) {
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
        $headers = [ ];

        foreach ($this->columns as $column) {
            if (!$column->isVisible()) {
                continue;
            }

            $headers[] = $column->getHeader();
        }

        return $headers;
    }

    /**
     * @param $dataSource
     */
    protected function applyDataSourceToColumns( $dataSource )
    {
        foreach ($this->columns as $column) {
            $column->setDataSource( $dataSource );
        }
    }

    /**
     * @param $dataSource
     */
    protected function applyDataSourceToButtons( $dataSource )
    {
        foreach ($this->buttons as $button) {
            $button->setDataSource( $dataSource );
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
     * @param $dataSource
     * @return mixed
     */
    public function getRowCss( $dataSource = null )
    {
        if ($dataSource && $this->rowCss instanceof \Closure) {
            $f = $this->rowCss;

            return $f( $dataSource );
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

    /**
     * @return Columns\Column[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return Buttons\Button[]
     */
    public function getButtons()
    {
        return $this->buttons;
    }

    /**
     * @return Rows\Row[]
     */
    public function getRows()
    {
        return $this->rows;
    }
}