<?php

    
    /**
     * getCombatantSkillsInteravalQuerry
     *
     * @param  string $type
     * @return string
     */
    function getCombatantSkillsInteravalQuerry (String $type) {
        return "SELECT * FROM combatant_skills_intervals
            INNER JOIN combatant_types
            ON combatant_skills_intervals.combatant_type_id = combatant_types.id
            WHERE combatant_types.type = '{$type}'
            ORDER BY combatant_skills_intervals.trait_id;";
    }
