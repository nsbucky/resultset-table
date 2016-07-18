<?php
/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/11/2016
 * Time: 10:16 AM
 */

namespace ResultSetTable\Decorators;


use ResultSetTable\Contracts\Renderable;
use ResultSetTable\Table;

/**
 * Class Decorator
 * @package ResultSetTable\Decorators
 */
abstract class Decorator implements Renderable
{
    /**
     * @var Table
     */
    protected $table;

    /**
     * Decorator constructor.
     * @param Table $table
     */
    public function __construct( Table $table )
    {
        $this->table = $table;
    }
    
    /**
     * @return string
     */
    abstract protected function decorate();

    /**
     * @return string
     */
    public function render()
    {
        return $this->decorate();
    }
}