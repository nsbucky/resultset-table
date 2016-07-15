<?php
/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/15/2016
 * Time: 8:31 AM
 */

namespace ResultSetTable\Columns;


/**
 * Class Raw
 * @package ResultSetTable\Columns
 */
class Raw extends Column
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
        return $this->fetchRawValueFromDataSource();
    }

}