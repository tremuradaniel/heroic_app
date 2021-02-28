<?php
    class Battles extends Controller {

        public const MAX_ROUND = 20;
        public const FIRST_ROUND = 0;
        public $log = [];
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
            'draw' => 'It\'s a draw! Game over.'
        ];

        public $round = 0;

        public function __construct()
        {
            $initialize = isset($_REQUEST['initialize']) ? json_decode($_REQUEST['initialize']) : false;
            $this->hero = new Heroes($initialize);
            $this->beast = new Beasts($initialize);
            $this->battleStats = new stdClass();

            $this->battleStats->combatants = [
                'hero' => $this->hero->traits,
                'beast' => $this->beast->traits,
                'isAttacker' => null
            ];
            $this->battleStats->battle = [
                'log' => [$this->battleActions['battleStart']],
                'round' => 0
            ];
        }

        public function show(): void
        {
            $this->view('battle/show');
        }

        public function initializeBattle ()
        {
            $this->setBattleStats();
            echo json_encode($this->battleStats);
        }

        private function setBattleStats()
        {
            $this->battleStats->combatants = [
                'hero' => $this->hero->traits,
                'beast' => $this->beast->traits
            ];
            $this->battleStats->battle = [
                'log' => [$this->battleActions['battleStart']],
                'round' => 0
            ];
        }

        public function triggerRound () {
            $data = isset($_REQUEST['battleStats']) ? json_decode($_REQUEST['battleStats']) : null;
            if ($data === null) {
                echo 'Could not get battleStats';
                return;
            }

            $this->setCombatantsStats($data);
            $this->setBattleRound($data);
            
            $isFirstRound = $this->round === self::FIRST_ROUND;
            
            if ($this->round < self::FIRST_ROUND) {
                echo 'problem with setting current round';
                return;
            }
            
            if ($isFirstRound) $this->determineWhoHasFirstBlow();

            $confrontationResult = $this->runRound();
            $result = $this->checkForGameOver($confrontationResult);
            echo json_encode($result);
        }

        private function setDraw () {
            array_push($this->battleStats->battle['log'], $this->battleActions['draw']);
            $this->battleStats->battle['status'] = 'gameOver';
            return $this->battleStats;
        }

        private function determineWhoHasFirstBlow () {
            $attacker = $this->setAttackerBasedOn('speed');
            if (is_null($attacker)) $attacker = $this->setAttackerBasedOn('luck');
            if (is_null($attacker)) $attacker = $this->setRandomAttacker();
            if (!is_null($attacker)) $this->battleStats->combatants['isAttacker'] = $attacker;
            else echo 'could not set attacker';
        }

        private function setAttackerBasedOn($trait)
        {
            $heroTrait = isset($this->hero->traits->$trait) ? $this->hero->traits->$trait : false;
            $beastTrait = isset($this->beast->traits->$trait) ? $this->beast->traits->$trait : false;
            $test = $heroTrait > $beastTrait;
            if ($heroTrait && $beastTrait) {
                if (!($heroTrait === $beastTrait)) {
                    return $heroTrait > $beastTrait ? 'hero' : 'beast';
                } else return null;
            } else {
                echo 'Could not find traits';
                return null;
            }
        }

        private function setRandomAttacker() {
            return rand(0,1) === 0 ? 'hero' : 'beast';
        }

        private function runRound () {
            $combat = new Combat($this->battleStats, $this->hero);
            $roundResuls = $combat->initializeCombat();
            return $roundResuls;
        }

        private function setCombatantsStats ($data) {
            $heroTraits = $data->combatants->hero;
            $beastTraits = $data->combatants->beast;
            $this->hero->setTraits($heroTraits);
            $this->beast->setTraits($beastTraits);
            $this->battleStats->combatants['hero'] = $this->hero->traits;
            $this->battleStats->combatants['beast'] = $this->beast->traits;
            if (isset($data->combatants->isAttacker)) {
                $this->battleStats->combatants['isAttacker'] = $data->combatants->isAttacker === 'hero'
                    ? 'beast' : 'hero';
            }
        }

        private function setBattleRound ($data) {
            $round = $data->battle->round;
            $this->battleStats->battle['round'] = $round;
            $this->round = $round;
        }

        private function checkForGameOver ($roundResuls) {
            $heroHealth = $roundResuls->combatants['hero']->health;
            $beastHealth = $roundResuls->combatants['beast']->health;
            $this->round = $roundResuls->battle['round'];
            $maximumRoundsReached = $this->round >= self::MAX_ROUND;
            if ($maximumRoundsReached) {
                if ($heroHealth > 0 && $beastHealth > 0) {
                    return $this->setDraw();
                }
            }
            return $roundResuls;
        }
        
    }
