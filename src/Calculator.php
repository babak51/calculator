<?php
/**
* PHPUnit Unit Testing Demo
* @author Jack Tester
*/
class Calculator
{
    public function add($a, $b)
    {
        return $a + $b;
    }

    public function subtract($a, $b)
    {
    	return $a - $b;
    }

    public function multiply($a, $b)
    {
    	return $a * $b;
        echo "This is a line of Dead code.";
    }

    public function divide($a, $b)
    {
      if (!$b) 
      {
        throw new Exception('Division by zero.');
      }
      return $a/$b;
    }
}
?>
