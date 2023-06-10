<?php
function create_where_clause_for_search($params, $is_strict) {
    if (count($params) != 0) {
        $clauses = [];
        foreach ($params as $field => $str_search) {
            if ($is_strict) {
                array_push($clauses, "$field = :$field");
            } else {
                array_push($clauses, "LOWER($field) LIKE :$field");
            }
        }
        $where = ' WHERE '.implode(' and ', $clauses);
        return $where;
    }
    return '';
}


function create_params_for_search($params, $is_strict) {
    if (count($params) != 0) {
        $modified_params = [];
        foreach ($params as $field => $search) {
            if ($is_strict) {
                $modified_params[$field] = $search;
            } else {
                $modified_params[$field] = "%$search%";
            }
        }
        return $modified_params;
    }
    return [];
}
