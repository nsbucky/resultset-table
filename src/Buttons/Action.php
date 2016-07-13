<?php
/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/13/2016
 * Time: 11:27 AM
 */

namespace ResultSetTable\Buttons;


class Action extends Button
{
    public function getValue()
    {
        return sprintf('<button class="%s" %s>%s</button>', $this->getCss(), $this->getConfirm(), $this->getLabel());
    }

}