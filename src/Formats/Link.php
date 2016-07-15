<?php
/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/11/2016
 * Time: 10:20 AM
 */

namespace ResultSetTable\Formats;


use ResultSetTable\Contracts\Formatter;

/**
 * Class Link
 * @package ResultSetTable\Formats
 */
class Link implements Formatter
{
    /**
     * @var string
     */
    public $label;

    /**
     * @param string $value
     * @return string
     */
    public function format( $value )
    {
        $label = $value;

        if( isset($this->label)){
            $label = $this->label;
        }

        return sprintf( '<a href="%s">%s</a>', $value, $label );
    }

}