<?php
    class Battles extends Controller {

        public const MAX_ROUND = 20;
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
                'hero' => [$this->hero->traits],
                'beast' => [$this->beast->traits],
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
                'hero' => [$this->hero->traits],
                'beast' => [$this->beast->traits]
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
            $this->setCurrRoundNumber($data);
            if ($this->round < 0) {
                echo 'problem with setting current round';
                return;
            }
            if ($this->round === 0) $this->determineWhoHasFirstBlow();
            $this->runRound();
        }

        private function setCurrRoundNumber ($data) {
            $this->round = $data !== null && isset($data->battle) &&
                isset($data->battle->round) ? $data->battle->round : -1;
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

        }

        private function setCombatantsStats ($data) {
            $heroTraits = $data->combatants->hero[0];
            $beastTraits = $data->combatants->beast[0];
            $this->hero->setTraits($heroTraits);
            $this->beast->setTraits($beastTraits);
            $this->battleStats->combatants['hero'] = $this->hero->traits;
            $this->battleStats->combatants['beast'] = $this->beast->traits;
        }
        
    }
