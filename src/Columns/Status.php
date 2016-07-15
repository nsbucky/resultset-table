<?php
/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/15/2016
 * Time: 8:32 AM
 */

namespace ResultSetTable\Columns;


class Status extends BooleanValue
{
    /**
     * @var string
     */
    protected $trueLabel = 'Active';

    /**
     * @var string
     */
    protected $falseLabel = 'Inactive';

    /**
     * @var array
     */
    protected $trueValues = [ '1', 'on', 1 ];

    /**
     * @var array
     */
    protected $falseValues = [ '0', 'off', 'null', 0 ];

    /**
     * @var array
     */
    protected $filter = [ '' => '', '0' => 'Inactive', '1' => 'Active' ];


}