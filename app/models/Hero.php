<?php
    class Hero {
        
        private $db;
        public function __construct()
        {
            $this->db = new Database;
        }

        public function getHeroSkillsIntervals()
        {
            $sql = getCombatantTraitsInteravalQuerry('hero');
            $this->db->query($sql);
            $results = $this->db->resultSet();
            return $results;
        }

    }
