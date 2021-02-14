<?php

    class Beasts extends Controller {

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
            $this->beastModel = $this->model('beast');
            $traitsIntervals = $this->beastModel->getBeastSkillsIntervals();
            $this->setBeastTraits($traitsIntervals);
        }
                
        /**
         * setBeastTraits
         *
         * @param  array $ranges
         * @return void
        */
        private function setBeastTraits ($ranges) {
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
