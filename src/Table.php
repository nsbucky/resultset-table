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
            return;
        }

        list( $name, $format ) = explode( ':', $name );

        $formatClass = "\\ResultSetTable\\Formats\\" . ucfirst( $format );
        $format      = new $formatClass;

        return [ $name, $format ];
    }

    /**
     * @param $dataSource
     * @param $wrapTag
     * @param $cellTag
     * @param Column[] $columns
     */
    public function buildSection( $dataSource, $wrapTag, $cellTag, array $columns )
    {
        Assertion::scalar( $wrapTag );
        Assertion::scalar( $cellTag );
        Assertion::inArray( $cellTag, [ 'th', 'td' ] );
        Assertion::inArray( $wrapTag, [ 'tbody', 'thead', 'tfoot' ] );

        $cols = [ ];

        foreach( $columns as $column ) {
            $column->setDataSource( $dataSource );

            $cellCss = $cellTag == 'td' ? $this->getTdCss() : $this->getThCss();

            $cols[] = sprintf( '<%s class="%s">%s</%1$s>', $cellTag, $cellCss, $column->render() );
        }

        return sprintf( '<%s><tr class="%s">%s</tr></%1$s>', $wrapTag, $this->getRowCss($dataSource), implode( PHP_EOL, $cols ) );
    }

    /**
     * @return string
     */
    public function render()
    {
        $sections   = [ ];
        $sections[] = $this->buildSection( $this->dataSource, 'thead', 'th', $this->columns );
        $sections[] = $this->buildSection( $this->dataSource, 'tbody', 'td', $this->columns );

        return sprintf( '<table class="%s" id="%s">%s</table>', $this->getTableCss(), $this->getTableId(), implode( PHP_EOL, $sections ) );
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