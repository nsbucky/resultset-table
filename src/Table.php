<?php
/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/11/2016
 * Time: 9:56 AM
 */

namespace ResultSetTable;

use Assert\Assertion;
use Illuminate\Pagination\LengthAwarePaginator;
use ResultSetTable\Buttons\Action;
use ResultSetTable\Buttons\Button;
use ResultSetTable\Columns\Column;
use ResultSetTable\Columns\DefaultColumn;
use ResultSetTable\Contracts\HasScript;
use ResultSetTable\Contracts\Renderable;
use ResultSetTable\Rows\Row;
use ResultSetTable\Traits\Configure;

/**
 * Class Table
 * @package ResultSetTable
 */
class Table implements Renderable
{
    use Configure;

    /**
     * @var array|\IteratorAggregate
     */
    protected $dataSource;

    /**
     * @var Column[]
     */
    protected $columns = [ ];

    /**
     * @var Button[]
     */
    protected $buttons = [ ];

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

    /**
     * @var
     */
    protected $tdCss;

    /**
     * @var
     */
    protected $thCss;

    /**
     * @var
     */
    protected $rowCss;

    /**
     * @var bool
     */
    protected $defaultToSortable = false;

    /**
     * Table constructor.
     * @param \IteratorAggregate|LengthAwarePaginator|array $dataSource
     * @param array $options
     */
    public function __construct( $dataSource, array $options = [ ] )
    {
        if( $dataSource instanceof LengthAwarePaginator) {
            $dataSource = $dataSource->items();
        }
        
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

            if( $this->defaultToSortable ) {
                $config->setSortable( true );
            }

            $this->columns[] = $config;

            return $this;
        }

        if( is_scalar( $config ) ) {
            list( $name, $format ) = $this->detectFormatting( $config );

            $options = array_merge( [
                'name'      => $name,
                'formatter' => $format,
                'sortable'  => $this->defaultToSortable,
            ], $options );

            $this->columns[] = new DefaultColumn( $options );
        }

        if( is_array( $config ) ) {
            $config['sortable'] = $this->defaultToSortable;
            $this->columns[]    = new DefaultColumn( $config );
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
     * @param Column[] $columns
     * @param array $buttons
     * @return string
     */
    public function buildSection( $dataSource, $cellTag, array $columns, array $buttons = [ ] )
    {
        Assertion::scalar( $cellTag );
        Assertion::inArray( $cellTag, [ 'th', 'td', 'tf' ] );

        $cols = [ ];

        foreach( $columns as $column ) {

            $cellCss     = $cellTag == 'td' ? $this->getTdCss() . ' ' . $column->getCss() : $this->getThCss();
            $columnValue = $cellTag == 'th' ? $column->getHeader() : $column->render();
            $columnValue = $cellTag == 'tf' ? $column->getFooter() : $columnValue;

            $cellTag = $cellTag == 'tf' ? 'td' : $cellTag;

            $cols[] = sprintf( '<%s class="%s">%s</%1$s>', $cellTag, trim( $cellCss ), $columnValue );
        }

        if( count( $buttons ) ) {
            $cols[] = sprintf( '<td>%s</td>', implode( PHP_EOL, $buttons ) );
        }

        if( $cellTag == 'th' && count( $this->buttons ) > 0 ) {
            $cols[] = '<th>Actions</th>';
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
        $tfoot = [ ];

        $firstTime = true;

        foreach( $this->dataSource as $dataSource ) {
            $this->applyDataSourceToColumns( $dataSource );
            $this->applyDataSourceToButtons( $dataSource );

            if( $firstTime ) {
                $thead[]   = $this->buildSection( $dataSource, 'th', $this->columns );
                $firstTime = false;
            }

            $tbody[] = $this->buildSection( $dataSource, 'td', $this->columns, $this->buildButtons() );

        }

        $tfoot[] = $this->buildSection( null, 'tf', $this->columns);

        return sprintf(
            '<table class="%s" id="%s"><thead>%s</thead><tfoot>%s</tfoot><tbody>%s</tbody></table>%s',
            $this->getTableCss(),
            $this->getTableId(),
            implode( PHP_EOL, $thead ),
            implode( PHP_EOL, $tfoot),
            implode( PHP_EOL, $tbody ),
            implode( PHP_EOL, $this->buildScripts() )
        );
    }

    /**
     * @return array
     */
    protected function buildScripts()
    {
        $scripts = [ ];

        foreach( $this->columns as $column ) {

            if( $column instanceof HasScript ) {
                $scripts[$column->getScriptKey()] = $column->getScript();
            }
        }

        return $scripts;
    }

    /**
     * @return array
     */
    protected function buildButtons()
    {
        $buttons = [ ];

        foreach( $this->buttons as $button ) {
            if( !$button->isVisible() ) {
                continue;
            }

            $buttons[] = $button->render();
        }

        return $buttons;
    }

    /**
     * @param $dataSource
     */
    protected function applyDataSourceToColumns( $dataSource )
    {
        foreach( $this->columns as $column ) {
            $column->setDataSource( $dataSource );
        }
    }

    /**
     * @param $dataSource
     */
    protected function applyDataSourceToButtons( $dataSource )
    {
        foreach( $this->buttons as $button ) {
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
        if( $dataSource && $this->rowCss instanceof \Closure ) {
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

    /**
     * @return boolean
     */
    public function isDefaultToSortable()
    {
        return $this->defaultToSortable;
    }

    /**
     * @param boolean $defaultToSortable
     */
    public function setDefaultToSortable( $defaultToSortable )
    {
        $this->defaultToSortable = $defaultToSortable;
    }


}