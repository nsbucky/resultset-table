<?php
/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/11/2016
 * Time: 10:20 AM
 */

namespace ResultSetTable\Formats;


use ResultSetTable\Formatter;

class Link implements Formatter
{
    public function format( $value )
    {
        return sprintf( '<a href="%s">%s</a>', $value, $value );
    }

}