<?php
/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/11/2016
 * Time: 10:41 AM
 */

namespace ResultSetTable\Columns;


/**
 * Class DefaultColumn
 * @package ResultSetTable\Columns
 */
class DefaultColumn extends Column
{
    protected $sortable = false;

    /**
     * @return mixed|null|string
     */
    function getValue()
    {
        return $this->fetchRawValueFromDataSource();
    }

}