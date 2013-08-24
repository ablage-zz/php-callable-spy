<?php

require_once __DIR__."/../vendor/autoload.php";

/**
 * Stub class for testing callables
 */
class CallableStub {
    /**
     * Static call
     *
     * @param string $a
     * @param string $b
     * @return string
     */
    public static function staticCall($a, $b) {
        return $a.$b;
    }

    /**
     * Dynamic call
     *
     * @param mixed $a
     * @return mixed
     */
    public function dynamicCall($a) {
        return $a + 3;
    }
}

/**
 * Child-class testing relative static method calls
 */
class CallableStubChild extends CallableStub {
    /**
     * Overwritten static call
     *
     * @param string $a
     * @param string $b
     * @return string
     */
    public static function staticCall($a, $b) {
        return $a.'-'.$b;
    }

}

/**
 * Stub function for function callable
 *
 * @param mixed $a
 * @param mixed $b
 * @return mixed
 */
function stubFn($a, $b) {
    return $a * $b;
}

class CallableTest extends PHPUnit_Framework_TestCase
{
    /**
     * Verifies spy
     *
     * @param \CallableSpy\Spy $spy
     * @param boolean $wasCalled
     * @param int $callCount
     */
    protected function _verifySpy($spy, $wasCalled, $callCount) {

        $this->assertNotNull($spy, 'Spy was returned as NULL');
        $this->assertInstanceOf('\CallableSpy\Spy', $spy, 'Spy is not of the right type');

        $this->assertEquals($wasCalled, $spy->wasCalled(), 'Call determination is not correct');
        $this->assertEquals($callCount, $spy->getCallCount(), 'Call count is not correct');
    }

    /**
     * Verifies call
     *
     * @param \CallableSpy\Call $call
     * @param array $args
     * @param mixed $result
     */
    protected function _verifyCall($call, $args, $result) {

        $this->assertNotNull($call, 'Call was returned as NULL');
        $this->assertInstanceOf('\CallableSpy\Call', $call, 'Call is not of the right type');

        $this->assertInstanceOf('DateTime', $call->getDate(), 'Call timestamp is not of right type');

        $this->assertEquals(count($args), count($call->getArgs()), 'Call argument count is not correct');
        $this->assertEquals($args, $call->getArgs(), 'Call arguments are not correct');

        $this->assertEquals($result, $call->getResult(), 'Call count is not correct');

        $this->assertNotNull($call->getStackTrace(), 'Stack-trace is NULL');
    }


    /**
     * Tests call class
     */
    public function testCall() {
        $args = array(1, 2);
        $result = 55;
        $timestamp = new DateTime();
        $stackTraceContext = 'StackTraceContext'; // Can be anything

        // Exercise
        $call = new \CallableSpy\Call($args, $result, $timestamp, $stackTraceContext);

        // Verify
        $this->_verifyCall($call, $args, $result);
        $this->assertEquals($stackTraceContext, $call->getStackTrace(), 'Call stack-trace was not correctly saved upon initialization');
    }


    /**
     * Function test data-provider
     *
     * @return array
     */
    public function functionDataProvider() {
        return array(
            array(5, 3, 15),
            array(7, 0, 0)
        );
    }

    /**
     * Tests functions
     *
     * Type 1: Simple callback
     *
     * @param mixed $a
     * @param mixed $b
     * @param mixed $expectedResult
     * @dataProvider functionDataProvider
     */
    public function testFunction($a, $b, $expectedResult)
    {
        // Setup
        $callable = 'stubFn';
        $callable = new \CallableSpy\Spy($callable);

        // Exercise
        $result = $callable($a, $b);

        // Verify
        $this->_verifySpy($callable, true, 1);
        $calls = $callable->getCalls();
        $this->_verifyCall($calls[0], array($a, $b), $expectedResult);
    }

    /**
     * Tests last call the same as last call in calls list - with one call
     *
     * @param mixed $a
     * @param mixed $b
     * @param mixed $expectedResult
     * @dataProvider functionDataProvider
     */
    public function testLastCallWithOneCall($a, $b, $expectedResult)
    {
        // Setup
        $callable = new \CallableSpy\Spy('stubFn');

        // Exercise
        $result = $callable($a, $b);

        // Verify
        $calls = $callable->getCalls();
        $this->_verifyCall($calls[0], array($a, $b), $expectedResult);

        $this->assertEquals($calls[0], $callable->getLastCall(), 'Last call was not chosen correctly');
    }


    /**
     * Static class method test data-provider
     *
     * @return array
     */
    public function staticMethodDataProvider() {
        return array(
            array('foo', 'bar', 'foo-bar'),
            array('baz', 'spam', 'baz-spam')
        );
    }

