<?php
namespace Models;

use PDO;

require_once __DIR__.'/../connection.php';
require_once 'helper_functions.php';


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

    static function search($params) {
        $conn = connect();

        $FILTER = 'SELECT * FROM '.self::TABLE;
        $search = $FILTER.create_where_clause_for_search($params);

        $statement = $conn->prepare($search);
        $statement->execute(create_params_for_search($params));
        $objs = $statement->fetchAll(PDO::FETCH_CLASS, 'Models\Butterfly');

        $conn = null;

        return $objs;
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
