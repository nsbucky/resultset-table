<?php
/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/15/2016
 * Time: 11:18 AM
 */

namespace ResultSetTable\Traits;


use Assert\Assertion;

trait Content
{
    protected $content;

    public function getContent($dataSource)
    {
        if( $this->content instanceof \Closure ) {
            $func = $this->content;

            return $func($dataSource);
        }

        Assertion::nullOrScalar($this->content);

        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }
}