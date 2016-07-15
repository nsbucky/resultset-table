<?php
/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/11/2016
 * Time: 9:59 AM
 */

namespace ResultSetTable\Columns;


use Assert\Assertion;
use ResultSetTable\Contracts\Formatter;
use ResultSetTable\Contracts\Renderable;
use ResultSetTable\Traits\Configure;
use ResultSetTable\Traits\FilterValue;
use ResultSetTable\Traits\QueryString;
use ResultSetTable\Traits\SortValue;
use ResultSetTable\Traits\Tokenize;

/**
 * Class Column
 * @package ResultSetTable\Columns
 */
abstract class Column implements Renderable
{
    use SortValue;
    use Configure;
    use FilterValue;
    use QueryString;
    use Tokenize;

    /**
     * @var
     */
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
        'formatter',
        'css',
        'sortable',
        'footer',
    ];


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
     * @var Formatter|\Closure
     */
    protected $formatter;

    /**
     * @var string
     */
    protected $header;

    /**
     * @var string
     */
    protected $footer;

    /**
     * @var
     */
    protected $css = 'rst-column';

    /**
     * Column constructor.
     * @param array $configurableOptions
     */
    public function __construct( array $configurableOptions = [ ] )
    {
        $this->configure( $configurableOptions );
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
        if( isset( $this->value ) ) {
            return $this->fetchDataFromValue();
        }

        if( is_array( $this->dataSource ) ) {
            return array_get( $this->dataSource, $this->name );
        }

        if( is_object( $this->dataSource ) ) {
            return object_get( $this->dataSource, $this->name );
        }

        return null;
    }

    /**
     * @return string
     */
    protected function fetchDataFromValue()
    {
        if( $this->value instanceof \Closure ) {
            $f = $this->value;

            return $f( $this->dataSource );
        }

        if( is_scalar( $this->value ) && strpos( $this->value, '{' ) !== false) {
            $this->createTokens( $this->dataSource );

            return $this->replace( $this->value );
        }

        return $this->value;
    }

    /**
     * @return string
     */
    public function render()
    {
        // format the value
        $value = $this->getValue();

        if( isset( $this->formatter ) ) {
            $value = $this->formatString( $value );
        }

        return $this->raw ? $value : e( $value );
    }

    /**
     * @param $string
     * @return string
     */
    protected function formatString( $string )
    {
        if( $this->formatter instanceof \Closure ) {
            $f = $this->formatter;

            return $f( $string );
        }

        if( $this->formatter instanceof Formatter ) {
            return $this->formatter->format( $string );
        }

        return $string;
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        $header = $this->header;

        if( empty( $this->header ) ) {
            $header = ucwords( str_replace( '_', ' ', $this->name ) );
        }

        // now we have a label. If sorting is turned on then we need to return
        // a header with the sorting link
        if( $this->sortable ) {
            return $this->createSortableLink( $header );
        }

        return $header;
    }

    /**
     * @param \Closure|Formatter $formatter
     */
    public function setFormatter( $formatter )
    {
        $this->formatter = $formatter;
    }

    /**
     * @return \Closure|Formatter
     */
    public function getFormatter()
    {
        return $this->formatter;
    }

    /**
     * @return boolean
     */
    public function isRaw()
    {
        return $this->raw;
    }

    /**
     * @param boolean $raw
     */
    public function setRaw( $raw )
    {
        $this->raw = $raw;
    }

    /**
     * @return mixed
     */
    public function getCss()
    {
        return $this->css;
    }

    /**
     * @param mixed $css
     */
    public function setCss( $css )
    {
        $this->css = $css;
    }

    /**
     * @return string
     */
    public function getFooter()
    {
        return $this->footer;
    }
}