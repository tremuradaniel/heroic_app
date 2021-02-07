<?php

    class Heroes extends Controller {


        public function __construct()
        {
            $this->heroPost = $this->model('hero');
            $this->traits = $this->heroPost->getHeroSkillsIntervals();
        }

    }