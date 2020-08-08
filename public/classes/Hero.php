<?php
    // require '../../vendor/autoload.php';
    class Hero extends Fighter {
        function __construct($kind, $warrior = null) {
            if (is_null($warrior)) parent::__construct('hero');
            else $this->traits = $warrior;
        }

        // Skils
        function rapidStrike () {
            if (rand(1,100) <= 10) {
                return 1;
            } else return 0;
        } 
        
        function magicShield ($damage) {
            if (rand(1,100) <= 10) {
                return $damage / 2;
            } else return $damage;
        }

        public function takeDamage ($damage) {
            $damage = $this->magicShield($damage);
            parent::takeDamage($damage);
        }
    }
?>