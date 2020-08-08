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
            $warriors = isset($_GET['warriors']) ? $_GET['warriors'] : null;
            echo battleRound($warriors);
            break;
        
        default:
            # code...
            break;
    }
    
    function initializeBattle() {
        $hero = new Hero('hero');
        $beast = new Fighter('beast');
        $warriors = json_encode([$hero->traits, $beast->traits]);
        return $warriors;
    }

    function battleRound($warriors) {
        $warriors = json_decode($warriors);
        $hero = new Hero(null, $warrior = $warriors[0]);
        $beast = new Fighter(null, $warrior = $warriors[1]);
        $battle = new Battle();
        $battle->warriors = [$hero, $beast];
        $warriors = json_encode($battle->fightRound());
        return $warriors;
    }   

    // battleRound('[{"health":97,"strength":71,"defence":49,"speed":40,"luck":29},
    // {"health":64,"strength":83,"defence":45,"speed":56,"luck":30}]');

?>