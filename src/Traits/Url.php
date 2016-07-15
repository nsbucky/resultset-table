<?php
/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/15/2016
 * Time: 11:01 AM
 */

namespace ResultSetTable\Traits;


use Assert\Assertion;

/**
 * Class Url
 * @package ResultSetTable\Traits
 */
trait Url
{
    /**
     * @var
     */
    protected $url;

    /**
     * @return string
     */
    public function getUrl( $dataSource )
    {
        $url = $this->url;

        if( $url instanceof \Closure ) {
            return $url( $dataSource );
        }

        Assertion::nullOrScalar( $url );

        return $url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl( $url )
    {
        $this->url = $url;
    }

}