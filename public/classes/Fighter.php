<?php
    class Fighter {
        // Properties
        public $traits = [
            'health' => null,
            'strength' => null,
            'defence' => null,
            'speed' => null,
            'luck' => null
        ];

        function __construct($kind, $warrior = null) {
            if (is_null($warrior)) $this->determineInitialTraits($kind);
            else $this->traits = $warrior;
        }

        private function determineInitialTraits ($kind) {
            switch ($kind) {
                case 'beast':
                    $ranges = [
                        [60, 90], 
                        [60, 90],
                        [40, 60],
                        [40, 60],
                        [25, 40]
                    ];
                    $this->populateTraitsByRange($ranges);
                    break;
                case 'hero':
                    $ranges = [
                        [70, 100], 
                        [70, 80],
                        [45, 55],
                        [40, 50],
                        [10, 30]
                    ];
                    $this->populateTraitsByRange($ranges);
                    break;
                default:
                    break;
            }
        }

        private function populateTraitsByRange ($ranges) {
            $index = 0;
            foreach ($this->traits as $key => $value) {
                $this->traits[$key] = random_int($ranges[$index][0], $ranges[$index][1]);
                $index++;
            }
        }

        public function doDamage () {
            return $traits->strength;
        }

        public function takeDamage ($attackerStrength) {
            if (isset($this->traits->health)) {
                $damage = $attackerStrength - $this->traits->defence;
                if ($damage > 0) {
                    $this->traits->health -= $damage;
                }
            }
        }
    }


    

?>