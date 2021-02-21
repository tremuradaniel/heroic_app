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
        public function __construct($initialize)
        {
            $this->beastModel = $this->model('beast');
            if ($initialize) {
                $traitsIntervals = $this->beastModel->getBeastSkillsIntervals();
                $this->setBeastTraitsFromRanges($traitsIntervals);
            }
        }
                
        /**
         * setBeastTraits
         *
         * @param  array $ranges
         * @return void
        */
        private function setBeastTraitsFromRanges ($ranges) {
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

        /**
         * setBeastTraits
         *
         * @param  mixed $array
         * @return void
        */
        public function setTraits ($array) {
            $this->traits = $array;
        }

    }
