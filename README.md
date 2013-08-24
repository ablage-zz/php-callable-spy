php-callable-spy
================

PHP Callable Spy

Example:

    // Closure to be spied on
    $fn = function ($a, $b) {
        return $a + $b;
    };

    // Replace closure with a spy
    $fn = new \Callable\Spy($fn);

    echo $fn(5, 3); // Output: 8 (through spy proxy)

    // Gets last call made through spy proxy
    $lastCall = $fn->getLastCall();

    $timestamp = $lastCall->getDate();          // Timestamp of call
    $stackTrace = $lastCall->getStackTrace();   // Full stack-trace of call

    print_r($lastCall->getArgs());      // Output: [5, 3]
    print_r($lastCall->getResult());    // Output: 8


For more examples, have a look in the examples folder above!
Also, there are a lot more examples as tests in the test folder.