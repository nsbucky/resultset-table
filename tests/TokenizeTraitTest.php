<?php

/**
 * Created by PhpStorm.
 * User: Kenrick
 * Date: 7/11/2016
 * Time: 8:55 AM
 */
class TestTokenizeTrait extends PHPUnit_Framework_TestCase
{
    /**
     * @var object
     */
    private $traitObject;

    /**
     * @return object
     */
    public function setUp()
    {
        $this->traitObject = $this->getObjectForTrait( 'ResultSetTable\Traits\Tokenize');
    }

    public function testCreateTokensAddsDelimiter()
    {
        $data = [
            'first_name' => 'test'
        ];

        $this->traitObject->createTokens($data);

        $tokens = $this->traitObject->getTokens();

        $this->assertArrayHasKey('{first_name}', $tokens);
    }

    public function testCreateTokensMultiLevel()
    {
        $data = [
            'first' => [
                'second' => 'test'
            ],
            'third' => [
                'fourth' => [
                    'fifth' => 'test'
                ]
            ]
        ];

        $this->traitObject->createTokens($data);

        $tokens = $this->traitObject->getTokens();

        $this->assertArrayHasKey('{first.second}', $tokens);

        $this->assertEquals('test', $tokens['{first.second}']);

        $this->assertArrayHasKey('{third.fourth.fifth}', $tokens);

        $this->assertEquals('test', $tokens['{third.fourth.fifth}']);
    }

    public function testCreateTokensNoObjects()
    {
        $data = [
            'first_name' => 'test',
            'object' => new stdClass()
        ];

        $this->traitObject->createTokens($data);

        $tokens = $this->traitObject->getTokens();

        $this->assertArrayNotHasKey('object', $tokens);
    }

    public function testCreateTokensFromArrayable()
    {
        $data = new MyArrayable();

        $this->traitObject->createTokens($data);

        $tokens = $this->traitObject->getTokens();

        $this->assertArrayHasKey('{test}', $tokens);
        
        $this->assertEquals('yes',$tokens['{test}']);
    }
    
    public function testReplaceTokens()
    {
        $data = new MyArrayable();

        $this->traitObject->createTokens($data);

        $this->traitObject->getTokens();

        $string = 'My answer is {test}';
        
        $expected = 'My answer is yes';
        
        $test = $this->traitObject->replace($string);
        
        $this->assertEquals( $expected, $test );
    }
}

class MyArrayable implements \Illuminate\Contracts\Support\Arrayable
{
    public function toArray()
    {
        return [
            'test'=>'yes'
        ];
    }
}
