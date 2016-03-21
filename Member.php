<?php

class Member {
    public $dbh;
    public $dsn  = 'mysql:host=localhost;dbname=sample_db;charset=utf8';
    public $user = 'root';
    public $pass = '';

    public function __construct() {
        try {
            $this->dbh = new PDO($this->dsn, $this->user, $this->pass);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function execute($sql, $params = array()) {
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    public function insert($params) {
        $sql = "insert into members (name, email, created_at, updated_at)
                values (:name, :email, :created_at, :updated_at)";

        return $this->execute($sql, array(
            ':name'       => $params['name'],
            ':email'      => $params['email'],
            ':created_at' => date('Y-m-d H:i:s'),
            ':updated_at' => date('Y-m-d H:i:s')
            ));
    }

    public function findAll() {
        $sql = "select * from members";

        return $this->execute($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findOne($sql, $params = array()) {
        return $this->execute($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }

    public function findOneById($id) {
        $sql = "select * from members where id = :id";

        return $this->findOne($sql, array(':id' => $id));
    }

    public function delete($id) {
        if ($this->findOneById($id)) {
            $sql = "delete from members where id = :id";
            $this->execute($sql, array(':id' => $id));

            return true;
        }

        return false;
    }

    public function update($params) {
        $oldRecord = $this->findOneById($params['id']);

        if (!$oldRecord) {
            return 'NotFound';
        }
        $params = array_merge($oldRecord, $params);
        $sql = "update members set name = :name, email = :email,
                updated_at = :updated_at where id = :id";

        $this->execute($sql, array(
            ':id'         => $params['id'],
            ':name'       => $params['name'],
            ':email'      => $params['email'],
            ':updated_at' => date('Y-m-d H:i:s')
            ));

        return true;
    }
}


$member = new Member();

$data_1 = array(
    'name' => 'kashiwagi',
    'email' => 'kashiwagi@example.com'
    );

$data_2 = array(
    'name' => 'ohira',
    'email' => 'ohira@example.com'
    );

// $members = $member->findAll();

// $record_1 = $member->findOneById(20);
// var_dump($record_1);

$edit_data = array(
    'id' => 20,
    'name' => 'EDIT_NAME_2222222222222',
);

// $member->update($edit_data);








