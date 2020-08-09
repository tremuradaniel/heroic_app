<?php
    class Battle {
        // Properties
        public $maxRound = 20;
        public $stage;
        public $round;
        public $warriors = [];
        public $hero = null;
        public $beast = null;
        public $outcome = [
            'warriors' => [],
            'log' => [],
            'round' => 0,
            'wasAttacker' => null,
            'winnner' => null
        ];
        public $battleLog = [];
        public $battleActions = [
            'battleStart' => 'The fight is about to begin!',
            'battleEnd' => 'The fight has ended!',
            'heroFallen' => 'The mighty Orderus has fallen!',
            'beastSlayed' => 'The Beast has been slayed!',
            'beastFirst' => 'The Beast is more agile and strikes first!',
            'heroFirst' => 'Orderus is more agile and strikes first!',
            'beastLuck' => 'The Beast is lucky and avoids a devastating blow!',
            'heroLuck' => 'Orderus is lucky and avoids a devastating blow!',
            'beastHit' => 'The Beast hits Orderus! Damage done: ',
            'heroHit' => 'Orderus hits the Beast! Damage done: ',
            'heroSpecialDefence' => 'Orderus uses his shield and gets half the damage!',
            'heroSpecialAttack' => 'Orderus manages to get an extra hit!',
        ];

        
        function isStillAlive ($evaluatedWarrior) {
            
            if ($evaluatedWarrior && isset($evaluatedWarrior->traits->health)) {
                if (!($evaluatedWarrior->traits->health > 0)) {
                    $this->outcome['winner'] = $evaluatedWarrior instanceof Hero ? 'beast' : 'hero';
                    if ($this->outcome['winner'] === 'hero') {
                        $this->logBattle($this->battleActions['beastSlayed']);
                    } else $this->logBattle($this->battleActions['heroFallen']);
                    $this->logBattle($this->battleActions['battleEnd']);
                }
            }
        }

        function whoGoesFirst () {
            $goesFirst;
            if(isset($this->warriors)  && count($this->warriors) === 2) {
                $heroSpeed = isset($this->hero) && isset($this->hero->traits->speed)
                    ? $this->hero->traits->speed : 0;  
                $beastSpeed = isset($this->warriors[1]) && isset($this->warriors[1]->traits->speed)
                    ? $this->warriors[1]->traits->speed : 0;
                $compareSpeed = $this->compareAbilities($heroSpeed, $beastSpeed);
                switch ($compareSpeed) {
                    case 1:
                        # hero goes first
                        $goesFirst = $this->hero;
                        break;
                    case 2:
                        # beast goes first
                        $goesFirst = $this->warriors[1];
                        break;
                    default:
                        $heroLuck = isset($this->hero) && isset($this->hero->traits->luck)
                            ? $this->hero->traits->luck : 0;  
                        $beastLuck = isset($this->warriors[1]) && isset($this->warriors[1]->traits->luck)
                            ? $this->warriors[1]->traits->luck : 0;
                        $compareLuck = $this->compareAbilities($heroLuck, $beastLuck, $random = true);
                        $goesFirst = $compareLuck === 1 ? $this->hero : $this->warriors[1];
                        break;
                }
            } else {
                echo 'No fighters on the battle field.';
            }
            $this->logWhoGoesFirst($goesFirst);
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
            $this->hero = $this->warriors[0];
            $this->beast = $this->warriors[1];
            $attacker;
            $defender;
            if ($this->round === 0) {
                $attacker = $this->whoGoesFirst();
            } else {
                $attacker = $this->outcome['wasAttacker'] === 'hero' ? $this->beast : $this->hero;
            }
            $defender = $attacker instanceof Hero ? $this->beast : $this->hero;
            if ($attacker instanceof Hero) {
                // HERO attacks
                $repeatAttack = $attacker->rapidStrike();
                for ($x = 0; $x <= $repeatAttack; $x++) {
                    $this->logRepeatAttack($repeatAttack, $x);
                    $defenderLuck = isset($defender->traits->luck) ? $defender->traits->luck : 0;
                    if (!$this->thatWasACloseOne($defenderLuck)) {
                        $attackerStrength = isset($attacker->traits->strength) ? $attacker->traits->strength : 0;
                        $damage = $attackerStrength - $defender->traits->defence;
                        $this->logBattle($this->battleActions['heroHit'] . $damage);
                        $defender->takeDamage($attackerStrength);
                    } else {
                        $this->logBattle($this->battleActions['beastLuck']);
                    }
                }
            } else {
                // BEAST attacks
                if (!$this->thatWasACloseOne($defender->traits->luck)) {
                    $attackerStrength = isset($attacker->traits->strength) ? $attacker->traits->strength : 0;
                    $initialDamage = $attackerStrength - $defender->traits->defence;
                    $actualDamage = $defender->takeDamage($attackerStrength);
                    if ($initialDamage !== $actualDamage) {
                        $this->logBattle($this->battleActions['heroSpecialDefence']);
                    }
                    $this->logBattle($this->battleActions['beastHit'] . ($actualDamage > 0 ? $actualDamage : 0));
                } else {
                    $this->logBattle($this->battleActions['heroLuck']);
                }
            }
            $this->outcome['warriors'] = [$this->hero->traits, $this->beast->traits];
           
            $this->outcome['wasAttacker'] = $attacker instanceof Hero ? 'hero' : 'beast';
            $this->isStillAlive($defender);
            return $this->outcome;
        }

        private function logWhoGoesFirst ($fighter) {
            if ($fighter instanceof Hero) {
                array_push($this->outcome['log'], $this->battleActions['heroFirst']);
            } else {
                array_push($this->outcome['log'], $this->battleActions['beastFirst']);
            }
        }

        private function logRepeatAttack ($repeatAttack, $repeatAttackTurn) {
            if ($repeatAttack === 1 && $repeatAttack === $repeatAttackTurn) {
                array_push($this->outcome['log'], $this->battleActions['heroSpecialAttack']);
            }
        }

        private function logBattle($message) {
            array_push($this->outcome['log'], $message);
        }

    }
?>