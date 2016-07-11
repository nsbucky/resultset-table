<?php
/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/11/2016
 * Time: 7:59 AM
 */

namespace ResultSetTable;


interface Formatter
{
    /**
     * @param string $value
     * @return string
     */
    public function format( $value );
}