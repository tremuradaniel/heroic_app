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

        public $usedSepcialAttack = false;
        
        /**
         * __construct
         *
         * @return void
        */
        public function __construct($initialize)
        {
            $this->heroModel = $this->model('hero');
            if ($initialize) {
                $traitsIntervals = $this->heroModel->getHeroSkillsIntervals();
                $this->setHeroTraitsFromRanges($traitsIntervals);
            }
        }
                
        /**
         * setHeroTraits
         *
         * @param  array $ranges
         * @return void
        */
        private function setHeroTraitsFromRanges ($ranges) {
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
         * setHeroTraits
         *
         * @param  mixed $array
         * @return void
         */
        public function setTraits ($array) {
            $this->traits = $array;
        }

        public function rapidStrike () {
            $percentageChanseToUseAbility = 10;
            if (rand(1,100) <= $percentageChanseToUseAbility) {
                return true;
            } else return false;
        }

        public function tryToUseShieldAgainstDamage ($damage) {
            $percentageChanseToUseAbility = 20;
            $wasAbleToUseMagicShield = rand(1,100) <= $percentageChanseToUseAbility;
            if ($wasAbleToUseMagicShield) return $damage / 2;
            return $damage;
        }

    }