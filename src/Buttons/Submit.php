<?php
/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/15/2016
 * Time: 7:20 AM
 */

namespace ResultSetTable\Buttons;


use Assert\Assertion;

/**
 * Class Submit
 * @package ResultSetTable\Buttons
 */
class Submit extends Button
{
    /**
     * @var array
     */
    protected $hiddenFields = [];

    /**
     * Submit constructor.
     * @param array $url
     * @param string $label
     * @param string $method
     * @param array $configurableOptions
     */
    public function __construct( $url, $label = 'Submit', $method = 'post', array $configurableOptions = [ ] )
    {
        Assertion::string($method);

        $configurableOptions['url']    = $url;
        $configurableOptions['label']  = $label;
        $configurableOptions['method'] = $method;

        $this->configurableOptions[] = 'hiddenFields';

        if( array_key_exists('hiddenFields', $configurableOptions) ){

            Assertion::isArray($configurableOptions['hiddenFields']);

        }

        parent::__construct( $configurableOptions );

        // add csrf token ?
        if( function_exists('csrf_token')) {
            $this->hiddenFields['_token'] = csrf_token();
        }
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return sprintf(
            '<form action="%s" method="%s" class="form-inline">
                <button type="submit" class="%s" title="Delete" %s>%s</button>				
				%s
			</form>',
            $this->getUrl(),
            $this->getMethod(),
            $this->getCss(),
            $this->getConfirm(),
            $this->getLabel(),
            $this->buildHiddenFields()
        );
    }

    /**
     * @return string|void
     */
    protected function buildHiddenFields()
    {
        if( count($this->hiddenFields) < 1 ) {
            return;
        }

        $html = [];

        foreach( $this->hiddenFields as $key => $value ) {
            $html[] = sprintf('<input type="hidden" name="%s" value="%s">', $key, $value);
        }

        return implode(PHP_EOL, $html);
    }

}