<?php
namespace DTOs;


class PageRequest {

    public $number;
    public $size;
    public $offset;

    public static function create($number = 0, $size = 10) {
        $page_req = new PageRequest();
        $page_req->number = $number;
        $page_req->size = $size;
        $page_req->offset = $number * $size;
        return $page_req;
    }

}


class Page {

    public $size;
    public $number;
    public $items;
    public $total_items;
    public $total_pages;
    public $prev_page;
    public $next_page;

    public static function create($items, $number, $size, $total_items) {
        $page = new Page();
        $page->items = $items;
        $page->number = $number;
        $page->size = $size;
        $page->total_items = $total_items;

        $total_pages = $size != 0 ? $total_items / $size : 0;
        if ($size != 0 && ($total_items % $size) != 0) $total_pages++;
        $page->total_pages = (int) $total_pages;

        if (($number - 1) >= 0) $page->prev_page = $number - 1;
        if (($number + 1) <= $total_pages) $page->next_page = $number + 1;

        return $page;
    }

}


class Search {

    public $fields;
    public $operator;
    public $is_strict;

    public static function create($fields = [], $operator = 'AND', $is_strict = true) {
        $search = new Search();
        $search->fields = $fields;
        $search->operator = $operator;
        $search->is_strict = $is_strict;
        return $search;
    }

}


class Sort {

    public $field;
    public $order;

    public static function create($field = 'id', $order = 'DESC') {
        $sort = new Sort();
        $sort->field = $field;
        $sort->order = $order;
        return $sort;
    }

}
