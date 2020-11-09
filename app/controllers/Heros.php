<?php

    class Heros extends Controller {


        public function __construct()
        {
            $this->heroPost = $this->model('hero');
            $this->traits = $this->heroPost->getHeroSkillsIntervals();
        }

    }