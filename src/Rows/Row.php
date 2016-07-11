<?php
/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/11/2016
 * Time: 10:04 AM
 */

namespace ResultSetTable\Rows;


use Assert\Assertion;

abstract class Row
{
    /**
     * @var \Closure
     */
    protected $callback;

    /**
     * @var mixed
     */
    protected $dataSource;

    /**
     * Row constructor.
     * @param $dataSource
     */
    public function __construct( $callback )
    {
        Assertion::isInstanceOf('Closure', $callback);

        $this->callback = $callback;
    }

    protected function setDataSource( $dataSource )
    {
        $func = $this->callback;

        $this->dataSource = $func($dataSource);
    }

    /**
     * @return string
     */
    abstract public function getValue();
}