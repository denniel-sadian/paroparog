<?php
function create_where_clause_for_search($params) {
    if (count($params) != 0) {
        $clauses = [];
        foreach ($params as $field => $str_search) {
            array_push($clauses, "LOWER($field) LIKE :$field");
        }
        $where = ' WHERE '.implode(', ', $clauses);
        return $where;
    }
    return '';
}


function create_params_for_search($params) {
    if (count($params) != 0) {
        $modified_params = [];
        foreach ($params as $field => $str_search) {
            $modified_params[$field] = "%$str_search%";
        }
        return $modified_params;
    }
    return [];
}
