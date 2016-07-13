<?php

/**
 * Created by PhpStorm.
 * User: Valued Customer
 * Date: 7/13/2016
 * Time: 1:08 PM
 */
class ButtonTest extends PHPUnit_Framework_TestCase
{
    public function testReplaceLabel()
    {
        $button = new \ResultSetTable\Buttons\Action([
            'label'=>'{test}',
            'url'=>'index.php'
        ]);

        $button->setDataSource( [
            'test'=>'balls'
        ] );
        
        $value = $button->render();

        $expected = '<button class="'.$button->getCss().'" >balls</button>';

        $this->assertEquals( $expected, $value );
    }

    public function testConfirm()
    {
        $button = new \ResultSetTable\Buttons\Action([
            'label'=>'{test}',
            'url'=>'index.php',
            'confirm'=>'Are you sure?',
        ]);

        $button->setDataSource( [
            'test'=>'balls',
        ] );

        $value = $button->render();

        $expected = '<button class="'.$button->getCss().'" onclick="return confirm(\'Are you sure?\');">balls</button>';

        $this->assertEquals( $expected, $value );
    }

    public function testLinkButton()
    {
        $button = new \ResultSetTable\Buttons\Link([
            'label'=>'{test}',
            'url'=>'index.php',
            'confirm'=>'Are you sure?',
        ]);

        $button->setDataSource( [
            'test'=>'balls',
        ] );

        $value = $button->render();

        $expected = '<a href="index.php" class="'.$button->getCss().'" onclick="return confirm(\'Are you sure?\');">balls</a>';

        $this->assertEquals( $expected, $value );
    }
}
