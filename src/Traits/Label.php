<?php
/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/15/2016
 * Time: 11:02 AM
 */

namespace ResultSetTable\Traits;


use Assert\Assertion;

/**
 * Class Label
 * @package ResultSetTable\Traits
 */
trait Label
{
    /**
     * @var
     */
    protected $label;

    /**
     * @return string
     */
    public function getLabel($dataSource, $label)
    {
        if( $this->label instanceof \Closure ) {
            $label = $this->label;

            return $label( $dataSource );
        }

        if( is_scalar($this->label) ) {
            return $this->label;
        }

        Assertion::nullOrScalar($label);

        return $label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel( $label )
    {
        $this->label = $label;
    }

}