    /**
     * Tests static class method calls with array
     *
     * Type 2.1: Static class method - Array
     *
     * @param mixed $a
     * @param mixed $b
     * @param mixed $expectedResult
     * @dataProvider staticMethodDataProvider
     */
    public function testStaticMethodOption1($a, $b, $expectedResult)
    {
        // Setup
        $callable = array('CallableStubChild', 'staticCall');
        $callable = new \CallableSpy\Spy($callable);

        // Exercise
        $result = $callable($a, $b);

        // Verify
        $this->_verifySpy($callable, true, 1);
        $this->_verifyCall($callable->getLastCall(), array($a, $b), $expectedResult);
    }

    /**
     * Tests static class method calls with string
     *
     * Type 2.2: Static class method - String (As of PHP 5.2.3)
     *
     * @param mixed $a
     * @param mixed $b
     * @param mixed $expectedResult
     * @dataProvider staticMethodDataProvider
     */
    public function testStaticMethodOption2($a, $b, $expectedResult)
    {
        // Setup
        $callable = 'CallableStubChild::staticCall';
        $callable = new \CallableSpy\Spy($callable);

        // Exercise
        $result = $callable($a, $b);

        // Verify
        $this->_verifySpy($callable, true, 1);
        $this->_verifyCall($callable->getLastCall(), array($a, $b), $expectedResult);
    }


    /**
     * Object method test data-provider
     *
     * @return array
     */
    public function objectMethodDataProvider() {
        return array(
            array(5, 8),
            array(0, 3)
        );
    }

    /**
     * Tests object method calls
     *
     * Type 3: Object method
     *
     * @param mixed $a
     * @param mixed $expectedResult
     * @dataProvider objectMethodDataProvider
     */
    public function testObjectMethod($a, $expectedResult)
    {
        // Setup
        $obj = new CallableStub();
        $callable = array($obj, 'dynamicCall');
        $callable = new \CallableSpy\Spy($callable);

        // Exercise
        $result = $callable($a);

        // Verify
        $this->_verifySpy($callable, true, 1);
        $this->_verifyCall($callable->getLastCall(), array($a), $expectedResult);
    }


    /**
     * Relative static class method test data-provider
     *
     * @return array
     */
    public function relativeStaticMethodDataProvider() {
        return array(
            array('foo', 'bar', 'foobar'),
            array('baz', 'spam', 'bazspam')
        );
    }

    /**
     * Tests relative static class method calls
     *
     * Type 4: Relative static class method (As of PHP 5.3.0)
     *
     * @param mixed $a
     * @param mixed $b
     * @param mixed $expectedResult
     * @dataProvider relativeStaticMethodDataProvider
     */
    public function testRelativeStaticMethod($a, $b, $expectedResult)
    {
        // Setup
        $callable = array('CallableStubChild', 'parent::staticCall');
        $callable = new \CallableSpy\Spy($callable);

        // Exercise
        $result = $callable($a, $b);

        // Verify
        $this->_verifySpy($callable, true, 1);
        $this->_verifyCall($callable->getLastCall(), array($a, $b), $expectedResult);
    }


    /**
     * Closure test data-provider
     *
     * @return array
     */
    public function closureDataProvider() {
        return array(
            array(5, 2, 3, 10),
            array(7, 1, 0, 8)
        );
    }

    /**
     * Tests closures
     *
     * Type 5: Closures
     *
     * @param mixed $a
     * @param mixed $b
     * @param mixed $c
     * @param mixed $expectedResult
     * @dataProvider closureDataProvider
     */
    public function testClosure($a, $b, $c, $expectedResult)
    {
        // Setup
        $closure = function ($param1, $param2) use ($a) {
            return $a + $param1 + $param2;
        };
        $closure = new \CallableSpy\Spy($closure);

        // Exercise
        $result = $closure($b, $c);

        // Verify
        $this->_verifySpy($closure, true, 1);
        $this->_verifyCall($closure->getLastCall(), array($b, $c), $expectedResult);
    }



    /**
     * Tests last call the same as last call in calls list - with multiple calls
     */
    public function testLastCallWithMultipleCalls()
    {
        $data = array(
            array(
                'args' => array(1, 2),
                'result' => 2
            ),
            array(
                'args' => array(5, 4),
                'result' => 20
            )
        );

        // Setup
        $callable = new \CallableSpy\Spy('stubFn');

        // Exercise
        $result = call_user_func_array($callable, $data[0]['args']);
        $result = call_user_func_array($callable, $data[1]['args']);

        // Verify
        $calls = $callable->getCalls();
        $this->_verifyCall($calls[0], $data[0]['args'], $data[0]['result']);
        $this->_verifyCall($calls[1], $data[1]['args'], $data[1]['result']);

        $this->assertEquals($calls[1], $callable->getLastCall(), 'Last call was not chosen correctly');
    }
}