<?php
function create_where_clause_for_search($search) {
    $where = '';

    if ($search->fields != null && count($search->fields) != 0) {
        $clauses = [];
        foreach ($search->fields as $field => $str_search) {
            if ($search->is_strict) {
                array_push($clauses, "$field = :$field");
            } else {
                array_push($clauses, "LOWER($field) LIKE :$field");
            }
        }
        $where = ' WHERE '.implode(" $search->operator ", $clauses);
    }

    if ($search->extra != null) {
        if (strpos($where, 'WHERE') !== false) {
            $where = $where.' AND '.$search->extra;
        } else {
            $where = ' WHERE '.$search->extra;
        }
    }

    return $where;
}


function create_params_for_search($search) {
    if ($search != null && $search->fields != null  && count($search->fields) != 0) {
        $modified_params = [];
        foreach ($search->fields as $field => $to_search) {
            if ($search->is_strict) {
                $modified_params[$field] = $to_search;
            } else {
                $modified_params[$field] = "%$to_search%";
            }
        }
        return $modified_params;
    }
    return [];
}
