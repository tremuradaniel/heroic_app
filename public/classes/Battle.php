<?php
    class Battle {
        // Properties
        public $maxRound = 20;
        public $stage;
        public $round;
        public $warriors = [];
        public $outcome;
        public $battleLog = [];
        public $battleActions = [];

        
        function isStillAlive () {

        }

        function whoGoesFirst () {
            $goesFirst;
            if(isset($this->warriors)  && count($this->warriors) === 2) {
                $heroSpeed = isset($this->warriors[0]) && isset($this->warriors[0]->speed)
                    ? $this->warriors[0]->speed : 0;  
                $beastSpeed = isset($this->warriors[1]) && isset($this->warriors[1]->speed)
                    ? $this->warriors[1]->speed : 0;
                $compareSpeed = $this->compareAbilities($heroSpeed, $beastSpeed);
                switch ($compareSpeed) {
                    case 1:
                        # hero goes first
                        $goesFirst = $this->warriors[0];
                        break;
                    case 2:
                        # beast goes first
                        $goesFirst = $this->warriors[1];
                        break;
                    default:
                        $heroLuck = isset($this->warriors[0]) && isset($this->warriors[0]->luck)
                            ? $this->warriors[0]->luck : 0;  
                        $beastLuck = isset($this->warriors[1]) && isset($this->warriors[1]->luck)
                            ? $this->warriors[1]->luck : 0;
                        $compareLuck = $this->compareAbilities($heroLuck, $beastLuck, $random = true);
                        $goesFirst = $compareLuck === 1 ? $this->warriors[0] : $this->warriors[1];
                        break;
                }
            } else {
                echo 'No fighters on the battle field.';
            }
            return $goesFirst;
        }

        private function compareAbilities ($ability1, $ability2, $random = null) {
            if (!(is_int($ability1) || is_int($ability2))) return;
            if ($ability1 > $ability2) return 1;
            else if ($ability1 < $ability2) return 2;
            else if ($random) return random_int(1, 2);
        }

        private function thatWasACloseOne ($defenderLuck) {
            // if luck is on the side of the defender it won't get hit
            if (rand(1,100) <= $defenderLuck) {
                return true;
            } else return false;
        }

        function fightRound () {
            $attacker = $this->whoGoesFirst();
            $defender = $attacker instanceof Hero ? $this->warriors[0] : $this->warriors[1];

            if ($attacker instanceof Hero) {
                $repeatAttack = $attacker->rapidStrike();
                for ($x = 0; $x <= $repeatAttack; $x++) {
                    $defenderLuck = isset($defender->traits->luck) ? $defender->traits->luck : 0;
                    if (!$this->thatWasACloseOne($defenderLuck)) {
                        $attackerStrength = isset($attacker->traits->strength) ? $attacker->traits->strength : 0;
                        $defender->takeDamage($attackerStrength);
                    }
                }
            } else {
                if (!$this->thatWasACloseOne($defender->traits->luck)) {
                    $defender->takeDamage($attacker->traits->strength);
                }
            }
            return $this->warriors;
        }
    }
?>