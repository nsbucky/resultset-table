<?php
/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/11/2016
 * Time: 10:00 AM
 */

namespace ResultSetTable\Buttons;


use Assert\Assertion;
use ResultSetTable\Contracts\Renderable;
use ResultSetTable\Traits\Configure;
use ResultSetTable\Traits\Tokenize;

/**
 * Class Button
 * @package ResultSetTable\Buttons
 */
abstract class Button implements Renderable
{
    use Configure;
    use Tokenize;

    /**
     * @var mixed
     */
    protected $dataSource;


    /**
     * @var array
     */
    protected $configurableOptions = [
        'visible',
        'name',
        'label',
        'confirm',
        'css',
        'url',
        'method',
    ];

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var bool
     */
    protected $confirm = false;

    /**
     * @var string
     */
    protected $css = 'btn btn-xs btn-default';

    /**
     * @var bool
     */
    protected $visible = true;

    /**
     * @return string
     */
    abstract public function getValue();

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
     * @return string
     */
    public function getLabel()
    {
        $label = $this->label;

        if( $label instanceof \Closure ) {
            $label = $label($this->dataSource);
        }

        Assertion::scalar($label);

        if( strpos( $label, '{' ) !== false ) {

            $this->createTokens( $this->dataSource );
            return $this->replace( $label );

        }
        
        return $label;
    }

    /**
     * @return string
     */
    public function getCss()
    {
        return $this->css;
    }

    /**
     * @return boolean
     */
    public function isVisible()
    {
        return $this->visible;
    }

    /**
     * @return string|void
     */
    public function getConfirm()
    {
        if( !$this->confirm ) {
            return;
        }

        return sprintf( 'onclick="return confirm(\'%s\');"', $this->confirm );
    }

    /**
     * @return string
     */
    public function render()
    {
        return $this->getValue();
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $url = $this->url;

        if( $url instanceof \Closure ) {
            $url = $url($this->dataSource);
        }

        Assertion::scalar($url);

        if( strpos( $url, '{' ) !== false ) {

            $this->createTokens( $this->dataSource );
            return $this->replace( $url );

        }

        return $url;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod( $method )
    {
        $this->method = $method;
    }

}