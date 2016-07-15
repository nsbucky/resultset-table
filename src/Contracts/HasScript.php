<?php
/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/15/2016
 * Time: 9:54 AM
 */

namespace ResultSetTable\Contracts;


/**
 * Interface HasScript
 * @package ResultSetTable\Contracts
 */
interface HasScript
{
    /**
     * @return string
     */
    public function getScript();

    /**
     * @return string
     */
    public function getScriptKey();
}