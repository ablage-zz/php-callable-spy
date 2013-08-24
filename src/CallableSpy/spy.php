<?php
//
//    The MIT License (MIT)
//
//    Copyright (c) 2013 Marcel Erz
//
//    Permission is hereby granted, free of charge, to any person obtaining a copy of
//    this software and associated documentation files (the "Software"), to deal in
//    the Software without restriction, including without limitation the rights to
//    use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
//    the Software, and to permit persons to whom the Software is furnished to do so,
//    subject to the following conditions:
//
//    The above copyright notice and this permission notice shall be included in all
//    copies or substantial portions of the Software.
//
//    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
//    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
//    FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
//    COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
//    IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
//    CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
//

namespace CallableSpy;

/**
 * Spy proxy class to spy on callables
 *
 * @package CallableSpy
 */
class Spy
{

    /**
     * List of calls made through the spy
     *
     * @var Call[]
     */
    protected $_callHistory = array();

    /**
     * Callable spied on
     *
     * @var callable|null
     */
    protected $_callable = NULL;


    /**
     * Initializes spy
     *
     * @param callable $callable
     */
    public function __construct($callable)
    {
        if (!is_callable($callable)) {
            throw new \InvalidArgumentException('Parameter is not a callable.');
        }

        $this->_callable = $callable;
    }

    /**
     * Invoked when callable is called
     *
     * @return mixed
     */
    public function __invoke()
    {
        $args   = func_get_args();
        $result = call_user_func_array($this->_callable, $args);

        $this->_callHistory[] = new Call($args, $result);

        return $result;
    }

    /**
     * Gets the total call count
     *
     * @return int
     */
    public function getCallCount()
    {
        return count($this->_callHistory);
    }

    /**
     * Was spy ever called?
     *
     * @return bool
     */
    public function wasCalled()
    {
        return ($this->getCallCount() > 0);
    }

    /**
     * Gets the most recent spy call
     *
     * @return Call
     * @throws Exception
     */
    public function getLastCall()
    {
        if (!$this->wasCalled()) {
            throw new \Exception("Callable hasn't been called.");
        }

        return $this->_callHistory[count($this->_callHistory) - 1];
    }

    /**
     * Gets all calls made through the spy
     *
     * @return Call[]
     */
    public function getCalls()
    {
        return $this->_callHistory;
    }
}

