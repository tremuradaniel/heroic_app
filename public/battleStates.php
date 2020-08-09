<?php

    require '../vendor/autoload.php';
    // $state = 100;
    $state = isset($_GET['state']) ? $_GET['state'] : 0;

    switch ($state) {
        // initiate
        case 0:
            echo initializeBattle();
            break;
        // play round
        case 1:
            echo battleRound();
            break;
        
        default:
            # code...
            break;
    }
    
    function initializeBattle() {
        $hero = new Hero('hero');
        $beast = new Fighter('beast');
        $battle = new Battle();
        array_push($battle->outcome['log'], $battle->battleActions['battleStart']);
        $battle->outcome['warriors'] = [$hero->traits, $beast->traits];
        return json_encode($battle->outcome);
    }

    function battleRound() {
        $warriors = isset($_GET['warriors']) ? json_decode($_GET['warriors']) : null;
        $round = isset($_GET['round']) ? json_decode($_GET['round']) : null;
        $wasAttacker = isset($_GET['wasAttacker']) ? json_decode($_GET['wasAttacker']) : null;
        $hero = new Hero(null, $warrior = $warriors[0]);
        $beast = new Fighter(null, $warrior = $warriors[1]);
        $battle = new Battle();
        $battle->warriors = [$hero, $beast];
        // print_r($battle->warriors);
        // echo '-000000000000000000000000000000000000';
        $battle->round = $round;
        $battle->wasAttacker = $wasAttacker;
        $warriors = json_encode($battle->fightRound());
        return json_encode($battle->outcome);
    }   

    // battleRound('[{"health":97,"strength":71,"defence":49,"speed":40,"luck":29},
    // {"health":64,"strength":83,"defence":45,"speed":56,"luck":30}]');

?>