<?php

require_once __DIR__."/../vendor/autoload.php";

// Value to be used inside of closure
$caughtValue = 3;

// Define closure with caught value
$fn = function ($value, $message) use ($caughtValue) {
    return $message.' = '.($value + $caughtValue);
};

// Create spy proxy for closure
$fn = new \CallableSpy\Spy($fn);

// Exercise closure
echo "Output: '".$fn(55, 'result')."'\n\n";


// Get call count
echo "Called ".$fn->getCallCount()." time\n";                  // 1
echo "Was called? ".var_export($fn->wasCalled(), true)."\n\n"; // true


// Get last call
$lastCall = $fn->getLastCall();

echo "Timestamp: ".$lastCall->getDate()->format(DateTime::RSS)."\n"; // [Timestamp of call]
echo "Arguments: ".var_export($lastCall->getArgs(), true)."\n";      // [55, 'result']
echo "Result:    ".var_export($lastCall->getResult(), true)."\n";    // 'result = 58'
