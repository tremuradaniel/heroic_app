<?php
    class Beast {
        
        private $db;
        public function __construct()
        {
            $this->db = new Database;
        }

        public function getBeastSkillsIntervals()
        {
            $sql = getCombatantTraitsInteravalQuerry('beast');
            $this->db->query($sql);
            $results = $this->db->resultSet();
            return $results;
        }

    }
