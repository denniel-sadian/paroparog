<?php
namespace Models;

require_once '/var/www/utilities/db/connection.php';
require_once '/var/www/utilities/db/models/helper_functions.php';
require_once '/var/www/utilities/db/models/dtos.php';

use PDO;
use DTOs\PageRequest;
use DTOs\Page;
use DTOs\Search;
use DTOs\Sort;


class Butterfly {

    const TABLE = 'butterflies';

    public $id;
    public $specie_type;
    public $class_name;
    public $family_name;

    static function create($fields) {
        $butterfly = new Butterfly();
        $butterfly->specie_type = $fields['specie_type'];
        $butterfly->class_name = $fields['class_name'];
        $butterfly->family_name = $fields['family_name'];
        return $butterfly;
    }

    static function filter(Search $search = null, Sort $sort = null, PageRequest $page_req = null) {
        if ($search == null) $search = Search::create();
        if ($sort == null) $sort = Sort::create();
        if ($page_req == null) $page_req = PageRequest::create();

        $conn = connect();

        $WHERE = create_where_clause_for_search($search);
        $SQL = 'SELECT * FROM '.self::TABLE;
        $SQL = $SQL.$WHERE;

        if ($sort != null) {
            $SQL = $SQL." order by $sort->field $sort->order";
        }
        if ($page_req != null) {
            $SQL = $SQL." LIMIT $page_req->size OFFSET $page_req->offset";
        }

        $statement = $conn->prepare($SQL);
        $statement->execute(create_params_for_search($search));
        $items = $statement->fetchAll(PDO::FETCH_CLASS, 'Models\Butterfly');

        $SQL = 'SELECT count(*) FROM '.self::TABLE;
        $SQL = $SQL.$WHERE;

        $statement = $conn->prepare($SQL);
        $statement->execute(create_params_for_search($search));
        $total_items = $statement->fetchColumn();

        $page = Page::create($items, $page_req->number+1, $page_req->size, $total_items);

        $conn = null;

        return $page;
    }

    static function get($id) {
        $conn = connect();

        $SELECT_ONE = 'SELECT * FROM '.self::TABLE.' where id = :id';

        $statement = $conn->prepare($SELECT_ONE);
        $statement->execute([':id' => $id]);
        $obj = $statement->fetchAll(PDO::FETCH_CLASS, 'Models\Butterfly')[0];

        $conn = null;

        return $obj;
    }

    function save() {
        global $TABLE;

        $conn = connect();

        $INSERT = '
            INSERT INTO '.self::TABLE.' (
                specie_type,
                class_name,
                family_name
            )
            VALUES (
                :specie_type,
                :class_name,
                :family_name
            )';

        $UPDATE = '
            UPDATE '.self::TABLE.' SET
                specie_type = :specie_type,
                class_name = :class_name,
                family_name = :family_name
            WHERE id = :id';

        $statement = $statement = $conn->prepare($this->id == null ? $INSERT : $UPDATE);

        $params = [
            ':specie_type' => $this->specie_type,
            ':class_name' => $this->class_name,
            ':family_name' => $this->family_name
        ];
        if ($this->id != null) {
            $params[':id'] = $this->id;
        }

        $statement->execute($params);

        if ($this->id == null) {
            $this->id = $conn->lastInsertId();
        }

        $conn = null;
    }

    function delete() {
        $conn = connect();

        $DELETE = 'DELETE FROM '.self::TABLE.' WHERE id = :id';

        if ($this->id != null) {
            $statement = $conn->prepare($DELETE);
            $statement->execute([':id' => $this->id]);
        }

        $conn = null;
    }

}
