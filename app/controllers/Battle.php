<?php

    class Battle extends Controller {

        public function __construct()
        {
            
        }

        public function show(): void
        {
            $this->view('battle/show');
        }
    }
