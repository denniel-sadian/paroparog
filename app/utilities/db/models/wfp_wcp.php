<?php
namespace Models;

use PDO;

require_once __DIR__.'/../connection.php';
require_once 'helper_functions.php';


class WfpWcp {

    const TABLE = 'wfp_wcp_details';

    public $id;
    public $permitee_name;
    public $permitee_address;
    public $permitee_photo_link;
    public $farm_name;
    public $farm_address;
    public $farm_photo_link;
    public $wcp_no;
    public $issuance_date;
    public $expiry_date;

    static function create($fields) {
        $wfpwcp = new WfpWcp();
        $wfpwcp->permitee_address = $fields['permitee_address'];
        $wfpwcp->permitee_photo_link = $fields['permitee_photo_link'];
        $wfpwcp->farm_name = $fields['farm_name'];
        $wfpwcp->farm_address = $fields['farm_address'];
        $wfpwcp->farm_photo_link = $fields['farm_photo_link'];
        $wfpwcp->wcp_no = $fields['wcp_no'];
        $wfpwcp->wfp_no = $fields['wfp_no'];
        $wfpwcp->issuance_date = $fields['issuance_date'];
        $wfpwcp->expiry_date = $fields['expiry_date'];
        return $wfpwcp;
    }

    static function search($params, $is_strict) {
        global $TABLE;

        $conn = connect();

        $FILTER = 'SELECT * FROM '.self::TABLE;
        $search = $FILTER.create_where_clause_for_search($params, $is_strict);

        $statement = $conn->prepare($search);
        $statement->execute(create_params_for_search($params, $is_strict));
        $objs = $statement->fetchAll(PDO::FETCH_CLASS, 'Models\WfpWcp');

        $conn = null;

        return $objs;
    }

    static function get($id) {
        global $TABLE;

        $conn = connect();

        $SELECT_ONE = 'SELECT * FROM '.self::TABLE.' where id = :id';

        $statement = $conn->prepare($SELECT_ONE);
        $statement->execute([':id' => $id]);
        $obj = $statement->fetchAll(PDO::FETCH_CLASS, 'Models\WfpWcp')[0];

        $conn = null;

        return $obj;
    }

    function save() {
        global $TABLE;

        $conn = connect();

        $INSERT = '
            INSERT INTO '.self::TABLE.' (
                permitee_name,
                permitee_address,
                permitee_photo_link,
                farm_name,
                farm_address,
                farm_photo_link,
                wcp_no,
                wfp_no,
                issuance_date,
                expiry_date
            )
            VALUES (
                :permitee_name,
                :permitee_address,
                :permitee_photo_link,
                :farm_name,
                :farm_address,
                :farm_photo_link,
                :wcp_no,
                :wfp_no,
                :issuance_date,
                :expiry_date
            )';

        $UPDATE = '
            UPDATE '.self::TABLE.' SET
                permitee_name = :permitee_name,
                permitee_address = :permitee_address,
                permitee_photo_link = :permitee_photo_link,
                farm_name = :farm_name,
                farm_address = :farm_address,
                farm_photo_link = :farm_photo_link,
                wcp_no = :wcp_no,
                wfp_no = :wfp_no,
                issuance_date = :issuance_date,
                expiry_date = :expiry_date
            WHERE id = :id';

        $statement = $statement = $conn->prepare($this->id == null ? $INSERT : $UPDATE);

        $params = [
            ':permitee_name' => $this->permitee_name,
            ':permitee_address' => $this->permitee_address,
            ':permitee_photo_link' => $this->permitee_photo_link,
            ':farm_name' => $this->farm_name,
            ':farm_address' => $this->farm_address,
            ':farm_photo_link' => $this->farm_photo_link,
            ':wcp_no' => strtolower($this->wcp_no),
            ':wfp_no' => strlower($this->wfp_no),
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

        $conn = null;
    }

    function delete() {
        global $TABLE;

        $conn = connect();

        $DELETE = 'DELETE FROM '.self::TABLE.' WHERE id = :id';

        if ($this->id != null) {
            $statement = $conn->prepare($DELETE);
            $statement->execute([':id' => $this->id]);
        }

        $conn = null;
    }

}
