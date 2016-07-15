<?php
/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/15/2016
 * Time: 8:31 AM
 */

namespace ResultSetTable\Columns;


class Pre extends Column
{
    protected $raw = true;

    public function getValue()
    {
        $value = $this->fetchRawValueFromDataSource();

        return sprintf( '<pre>%s</pre>', e( $value ) );
    }

}