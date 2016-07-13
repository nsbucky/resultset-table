<?php
/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/13/2016
 * Time: 12:38 PM
 */

namespace ResultSetTable\Formats;


use ResultSetTable\Contracts\Formatter;

class Money implements Formatter
{
    public $formatString = '%.2n';

    public $locale = 'en_US.UTF-8';

    public function format( $value )
    {
        setlocale(LC_MONETARY, $this->locale);
        
        return money_format( $this->formatString, $value );
    }

}