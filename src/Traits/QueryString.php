<?php
/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/12/2016
 * Time: 6:45 AM
 */

namespace ResultSetTable\Traits;


/**
 * Class QueryString
 * @package ResultSetTable\Traits
 */
trait QueryString
{
    /**
     * @var
     */
    protected $input;

    /**
     * @return mixed
     */
    public function getInput()
    {
        if( isset($this->input)) {
            return $this->input;
        }
        
        $this->input = $_GET;
    }

    /**
     * @param array $input
     */
    public function setInput( array $input)
    {
        $this->input = $input;
    }
}