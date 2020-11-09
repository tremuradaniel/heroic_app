<?php

    
    /**
     * getCombatantTraitsInteravalQuerry
     *
     * @param  string $type
     * @return string
     */
    function getCombatantTraitsInteravalQuerry (String $type) {
        return 
            "SELECT 
                combatant_traits.trait,
                combatant_traits_intervals.min,
                combatant_traits_intervals.max
            FROM combatant_traits_intervals
                INNER JOIN combatant_types
            ON combatant_traits_intervals.combatant_type_id = combatant_types.id
                INNER JOIN combatant_traits 
            ON combatant_traits_intervals.trait_id = combatant_traits.id
                WHERE combatant_types.`type` = '{$type}'
            ORDER BY combatant_traits_intervals.trait_id;";
    }
