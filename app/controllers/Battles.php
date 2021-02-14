<?php
    class Battles extends Controller {

        public const MAX_ROUND = 20;
        public $log = [];

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
            echo json_encode($data);
        }
        
    }
