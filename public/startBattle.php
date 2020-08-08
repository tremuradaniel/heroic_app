<?php

    require '../vendor/autoload.php';
    $response = '123';
    $test = new Battle();
    $response = $test->test();
    echo $response;
?>