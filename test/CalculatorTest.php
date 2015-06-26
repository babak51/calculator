<?php
require dirname(__DIR__) . '/src/Calculator.php';

class CalculatorTest extends PHPUnit_Framework_TestCase{

  private $calculator;


  public function setUp(){
  	$this->calculator = new Calculator();
  }

  public function testAddition()
  {
    $this->assertEquals($this->calculator->add(16,14), 30, 'Error!  The addition operation is broken.');
  }

  
  public function testSubtraction()
  {
    $this->assertEquals($this->calculator->subtract(56,25), 31, 'Error! The subtraction operation is broken.');
  }

  public function testMultiplication()
  {
  	$this->assertEquals($this->calculator->multiply(21,3), 63, 'Error! The multiplication operation is broken.');
  }

  public function testDivision()
  {
  	$this->assertEquals($this->calculator->divide(24,2), 12, 'Error!  The division operator is broken.');
  }

  /**
   * @expectedException Exception
   */
  public function testDivideByZeroInDivision()
  {
     $this->calculator->divide(24,0);
  }
}
?>
