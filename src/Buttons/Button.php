<?php
/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/11/2016
 * Time: 10:00 AM
 */

namespace ResultSetTable\Buttons;


use ResultSetTable\Contracts\Renderable;
use ResultSetTable\Traits\Configure;
use ResultSetTable\Traits\Tokenize;

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
        'url'
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

        if( strpos( $url, '{' ) !== false ) {

            $this->createTokens( $this->dataSource );
            return $this->replace( $url );

        }

        return $url;
    }
}