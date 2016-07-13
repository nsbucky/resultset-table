<?php
/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/13/2016
 * Time: 1:16 PM
 */

namespace ResultSetTable\Buttons;


class Link extends Button
{
    public function getValue()
    {
        return sprintf('<a href="%s" class="%s" %s>%s</a>',
            $this->getUrl(), 
            $this->getCss(), 
            $this->getConfirm(), 
            $this->getLabel());
    }

}