<?php

class Model {
    public $dbh;
    public $dsn  = 'mysql:host=localhost;dbname=sample_db;charset=utf8';
    public $user = 'root';
    public $pass = '';

    public $tableName; // テーブル名を保持

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
        $sql = sprintf('select * from %s', $this->tableName);

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


class Member extends Model {
    public $dbh;

    public $tableName = 'members';
}


$member = new Member();


// /*** 追加 ***/

// $data_1 = array(
//     'name' => 'Kashiwagi',
//     'email' => 'kashiwagi@example.com',
// );

// $member->insert($data_1);



// /*** 取得 ***/
// $record_1 = $member->findOneById(1);
$records  = $member->findAll();
var_dump($records);


// /*** 削除 ***/
// $member->delete(1);


// /*** 編集 ***/
// $edit_data = array(
//     'id' => 2,
//     'name' => 'EDIT_NAME',
//     'email' => 'EDIT_NAME@example.com',
// );

// $member->update($edit_data);