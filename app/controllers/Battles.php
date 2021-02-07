<?php
    class Battles extends Controller {
        public const MAX_ROUND = 20;
        public $log = [];
        public function __construct()
        {
            $this->hero = new Heroes();
        }

        public function show(): void
        {
            $this->view('battle/show', [
                'data' => $this->hero
            ]);
        }
    }
