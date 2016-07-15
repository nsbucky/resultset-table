<?php
/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/13/2016
 * Time: 1:16 PM
 */

namespace ResultSetTable\Buttons;


/**
 * Class Link
 * @package ResultSetTable\Buttons
 */
class Link extends Button
{

    /**
     * Link constructor.
     */
    public function __construct( $url, $label = 'Go', array $configurableOptions = [ ] )
    {
        $configurableOptions['url']   = $url;
        $configurableOptions['label'] = $label;

        parent::__construct( $configurableOptions );
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return sprintf( '<a href="%s" class="%s" %s>%s</a>',
            $this->getUrl(),
            $this->getCss(),
            $this->getConfirm(),
            $this->getLabel() );
    }

}