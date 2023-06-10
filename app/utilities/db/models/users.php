<?php
namespace Models;

use PDO;

require_once __DIR__.'/../connection.php';
require_once 'helper_functions.php';


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

    static function create($fields) {
        $user = new User();
        $user->username = $fields['username'];
        $user->email = $fields['email'];
        $user->password = md5($fields['password']);
        $user->first_name = $fields['first_name'];
        $user->last_name = $fields['last_name'];
        $user->gender = $fields['gender'];
        $user->type = $fields['type'];
        $user->active = $fields['active'];
        return $user;
    }

    static function search($params) {
        $conn = connect();

        $FILTER = 'SELECT * FROM '.self::TABLE;
        $search = $FILTER.create_where_clause_for_search($params);

        $statement = $conn->prepare($search);
        $statement->execute(create_params_for_search($params));
        $objs = $statement->fetchAll(PDO::FETCH_CLASS, 'Models\User');

        $conn = null;

        return $objs;
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
                active
            )
            VALUES (
                :username,
                :email,
                :password,
                :first_name,
                :last_name,
                :gender,
                :type,
                :active
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
                active = :active
            WHERE id = :id';

        $statement = $statement = $conn->prepare($this->id == null ? $INSERT : $UPDATE);

        $params = [
            ':username' => $this->username,
            ':email' => $this->email,
            ':password' => $this->password,
            ':first_name' => $this->first_name,
            ':last_name' => $this->last_name,
            ':gender' => $this->gender,
            ':type' => $this->type,
            ':active' => $this->active,
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
