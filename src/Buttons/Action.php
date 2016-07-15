<?php
/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/13/2016
 * Time: 11:27 AM
 */

namespace ResultSetTable\Buttons;


/**
 * Class Action
 * @package ResultSetTable\Buttons
 */
class Action extends Button
{

    /**
     * @return string
     */
    public function getValue()
    {
        return sprintf('<button class="%s" %s>%s</button>', $this->getCss(), $this->getConfirm(), $this->getLabel());
    }

}