<?php
/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/12/2016
 * Time: 6:45 AM
 */

namespace ResultSetTable\Traits;


trait QueryString
{
    protected $input;
    
    public function getInput()
    {
        if( isset($this->input)) {
            return $this->input;
        }
        
        $this->input = $_GET;
    }
    
    public function setInput( array $input)
    {
        $this->input = $input;
    }
}