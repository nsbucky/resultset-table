<?php
/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/15/2016
 * Time: 8:29 AM
 */

namespace ResultSetTable\Columns;


/**
 * Class VarDump
 * @package ResultSetTable\Columns
 */
class VarDump extends Column
{
    /**
     * @var bool
     */
    protected $raw = true;

    /**
     * @return string
     */
    public function getValue()
    {
        $value = $this->fetchRawValueFromDataSource();

        // see if it is serializable
        if( strpos($value, 'a:') !== false ) {
            $ser = @unserialize( $value );

            if( is_array( $ser )) {
                $value = $ser;
            }
        }

        // detect if string is json
        if( $this->$this->isJSON($value)) {
            $value = json_decode($value, true);
        }

        if( is_array( $value )) {
            $value = print_r($value, 1);
        }

        return '<pre>'.wordwrap( e($value), 125 ).'</pre>';
    }

    /**
     * @param $string
     * @return bool
     */
    protected function isJSON( $string){
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }

}