<?php

    class Combat {

        public function __construct($battleStats, $hero) {
            $this->battleStats = $battleStats;
            $this->hero = $hero;
            $this->setbattleActions();
        }

        public function initializeCombat() {
            if (!($this->battleStats->combatants['beast'] && $this->battleStats->combatants['hero'] &&
                $this->battleStats->combatants['isAttacker'])) return false;
            $this->startCombat();
            $this->updateRound();
            return $this->battleStats;
        }

        public function setbattleActions() {
            $battleClass = new Battles;
            $this->battleActions = $battleClass->battleActions;
        }

        private function updateRound() {
            $this->battleStats->battle['round'] += 1;
        }

        private function startCombat() {
            if ($this->battleStats->combatants['isAttacker'] === 'hero') return $this->heroAttacks();
            else return $this->beastAttacks();
        }

        private function heroAttacks($usedSepcialAttack = false)
        {
            if ($this->defenderIsLucky($this->battleStats->combatants['beast']->luck)) {
                array_push($this->battleStats->battle['log'], $this->battleActions['beastLuck']);
                return;
            }

            $damage = $this->calculateDamageFor('hero');
            $this->doDamageTo('beast', $damage);
            if (!$usedSepcialAttack) $this->trySpecialAttack();
        }

        private function beastAttacks() {
            if ($this->defenderIsLucky($this->battleStats->combatants['hero']->luck)) {
                array_push($this->battleStats->battle['log'], $this->battleActions['heroLuck']);
                return;
            }
            $damage = $this->calculateDamageFor('beast');
            $this->doDamageTo('hero', $damage);
        }

        private function trySpecialAttack() {
            if ($this->hero->rapidStrike()) {
                array_push($this->battleStats->battle['log'], $this->battleActions['heroSpecialAttack']);
                $this->heroAttacks(true);
            }
        }

        private function defenderIsLucky($defenderLuck) {
            if (rand(1,100) <= $defenderLuck) {
                return true;
            } else return false;
        }

        private function calculateDamageFor($combatant) {
            $attacker = $combatant === 'hero' 
                ? $this->battleStats->combatants['hero']
                : $this->battleStats->combatants['beast'];
            $defender = $combatant !== 'hero' 
                ? $this->battleStats->combatants['hero']
                : $this->battleStats->combatants['beast'];
            return $attacker->strength - $defender->defence;
        }

        private function doDamageTo($recepient, $damage) {
            if ($recepient === 'hero') {
                $damage = $this->hero->tryToUseShieldAgainstDamage($damage);
                $this->battleStats->combatants[$recepient]->health -= $damage;
                array_push($this->battleStats->battle['log'], $this->battleActions['beastHit'] . $damage);
            } else {
                $this->battleStats->combatants[$recepient]->health -= $damage;
                array_push($this->battleStats->battle['log'], $this->battleActions['heroHit'] . $damage);
            }
        }
        
    }
