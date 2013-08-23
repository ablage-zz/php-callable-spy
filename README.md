php-callable-spy
================

PHP Callable Spy

Example:

    $fn = function ($a, $b) {
        return $a + $b;
    };

    $fn = new Callable\Spy($fn);

    echo $fn(5, 3); // Output: 8

    // Get last call made through spy proxy
    $lastCall = $fn->getLastCall();

    $timestamp = $lastCall->getDate();          // Timestamp of call
    $stackTrace = $lastCall->getStackTrace();   // Full stack-trace of call

    print_r($lastCall->getArgs());      // Output: [5, 3]
    print_r($lastCall->getResult());    // Output: 8

