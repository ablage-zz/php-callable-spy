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
 * Data class holding call information for every call made through the spy-proxy
 *
 * @package CallableSpy
 */
class Call
{

    /**
     * Timestamp of call
     *
     * @var DateTime|null
     */
    protected $_date = NULL;

    /**
     * List of arguments supplied for the call
     *
     * @var array|null
     */
    protected $_args = NULL;

    /**
     * Result returned from the call
     *
     * @var mixed|null
     */
    protected $_result = NULL;

    /**
     * Stack-trace of call
     *
     * @var array|null
     */
    protected $_stackTrace = NULL;


    /**
     * Initializes the call
     *
     * @param array|null $args
     * @param mixed|nnull $result
     * @param DateTime|null [$date=null]
     * @param array|null [$stackTrace=null]
     */
    public function __construct($args, $result, $date = NULL, $stackTrace = NULL)
    {
        $this->_date = (isset($date) ? $date : new \DateTime());

        $this->_args   = $args;
        $this->_result = $result;

        $this->_stackTrace = (isset($stackTrace) ? $stackTrace : debug_backtrace(TRUE));
    }


    /**
     * Gets the timestamp of the call
     *
     * @return DateTime|null
     */
    public function getDate()
    {
        return $this->_date;
    }

    /**
     * Gets the supplied arguments for the call
     *
     * @return array|null
     */
    public function getArgs()
    {
        return $this->_args;
    }

    /**
     * Gets the result for the call
     *
     * @return nnull|mixed|null
     */
    public function getResult()
    {
        return $this->_result;
    }

    /**
     * Gets the stack-trace for the call
     *
     * @return array|null
     */
    public function getStackTrace()
    {
        return $this->_stackTrace;
    }
}
