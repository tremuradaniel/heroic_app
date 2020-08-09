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
        
        function magicShield () {
            return rand(1,100) <= 20;
        }

        function takeDamage ($attackerStrength) {
            $useMagicShield = $this->magicShield();
            $damage = $attackerStrength - $this->traits->defence;
            if ($useMagicShield) {
                $damage = $damage / 2;
                if ($damage > 0) {
                    $this->traits->health -= $damage;
                }
            } else parent::takeDamage($attackerStrength);
            return $damage;
        }
    }
?>