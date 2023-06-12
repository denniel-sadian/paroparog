<?php
namespace Models;

require_once "/var/www/utilities/db/connection.php";
require_once "/var/www/utilities/db/models/helper_functions.php";
require_once '/var/www/utilities/db/models/dtos.php';

use PDO;
use DTOs\PageRequest;
use DTOs\Page;
use DTOs\Search;
use DTOs\Sort;


class UserType {
    const CLIENT = 'CLIENT';
    const ADMIN = 'ADMIN';
    const PAYMENT_SIGNATORY = 'PAYMENT_SIGNATORY';
    const PERMIT_SIGNATORY = 'PERMIT_SIGNATORY';
    const RELEASING_PERSONNEL = 'RELEASING_PERSONNEL';
}


class Gender {
    const MALE = 'MALE';
    const FEMALE = 'FEMALE';
}


class User {

    const TABLE = 'users';

    public $id;
    public $username;
    public $email;
    public $password;
    public $first_name;
    public $last_name;
    public $gender;
    public $type;
    public $created_at;
    public $active;
    public $password_changed;

    static function create($fields) {
        $user = new User();
        $user->username = $fields['username'];
        $user->email = $fields['email'];
        $user->password = md5($fields['password']);
        $user->first_name = $fields['first_name'];
        $user->last_name = $fields['last_name'];
        $user->gender = $fields['gender'];
        $user->type = $fields['type'];
        $user->active = true;
        $user->password_changed = false;
        return $user;
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
        $items = $statement->fetchAll(PDO::FETCH_CLASS, 'Models\User');

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
        $obj = $statement->fetchAll(PDO::FETCH_CLASS, 'Models\User')[0];

        $conn = null;

        return $obj;
    }

    function save() {
        $conn = connect();

        $INSERT = '
            INSERT INTO '.self::TABLE.' (
                username,
                email,
                password,
                first_name,
                last_name,
                gender,
                type,
                active,
                password_changed
            )
            VALUES (
                :username,
                :email,
                :password,
                :first_name,
                :last_name,
                :gender,
                :type,
                :active,
                :password_changed
            )';

        $UPDATE = '
            UPDATE '.self::TABLE.' SET
                id = :id,
                username = :username,
                email = :email,
                password = :password,
                first_name = :first_name,
                last_name = :last_name,
                gender = :gender,
                type = :type,
                active = :active,
                password_changed = :password_changed
            WHERE id = :id';

        $statement = $statement = $conn->prepare($this->id == null ? $INSERT : $UPDATE);

        $statement->bindParam(':username', $this->username);
        $statement->bindParam(':email', $this->email);
        $statement->bindParam(':password', $this->password);
        $statement->bindParam(':first_name', $this->first_name);
        $statement->bindParam(':last_name', $this->last_name);
        $statement->bindParam(':gender', $this->gender);
        $statement->bindParam(':type', $this->type);
        $statement->bindParam(':active', $this->active, PDO::PARAM_BOOL);
        $statement->bindParam(':password_changed', $this->password_changed, PDO::PARAM_BOOL);
        if ($this->id != null) {
            $statement->bindParam(':id', $this->id, PDO::PARAM_INT);
        }

        $statement->execute();

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

    function set_password($password) {
        $this->password = md5($password);
    }

}
