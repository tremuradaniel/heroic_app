<?php

    class Heroes extends Controller {

        // Properties
        public $traits = [
            'health' => null,
            'strength' => null,
            'defence' => null,
            'speed' => null,
            'luck' => null
        ];
        
        /**
         * __construct
         *
         * @return void
        */
        public function __construct()
        {
            $this->heroModel = $this->model('hero');
            $traitsIntervals = $this->heroModel->getHeroSkillsIntervals();
            $this->setHeroTraits($traitsIntervals);
        }
                
        /**
         * setHeroTraits
         *
         * @param  array $ranges
         * @return void
        */
        private function setHeroTraits ($ranges) {
            $index = 0;
            foreach ($this->traits as $key => $value) {
                $currTraitInterval = array_filter($ranges, function ($elem) use($key) {
                    return $elem->trait === ucfirst($key);
                });
                $currMinTrait = array_column($currTraitInterval, 'min')[0];
                $currMaxTrait = array_column($currTraitInterval, 'max')[0];
                $this->traits[$key] = random_int($currMinTrait, $currMaxTrait);
                $index++;
            }
        }

    }