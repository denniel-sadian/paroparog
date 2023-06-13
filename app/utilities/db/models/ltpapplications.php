<?php
namespace Models;

use PDO;

require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/db/connection.php';
require_once '/var/www/utilities/db/models/helper_functions.php';
require_once '/var/www/utilities/db/models/dtos.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/transport_entries.php';
require_once '/var/www/utilities/db/models/payment_order.php';

use Models\User;
use Models\UserType;
use Models\TransportEntry;
use Models\PaymentOrder;
use DTOs\PageRequest;
use DTOs\Page;
use DTOs\Search;
use DTOs\Sort;


class Status {
    const DRAFT = 'DRAFT';
    const SUBMITTED = 'SUBMITTED';
    const ACCEPTED = 'ACCEPTED';
    const RELEASED = 'RELEASED';
}


class LtpApplication {

    const TABLE = 'ltpapplications';

    public $id;
    public $no;
    public $client_id;
    public $client;
    public $permit_signatory_id;
    public $permit_signatory = null;
    public $permit_signature_link;
    public $issuing_personnel_id;
    public $releasing_personnel_id;
    public $releasing_personnel;
    public $status;
    public $created_at;
    public $updated_at;
    public $submitted_at;
    public $accepted_at;
    public $returned_at;
    public $remarks = '';
    public $issuance_date;
    public $release_date;
    public $validity_date;
    public $veterinary_quarantine_cert_link;
    public $supporting_docs_link;
    public $transport_address;
    public $transport_date;
    public $inspection_report_link;

    public $payment_order = null;
    public $transport_entries = [];
    public $quantity = 0;

    function save() {
        $conn = connect();

        $INSERT = '
            INSERT INTO '.self::TABLE.' (
                no,
                client_id,
                permit_signatory_id,
                issuing_personnel_id,
                releasing_personnel_id,
                status,
                created_at,
                updated_at,
                returned_at,
                remarks,
                release_date,
                validity_date,
                veterinary_quarantine_cert_link,
                supporting_docs_link,
                transport_address,
                transport_date,
                submitted_at,
                accepted_at,
                issuance_date,
                inspection_report_link,
                permit_signature_link
            )
            VALUES (
                :no,
                :client_id,
                :permit_signatory_id,
                :issuing_personnel_id,
                :releasing_personnel_id,
                :status,
                :created_at,
                :updated_at,
                :returned_at,
                :remarks,
                :release_date,
                :validity_date,
                :veterinary_quarantine_cert_link,
                :supporting_docs_link,
                :transport_address,
                :transport_date,
                :submitted_at,
                :accepted_at,
                :issuance_date,
                :inspection_report_link,
                :permit_signature_link
            )';

        $UPDATE = '
            UPDATE '.self::TABLE.' SET
                no = :no,
                client_id = :client_id,
                permit_signatory_id = :permit_signatory_id,
                issuing_personnel_id = :issuing_personnel_id,
                releasing_personnel_id = :releasing_personnel_id,
                status = :status,
                created_at = :created_at,
                updated_at = :updated_at,
                returned_at = :returned_at,
                remarks = :remarks,
                release_date = :release_date,
                validity_date = :validity_date,
                veterinary_quarantine_cert_link = :veterinary_quarantine_cert_link,
                supporting_docs_link = :supporting_docs_link,
                transport_address = :transport_address,
                transport_date = :transport_date,
                submitted_at = :submitted_at,
                accepted_at = :accepted_at,
                issuance_date = :issuance_date,
                inspection_report_link = :inspection_report_link,
                permit_signature_link = :permit_signature_link
            WHERE id = :id';

        $statement = $statement = $conn->prepare($this->id == null ? $INSERT : $UPDATE);

        $params = [
            ':no' => strtoupper($this->no != null ? $this->no : ''),
            ':client_id' => $this->client_id,
            ':permit_signatory_id' => $this->permit_signatory_id,
            ':issuing_personnel_id' => $this->issuing_personnel_id,
            ':releasing_personnel_id' => $this->releasing_personnel_id,
            ':status' => $this->status,
            ':created_at' => $this->created_at,
            ':updated_at' => $this->updated_at,
            ':returned_at' => $this->returned_at,
            ':remarks' => $this->remarks,
            ':release_date' => $this->release_date,
            ':validity_date' => $this->validity_date,
            ':veterinary_quarantine_cert_link' => $this->veterinary_quarantine_cert_link,
            ':supporting_docs_link' => $this->supporting_docs_link,
            ':transport_address' => $this->transport_address,
            ':transport_date' => $this->transport_date,
            ':submitted_at' => $this->submitted_at,
            ':accepted_at' => $this->accepted_at,
            ':issuance_date' => $this->issuance_date,
            ':inspection_report_link' => $this->inspection_report_link,
            ':permit_signature_link' => $this->permit_signature_link
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
        $items = $statement->fetchAll(PDO::FETCH_CLASS, 'Models\LtpApplication');
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

    static function get($id) {
        $conn = connect();

        $SELECT_ONE = 'SELECT * FROM '.self::TABLE.' where id = :id';

        $statement = $conn->prepare($SELECT_ONE);
        $statement->execute([':id' => $id]);
        $obj = $statement->fetchAll(PDO::FETCH_CLASS, 'Models\LtpApplication')[0];
        $obj->fetch_related();

        $conn = null;

        return $obj;
    }

    function fetch_related() {
        if ($this->client_id != null) {
            $this->client = User::get($this->client_id);
        }
        if ($this->permit_signatory_id != null) {
            $this->permit_signatory = User::get($this->permit_signatory_id);
        }
        if ($this->releasing_personnel_id != null) {
            $this->releasing_personnel = User::get($this->releasing_personnel_id);
        }

        $this->transport_entries = TransportEntry::filter_all(Search::create(['ltpapp_id' => $this->id]));
        foreach ($this->transport_entries as $i) {
            $this->quantity += $i->quantity;
        }

        $payment_orders = PaymentOrder::filter(Search::create(['ltpapp_id' => $this->id]))->items;

        if (count($payment_orders) > 0) {
            $this->payment_order = $payment_orders[0];
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
