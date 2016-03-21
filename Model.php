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
        /*
        $data_1 = array(
            'name' => 'Kashiwagi',
            'email' => 'kashiwagi@example.com',
        );
        */

        $columns = array_keys($params);

        $columns[] = 'created_at';
        $columns[] = 'updated_at';

        $parameters = array();
        /*
            'プレースホルダ' => 値,

            ':name' => 'ohira',
            ':email' => 'ohira@example.com'

        */
        $now = date('Y-m-d H:i:s');
        $params['created_at'] = $now;
        $params['updated_at'] = $now;

        foreach ($columns as $column) {

            if (isset($params[$column])) {
                $parameters[':'.$column] = $params[$column];
            }
        }

        // var_dump($parameters);
        // var_dump($columns);
        $sql = "insert into
                members
                (name, email, created_at, updated_at)
                values
                (:name, :email, :created_at, :updated_at)";


        $sql = sprintf('insert into %s (%s) values (%s)',
                $this->tableName,
                implode(',', $columns),
                implode(',', array_keys($parameters))
            );

        // var_dump($sql);

        return $this->execute($sql, $parameters);
    }

    public function findAll() {
        $sql = sprintf('select * from %s', $this->tableName);

        return $this->execute($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findOne($sql, $params = array()) {

        return $this->execute($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }

    public function findOneById($id) {
        $sql = sprintf('select * from %s where id = :id', $this->tableName);

        return $this->findOne($sql, array(':id' => $id));
    }

    public function delete($id) {
        if ($this->findOneById($id)) {
            $sql = sprintf('delete from %s where id = :id', $this->tableName);

            $this->execute($sql, array(':id' => $id));

            return true;
        }

        return false;
    }

    public function update($params) {

        // 既存レコードの取得

        if (empty($params['id'])) {
            return false;
        }

        $id = $params['id'];

        $oldRecord = $this->findOneById($id);

        if (!$oldRecord) {
            return false;
        }

        // SQLを作る
        unset($params['id']);

        $params['updated_at'] = date('Y-m-d H:i:s');

        // var_dump($params);exit;

        $parameters = array();

        $columns = array_keys($params);

        foreach ($columns as $column) {
            if (isset($params[$column])) {
                $parameters[':'.$column] = $params[$column];
            }
        }

        // var_dump($parameters);exit;

        $sql = "update members set name = :name, email = :email,
                updated_at = :updated_at where id = :id";

        $update = array(); // カラム名 = :カラム名 というパーツ

        foreach ($columns as $column) {
            $update[] = $column . ' = :' . $column;
        }

        // var_dump($update);exit;
        // var_dump(implode(',', $update));

        $sql = sprintf('update %s set %s where id = %d',
                $this->tableName, // テーブル名
                implode(',', $update), // カラム1 = :カラム1, カラム2 = :カラム名2 ,...
                $id  // 対象となるレコードのid
            );

        // 実行します
        return $this->execute($sql, $parameters);

    }

}


class Member extends Model {
    public $tableName = 'members';
}


$member = new Member();


// /*** 追加 ***/

$data_1 = array(
    'name' => 'Kashiwagi',
    'email' => 'kashiwagi@example.com',
);

// $member->insert($data_1);



// /*** 取得 ***/
// $record_1 = $member->findOneById(40);
// var_dump($record_1);

// $records  = $member->findAll();
// var_dump($records);


// /*** 削除 ***/
// $member->delete(42);


// /*** 編集 ***/
$edit_data = array(
    'id' => 63,
    'name' => 'EDIT_NAME',
    'email' => 'EDIT_NAME@example.com',
);

$member->update($edit_data);