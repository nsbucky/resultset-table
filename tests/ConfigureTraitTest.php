<?php

/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/11/2016
 * Time: 9:25 AM
 */
class ConfigureTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var MyConfigure
     */
    private $configurable;

    public function setUp()
    {
        $this->configurable = new MyConfigure();
    }

    public function testConfigure()
    {
        $this->configurable->configure([
            'allow' =>false
        ]);

        $this->assertFalse( $this->configurable->getAllow() );
    }
}

class MyConfigure
{
    use \ResultSetTable\Traits\Configure;

    protected $configurableOptions = ['allow'];

    protected $allow = true;

    public function getAllow()
    {
        return $this->allow;
    }

}
