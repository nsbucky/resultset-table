<?php
/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/13/2016
 * Time: 12:38 PM
 */

namespace ResultSetTable\Formats;


use ResultSetTable\Contracts\Formatter;

/**
 * Class Money
 * @package ResultSetTable\Formats
 */
class Money implements Formatter
{
    /**
     * @var string
     */
    public $formatString = '%.2n';

    /**
     * @var string
     */
    public $locale = 'en_US.UTF-8';

    /**
     * @param string $value
     * @return string
     */
    public function format( $value )
    {
        if( function_exists('money_format')) {
            setlocale(LC_MONETARY, $this->locale);

            return money_format( $this->formatString, $value );    
        }
        
        return sprintf('$%1.2f', $value);
    }

}