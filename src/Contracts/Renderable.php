<?php
/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/11/2016
 * Time: 9:53 AM
 */

namespace ResultSetTable\Contracts;


/**
 * Interface Renderable
 * @package ResultSetTable\Contracts
 */
interface Renderable
{
    /**
     * @return string
     */
    public function render();
}