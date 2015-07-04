# calculator
A PHPUnit Unit Testing Demo:

Lives on: https://github.com/babak51/calculator

The Demo would cover the following areas:
1) Given a PHP class and its functions we would create some unit tests and execute them using PHPUnit.
2) We would cover phpunit.xml as a configuration file.
3) We would cover Xdebug's Code Coverage analysis and how it could be used to find "dead code" and holes is test coverage.  The aim is to get a %100 coverage.  Great Tool!
4) We would demo the use  of Travis-CI the open-source Continuos  integration service working with Github and testing our PHP class under test.  Very useful.
5) We would also cover PHPUnit Skeleton Generator to generate skeleton of Unit Tests is production.  Useful in test production!

******************************
Part 1+2 of the Demo 
******************************
We are given the task of testing the following PHP class (Calculator.php) which acts as a simple caculator:
<?php
/**
* PHPUnit Unit Testing Demo
* @author Joe Tester
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

To test the Calculator class we would write the CalculatorTest.php
<?php
require dirname(__DIR__) . '/src/Calculator.php';
class CalculatorTest extends PHPUnit_Framework_TestCase
{
  private $calculator;

  public function setUp()
  {
    $this->calculator = new Calculator();
  }

  public function testAddition()
  {
    $this->assertEquals($this->calculator->add(16,14), 30, 'Error! The addition operation is broken.');
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
    $this->assertEquals($this->calculator->divide(24,2), 12, 'Error! The division operator is broken.');
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

Lets try few simple commands:
~/demo/calculator (master)>phpunit --version
PHPUnit 3.7.38 by Sebastian Bergmann.


~/demo/calculator (master)>phpunit --help | more
PHPUnit 3.7.38 by Sebastian Bergmann.

Usage: phpunit [options] UnitTest [UnitTest.php]
       phpunit [options] <directory>

  --log-junit <file>        Log test execution in JUnit XML format to file.
  --log-tap <file>          Log test execution in TAP format to file.
  --log-json <file>         Log test execution in JSON format.

  --coverage-clover <file>  Generate code coverage report in Clover XML format.
  --coverage-html <dir>     Generate code coverage report in HTML format.
  --coverage-php <file>     Serialize PHP_CodeCoverage object to file.
  --coverage-text=<file>    Generate code coverage report in text format.
                            Default to writing to the standard output.

  --testdox-html <file>     Write agile documentation in HTML format to file.
  --testdox-text <file>     Write agile documentation in Text format to file.

  --filter <pattern>        Filter which tests to run.
  --testsuite <pattern>     Filter which testsuite to run.
  --group ...               Only runs tests from the specified group(s).
  --exclude-group ...       Exclude tests from the specified group(s).
  --list-groups             List available test groups.
  --test-suffix ...         Only search for test in files with specified
                            suffix(es). Default: Test.php,.phpt

  --loader <loader>         TestSuiteLoader implementation to use.
  --printer <printer>       TestSuiteListener implementation to use.
  --repeat <times>          Runs the test(s) repeatedly.

  --tap                     Report test execution progress in TAP format.
  --testdox                 Report test execution progress in TestDox format.

  --colors                  Use colors in output.
  --stderr                  Write to STDERR instead of STDOUT.
  --stop-on-error           Stop execution upon first error.
  --stop-on-failure         Stop execution upon first error or failure.
  --stop-on-skipped         Stop execution upon first skipped test.

  . . . and on and on . . . 

  The best practice is to use a configuration file called phpunit.xml an example of which is listed below:
  <phpunit
      bootstrap="test/bootstrap.php"
      colors="true"
      strict="true"
      verbose="false">
    <testsuites>
      <testsuite name="Demo Test Suite">
        <directory suffix="Test.php">test</directory>
      </testsuite>
    </testsuites>
    <php>
      <includePath>src</includePath>
    </php>
  </phpunit>

  When specifing directories it should be kept in mind that by default, only files in that directory and and child directories with the pattern *Test.php will be loaded. You can change this behavior using suffix.

  We also need to present the bootstrap.php file in the test folder as mentioned in the phpunit.xml file above (same for all your tests):
  <?php
  spl_autoload_register(function ($className) {
    $classPath = str_replace(
      array('_', '\\'),
      DIRECTORY_SEPARATOR,
      $className
  ) . '.php';
  require $classPath;
});
?>

   
This is the related part of my .bash_profile file:
cat .bash_profile
. . . on and on . . .

export PATH="~/myphp/vendor/phpunit/phpunit/composer/bin:$PATH"

As you can see I have been using composer to help me install.

We are ready to test.  Lets test our Calculator class with the tests we got:

try:
~/demo/calculator (master)>phpunit -c phpunit.xml

Or the following which defaults to using the phpunit.xml.  But, lets add --debug option as well:

~/demo/calculator (master)>phpunit --debug
PHPUnit 3.7.38 by Sebastian Bergmann.

Configuration read from /Users/mamiraslani/demo/calculator/phpunit.xml


Starting test 'CalculatorTest::testAddition'.
.
Starting test 'CalculatorTest::testSubtraction'.
.
Starting test 'CalculatorTest::testMultiplication'.
.
Starting test 'CalculatorTest::testDivision'.
.
Starting test 'CalculatorTest::testDivideByZeroInDivision'.
.

Time: 25 ms, Memory: 3.00Mb

OK (5 tests, 5 assertions)
~/demo/calculator (master)>

Lets make a test fail!   Changes expected value of one of the tests and run the Unit Tests again

~/demo/calculator (master)>phpunit > foo.txt
~/demo/calculator (master)>nano foo.txt

Now please fix the test you intentionally broke and remark out the last test so we continue with the next part of the demo.




******************************
Part  3  of the Demo
******************************
Code Coverage analysis:

Issue the following command:
~/demo/calculator (master)>php --version
PHP 5.4.30 (cli) (built: Jul 29 2014 23:43:29)
Copyright (c) 1997-2014 The PHP Group
Zend Engine v2.4.0, Copyright (c) 1998-2014 Zend Technologies
    with Xdebug v2.3.2, Copyright (c) 2002-2015, by Derick Rethans

The PHP cli is a PHP command line interface to run tests from the command line.

A test runner is exactly what it sounds like. It is a way for PHPUnit to run your test suites and output the results.  The default PHPUnit test runner is the command-line runner that is invoked with the phpunit command in your terminal application.

The original PHP Engine is the Zend Engine.  A PHP Engine is a program that parses, interprets, and executes PHP code.

You can also see that we have Xdebug Extension installed.   There are more to read if you follow these steps:

In a folder outside our calculator folder say a ~/myphpsandbox folder create a info.php file with the following content:
<?php
  phpinfo();
?>
Just those 3 lines.  Then run this php program 
~/myphpsandbox>php info.php | more

You would see information and settings for Xdebug along with many others.


What is Xdebug after all?  It is a PHP Extension and it can help with:
- Remote Debugging
- Code Coverage Analysis
- Code Profiling

We would focus on Code Coverage Analysis that Xdebug offers in this demo:

For much more info on Xdebug please visit:
http://xdebug.org/
and for download:
http://xdebug.org/download.php
doc:
http://xdebug.org/docs/code_coverage

Since I am using Composer, I ended up manually installing Xdebug.  Even so, don't use PEAR, PEAR is old.

These are your Code Coverage options (You could see them giving the command "phpunit -h | more"):
  --coverage-clover <file>  Generate code coverage report in Clover XML format.
  --coverage-html <dir>     Generate code coverage report in HTML format.
  --coverage-php <file>     Serialize PHP_CodeCoverage object to file.
  --coverage-text=<file>    Generate code coverage report in text format.
                            Default to writing to the standard output.

In this demo we would use the --coverage-html option.

To generate the Code Coverage reports, from your test folder issue this command:
~/demo/calculator/test (master)>phpunit --coverage-html coverage CalculatorTest.php

This would create a folder called coverage inside the test folder and it would add the reports to it using our CalculatorTest.php file.

Now we are ready to read the Code Coverage Analysis report and inspect it closely.

Please open a browser window, and then open the file (in my case) ~/demo/calculator/test/coverage/index.html

on the left of the screen click in this order:
click on "demo"
click on "calculator"
click on "src"
click on "calculator.php"

Please look at the bottom of the page:

Legend
Executed | Not Executed  | Dead Code
Generated by PHP_CodeCoverage 1.2.18 using PHP 5.4.30 and PHPUnit 3.7.38 at Sat Jul 4 0:16:37 UTC 2015.

"Excecuted" lines are green.  "Not Executed" are in red color.  If you remarked out the last test you would see red lines of code and a less than %100 coverage in your report.  Can you find the yellow Dead code?  

There is another aspect to the report which can help you write better tests.   Please move your mouse over the lines of code.  For example in the divide funtion mouse over "if (!$b)" shows that 2 tests are giving coverage to this line, but for "return $a/$b;" you get just one test.  In a more complicated example with many tests you might end up with with hundreds of tests giving coverage to one segment of the test.  That would be an over-kill, a waste of testing resources.  Code Coverage Analysis gives you a sense of which areas needs to be tested more and which areas don't. The last word on this subject, always aim for %100 coverage. 


**************************************
Part 4 of the Demo: Travis-ci
**************************************

So far we have been launching our Unit Tests from command-line at will, but wouldn't that be great if this process of launching tests was automatic?  What if every time the developer pushed her changes to the repository we would run these tests automatically and verify the new/modified code!  To reach that aim in our testing we would use Travis-ci or a similar service.  Please visit this site:
https://travis-ci.org  <-- public repositories (this demo)
https://travis-ci.com  <-- private    //
and click on PHP as a language choice.  There are many other.

To use the Travis service I had to open a free Travis account and connect it to the Github repository which the calculator project is kept at, but I had to be the admin of the Github repository:
https://github.com/babak51/calculator

You would also need to add a .travis.yml (a hidden file) to the project folder.  Lets examine the .yml file which tells the Travis Service how to test your code:

language: php
php:
  - 5.4
  - 5.5
  - 5.6
  - hhvm

  Remember this is a .yml file and positions matter.
  We are telling Travis to try out the PHP versions 5.4, 5.5, and 5.6.  Remember the PHP version on my PC was 5.4.30.  But I like to see how the code works on other versions as well. 


The last line of the .yml file is "- hhvm".  what is hhvm?  You might ask.   HHVM Works as a self contained web server that executes PHP scripts, replacing Apache and mod-PHP altogether.
    
You can see the results of the builds on the Travis site.  Try changing something in your src/calculator.php file.  I changed the author's first name.  Now using Git commands like git status, git fetch, git pull, git add, git commit, and git push, push your change(s).  A final "git status" command should tell you "nothing to commit, working directory clean".  You can inspect the repository and see if your change(s) have been commited to the master branch (in our simple example).

This also should trigger 4 builds on Travis.  Remember that Travis and Github are connected to each other through the hook(s) as you established your Travis account.

Do you see the test results on the Travis site?  The 4 builds should all pass, you should see 4 green checkmarks.  Of course there is much Travis can do for you which we do not cover in this demo.  But we need to move on to the next topic in the demo.



**********************************************
Part 5 of the Demo: PHPUnit Skeleton Generator
**********************************************

If you have many lines of legacy PHP code and you want to great Unit Tests for them you would find that much of your time is being spent typing or cut/pasting some of the lines the test script which could have been generated for you.  You might also like to see a standard in you test authoring.  That is where the PHPUnit Skeleton Generator comes to play.  Create a new foder called workingSkelgenExample.  I created it as:
~/myphpsandbox/workingSkelgenExample>
Now in this new folder create a Calculator.php file which contains an even simpler version of our simple calculator class:
<?php
class Calculator
{
    public function add($a, $b)
    {
        return  $a +  $b;
    }
}
?>

All this calculator can do is to add!  Lets try to genrate a Skeleton Unit Test for this class.  Type:
phpunit-skelgen generate-test Calculator

A new CalculatorTest.php would be created which looks like:
<?php
/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-07-04 at 02:49:51.
 */
class CalculatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Calculator
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Calculator;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Calculator::add
     * @todo   Implement testAdd().
     */
    public function testAdd()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}

?>

Of course you still need to take this generated CalculatorTest.php and modify it to you testing needs but some of the leg work is already done for you which would save you time and avoid mistakes.  



~>phpunit-skelgen --help
phpunit-skelgen 2.0.1 by Sebastian Bergmann.

Usage:
 help [--xml] [--format="..."] [--raw] [command_name]

Arguments:
 command               The command to execute
 command_name          The command name (default: "help")

Options:
 --xml                 To output help as XML
 --format              To output help in other formats (default: "txt")
 --raw                 To output raw command help
 --help (-h)           Display this help message.
 --quiet (-q)          Do not output any message.
 --verbose (-v|vv|vvv) Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
 --version (-V)        Display this application version.
 --ansi                Force ANSI output.
 --no-ansi             Disable ANSI output.
 --no-interaction (-n) Do not ask any interactive question.

Help:
 The help command displays help for a given command:

   php /usr/local/bin/phpunit-skelgen help list

 You can also output the help in other formats by using the --format option:

   php /usr/local/bin/phpunit-skelgen help --format=xml list

 To display the list of available commands, please use the list command.
~>


~>phpunit-skelgen --list
phpunit-skelgen 2.0.1 by Sebastian Bergmann.

phpunit-skelgen version 2.0.1

Usage:
  [options] command [arguments]

Options:
  --help           -h Display this help message.
  --quiet          -q Do not output any message.
  --verbose        -v|vv|vvv Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
  --version        -V Display this application version.
  --ansi              Force ANSI output.
  --no-ansi           Disable ANSI output.
  --no-interaction -n Do not ask any interactive question.

Available commands:
  generate-class   Generate a class based on a test class
  generate-test    Generate a test class based on a class
  help             Displays help for a command
  list             Lists commands
~>




I hope this demo helps you with your Unit Testing.

-Best














   



