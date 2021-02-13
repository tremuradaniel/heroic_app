<?php

    class Heroes extends Controller {


        public function __construct()
        {
            $this->heroModel = $this->model('hero');
            $this->traits = $this->heroModel->getHeroSkillsIntervals();
        }

    }