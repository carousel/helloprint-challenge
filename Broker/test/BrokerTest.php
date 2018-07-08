<?php

use PHPUnit\Framework\TestCase;
use App\Validator;

class BrokerTest extends TestCase
{
        
    /**
     * @test
     */
    public function testPasswordFieldIsEmpty()
    {
        $input = '{"username":"miroslav.trninic@gmail.com","password":"","type":"login"}';
        $validator = new Validator('hello');
        $validator->format($input);
        $validator->validate();
        $this->assertContains('password field is required',$validator->errors);
    }
    /**
     * @test
     */
    public function testValidationPasses()
    {
        $input = '{"username":"carousel","password":"password","type":"login"}';
        $validator = new Validator('hello');
        $validator->format($input);
        $validator->validate();
        $this->assertTrue(empty($validator->errors));
    }
    /**
     * @test
     */
    public function testIfMoreThenOneFieldIsEmpty()
    {
        $input = '{"username":"","password":"","type":"login"}';
        $validator = new Validator($input);
        $validator->format($input);
        $validator->validate();
        $this->assertContains('password field is required',$validator->errors);
        $this->assertContains('username field is required',$validator->errors);
    }

}

