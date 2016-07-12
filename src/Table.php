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
use ResultSetTable\Columns\DefaultColumn;
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

        if( is_scalar( $config )) {
            list($name,$format) = $this->detectFormatting( $config );

            $this->columns[] = new DefaultColumn( [
                'name' => $name,
                'format'=>$format,
            ] );
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

    /**
     * @param $name
     */
    protected function detectFormatting( $name )
    {
        if( strpos( $name, ':' ) === false ) {
            return;
        }

        $formatOptions = substr( $name, strpos( $name, ':' ) + 1 );

        // detect if the value now required formatting
        // formatting should be like this:
        // img.jpg:image|width:45|height:34
        foreach( [ 'image', 'url', 'size', 'email', 'money','rounded' ] as $formatter ) {
            if( stripos( $name, ':' . $formatter ) !== false ) {
                $formatClass     = "\\ResultTable\\Formatter\\" . ucfirst( $formatter );
                $this->formatter = new $formatClass( $formatOptions );
                break;;
            }
        }
    }

    public function render()
    {
        // TODO: Implement render() method.
    }
}