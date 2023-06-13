<?php
namespace Models;

require_once '/var/www/utilities/db/connection.php';
require_once '/var/www/utilities/db/models/helper_functions.php';
require_once '/var/www/utilities/db/models/dtos.php';
require_once '/var/www/utilities/db/models/users.php';

use PDO;
use Models\User;
use DTOs\PageRequest;
use DTOs\Page;
use DTOs\Search;
use DTOs\Sort;


class PaymentOrder {

    const TABLE = 'payment_order';

    public $id;
    public $ltpapp_id;
    public $payment_signatory_id;
    public $amount;
    public $or_no = null;
    public $or_link;
    public $signature_link;

    public $payment_signatory = null;

    static function filter(Search $search = null, Sort $sort = null, PageRequest $page_req = null) {
        if ($search == null) $search = Search::create();
        if ($sort == null) $sort = Sort::create();
        if ($page_req == null) $page_req = PageRequest::create();

        $conn = connect();

        $WHERE = create_where_clause_for_search($search);
        $SQL = 'SELECT * FROM '.self::TABLE;
        $SQL = $SQL.$WHERE;

        if ($sort != null) {
            $SQL = $SQL." ORDER BY $sort->field $sort->order";
        }
        if ($page_req != null) {
            $SQL = $SQL." LIMIT $page_req->size OFFSET $page_req->offset";
        }

        $statement = $conn->prepare($SQL);
        $statement->execute(create_params_for_search($search));
        $items = $statement->fetchAll(PDO::FETCH_CLASS, 'Models\PaymentOrder');
        foreach ($items as $item) {
            $item->fetch_related();
        }

        $SQL = 'SELECT count(*) FROM '.self::TABLE;
        $SQL = $SQL.$WHERE;

        $statement = $conn->prepare($SQL);
        $statement->execute(create_params_for_search($search));
        $total_items = $statement->fetchColumn();

        $page = Page::create($items, $page_req->number+1, $page_req->size, $total_items);

        $conn = null;

        return $page;
    }

    static function filter_all(Search $search = null, Sort $sort = null) {
        $total_items = self::filter($search, $sort)->total_items;
        $page_req = $page_req = PageRequest::create(0, $total_items);
        $items = self::filter($search, $sort, $page_req)->items;
        return $items;
    }

    static function get($id) {
        $conn = connect();

        $SELECT_ONE = 'SELECT * FROM '.self::TABLE.' where id = :id';

        $statement = $conn->prepare($SELECT_ONE);
        $statement->execute([':id' => $id]);
        $obj = $statement->fetchAll(PDO::FETCH_CLASS, 'Models\PaymentOrder')[0];
        $obj->fetch_related();

        $conn = null;

        return $obj;
    }

    function save() {
        $conn = connect();

        $INSERT = '
            INSERT INTO '.self::TABLE.' (
                ltpapp_id,
                payment_signatory_id,
                amount,
                or_no,
                or_link,
                signature_link
            )
            VALUES (
                :ltpapp_id,
                :payment_signatory_id,
                :amount,
                :or_no,
                :or_link,
                :signature_link
            )';

        $UPDATE = '
            UPDATE '.self::TABLE.' SET
                ltpapp_id = :ltpapp_id,
                payment_signatory_id = :payment_signatory_id,
                amount = :amount,
                or_no = :or_no,
                or_link = :or_link,
                signature_link = :signature_link
            WHERE id = :id';

        $statement = $statement = $conn->prepare($this->id == null ? $INSERT : $UPDATE);

        $params = [
            ':ltpapp_id' => $this->ltpapp_id,
            ':payment_signatory_id' => $this->payment_signatory_id,
            ':amount' => $this->amount,
            ':or_no' => $this->or_no,
            ':or_link' => $this->or_link,
            ':signature_link' => $this->signature_link
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

    function fetch_related() {
        if ($this->payment_signatory_id != null) {
            $this->payment_signatory = User::get($this->payment_signatory_id);
        }
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
