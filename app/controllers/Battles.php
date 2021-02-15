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

        public function __construct()
        {
            $this->hero = new Heroes();
            $this->beast = new Beasts();
        }

        public function show(): void
        {
            $this->view('battle/show');
        }

        public function initializeBattle ()
        {
            $data = new stdClass();
            $data->combatants = [
                'hero' => [$this->hero->traits],
                'beast' => [$this->beast->traits]
            ];
            $data->battle = [
                'log' => [$this->battleActions['battleStart']],
                'round' => 0
            ];
            echo json_encode($data);
        }
        
    }
