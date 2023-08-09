<?php
namespace Models;

use PDO;

require_once '/var/www/utilities/db/connection.php';
require_once '/var/www/utilities/db/models/helper_functions.php';
require_once '/var/www/utilities/db/models/allowed_animals.php';
require_once '/var/www/utilities/db/models/dtos.php';

use Models\AllowedAnimal;
use DTOs\PageRequest;
use DTOs\Page;
use DTOs\Search;
use DTOs\Sort;


class WfpWcp {

    const TABLE = 'wfp_wcp_details';

    public $id;
    public $permitee_first_name;
    public $permitee_last_name;
    public $permitee_address;
    public $permitee_photo_link;
    public $farm_name;
    public $farm_address;
    public $farm_photo_link;
    public $wfp_no;
    public $wfp_photo_link;
    public $wcp_no;
    public $wcp_photo_link;
    public $issuance_date;
    public $expiry_date;
    public $expired = false;
    public $allowed_animals = [];

    static function create($fields) {
        $wfpwcp = new WfpWcp();
        $wfpwcp->permitee_first_name = $fields['permitee_first_name'];
        $wfpwcp->permitee_last_name = $fields['permitee_last_name'];
        $wfpwcp->permitee_address = $fields['permitee_address'];
        $wfpwcp->permitee_photo_link = $fields['permitee_photo_link'];
        $wfpwcp->farm_name = $fields['farm_name'];
        $wfpwcp->farm_address = $fields['farm_address'];
        $wfpwcp->farm_photo_link = $fields['farm_photo_link'];
        $wfpwcp->wfp_no = $fields['wfp_no'];
        $wfpwcp->wfp_photo_link = $fields['wfp_photo_link'];
        $wfpwcp->wcp_no = $fields['wcp_no'];
        $wfpwcp->wcp_photo_link = $fields['wcp_photo_link'];
        $wfpwcp->issuance_date = $fields['issuance_date'];
        $wfpwcp->expiry_date = $fields['expiry_date'];
        return $wfpwcp;
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
        $items = $statement->fetchAll(PDO::FETCH_CLASS, 'Models\WfpWcp');
        foreach ($items as $item) {
            $item->expired = $item->is_expired();
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

    static function get($id) {
        $conn = connect();

        $SELECT_ONE = 'SELECT * FROM '.self::TABLE.' where id = :id';

        $statement = $conn->prepare($SELECT_ONE);
        $statement->execute([':id' => $id]);
        $obj = $statement->fetchAll(PDO::FETCH_CLASS, 'Models\WfpWcp')[0];

        $obj->fetch_related();

        $conn = null;

        return $obj;
    }

    function save() {
        $conn = connect();

        $INSERT = '
            INSERT INTO '.self::TABLE.' (
                permitee_first_name,
                permitee_last_name,
                permitee_address,
                permitee_photo_link,
                farm_name,
                farm_address,
                farm_photo_link,
                wfp_no,
                wfp_photo_link,
                wcp_no,
                wcp_photo_link,
                issuance_date,
                expiry_date
            )
            VALUES (
                :permitee_first_name,
                :permitee_last_name,
                :permitee_address,
                :permitee_photo_link,
                :farm_name,
                :farm_address,
                :farm_photo_link,
                :wfp_no,
                :wfp_photo_link,
                :wcp_no,
                :wcp_photo_link,
                :issuance_date,
                :expiry_date
            )';

        $UPDATE = '
            UPDATE '.self::TABLE.' SET
                permitee_first_name = :permitee_first_name,
                permitee_last_name = :permitee_last_name,
                permitee_address = :permitee_address,
                permitee_photo_link = :permitee_photo_link,
                farm_name = :farm_name,
                farm_address = :farm_address,
                farm_photo_link = :farm_photo_link,
                wfp_no = :wfp_no,
                wfp_photo_link = :wfp_photo_link,
                wcp_no = :wcp_no,
                wcp_photo_link = :wcp_photo_link,
                issuance_date = :issuance_date,
                expiry_date = :expiry_date
            WHERE id = :id';

        $statement = $statement = $conn->prepare($this->id == null ? $INSERT : $UPDATE);

        $params = [
            ':permitee_first_name' => $this->permitee_first_name,
            ':permitee_last_name' => $this->permitee_last_name,
            ':permitee_address' => $this->permitee_address,
            ':permitee_photo_link' => $this->permitee_photo_link,
            ':farm_name' => $this->farm_name,
            ':farm_address' => $this->farm_address,
            ':farm_photo_link' => $this->farm_photo_link,
            ':wfp_no' => strtoupper($this->wfp_no),
            ':wfp_photo_link' => $this->wfp_photo_link,
            ':wcp_no' => strtoupper($this->wcp_no),
            ':wcp_photo_link' => $this->wcp_photo_link,
            ':issuance_date' => $this->issuance_date,
            ':expiry_date' => $this->expiry_date
        ];
        if ($this->id != null) {
            $params[':id'] = $this->id;
        }

        $statement->execute($params);

        if ($this->id == null) {
            $this->id = $conn->lastInsertId();
        }

        $this->fetch_related();

        $conn = null;
    }

    function fetch_related() {
        $this->allowed_animals = AllowedAnimal::filter_all(Search::create(['wcp_id' => $this->id]));
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

    function is_expired() {
        $currentDate = date("Y-m-d");
        return $this->expiry_date <= $currentDate;
    }

}
