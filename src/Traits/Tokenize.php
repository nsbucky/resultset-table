<?php
/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/11/2016
 * Time: 8:07 AM
 */

namespace ResultSetTable\Traits;

use Assert\Assertion;

/**
 * Class Tokenize
 * @package ResultSetTable\Traits
 */
trait Tokenize
{
    /**
     * @var array
     */
    protected $tokens;

    /**
     * create an array of tokens to be used in various column functions. if the
     * data passed is an object, it will first see if the object has a method called
     * isArray() to create an array from the object, otherwise it will cast the object
     * to an array and hope for the best
     * @param $data
     */
    public function createTokens( $data )
    {
        if( isset( $this->tokens )) {
            return $this->tokens;
        }
        
        if( is_object( $data ) && method_exists( $data, 'toArray' ) ) {
            
            $data = $data->toArray();
            
        }
        
        Assertion::isArray($data);

        // drop any object members.
        $data = array_filter($data, function($v ){

            return ! is_object($v);
            
        });

        // allow for tokens to access multi-dimensional arrays via dot syntax
        $data = array_dot( $data );

        foreach( $data as $key => $value ) {
            
            $this->tokens['{' . $key . '}'] = $value;
            
        }
    }

    /**
     * replace any tokens found in string with tokens
     * @param string $string
     * @return string
     */
    public function replace( $string )
    {
        Assertion::scalar($string);
        
        // token delimiter not found, just return the string
        if( strpos( $string, '{' ) === false ) {

            return $string;

        }

        $tokens = $this->getTokens();

        return str_replace( array_keys( $tokens ), array_values( $tokens ), $string );
    }

    /**
     * @return array
     */
    public function getTokens()
    {
        return $this->tokens;
    }

}