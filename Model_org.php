<?php

class Model {

    // 外部から上書きできないように
    protected $dbh;

    protected $tableName;

    protected $dsn = 'mysql:host=localhost;dbname=sample_db;charset=utf8';
    protected $user = 'root';
    protected $pass = '';

    public function __construct() {
        $this->dbh = new PDO($this->dsn, $this->user, $this->pass);
    }

    public function execute($sql, $params = array()) {
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }


    public function findAll() {
        $sql = sprintf("select * from %s", $this->tableName);

        return $this->execute($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findOne() {
        $sql = sprintf("select * from %s", $this->tableName);

        return $this->execute($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public function findOneById($id) {
        $sql = sprintf("select * from %s where id = :id", $this->tableName);

        return $this->findOne($sql, array(":id" => $id));
    }


    public function insert($params) {
        $columns = array_keys($params);

        if (!isset($params['created_at']))
        {
            $columns[] = 'created_at';
        }

        if (!isset($params['updated_at']))
        {
            $columns[] = 'updated_at';
        }

        $parameters = array();
        foreach ($columns as $column)
        {
            if (isset($params[$column]))
            {
                $parameters[':'.$column] = $params[$column];
            }
        }

        if (!in_array(':created_at', array_keys($parameters))) $parameters[':created_at'] = date('Y-m-d H:i:s');
        if (!in_array(':updated_at', array_keys($parameters))) $parameters[':updated_at'] = date('Y-m-d H:i:s');

        $sql = sprintf("insert into %s (%s) values (%s)",
                $this->tableName,
                implode(",", $columns),
                implode(",", array_keys($parameters))
            );

        $this->execute($sql, $parameters);
    }


    public function delete($id) {
        if ($this->findOneById($id))
        {
            $sql = sprintf("delete from %s where id = :id", $this->tableName);

            $this->execute($sql, array(":id" => $id));
        }

        return false;
    }


    public function update($params) {
        if (empty($params['id']))
        {
            return false;
        }

        $id = $params['id'];
        $oldRecord = $this->findOneById($id);
        if (!$oldRecord) {
            return false;
        }
        unset($params['id']);

        if (!isset($params['updated_at']))
        {
            $params['updated_at'] = date('Y-m-d H:i:s');
        }
        $columns = array_keys($params);

        $parameters = array();

        foreach ($columns as $column)
        {
            if (isset($params[$column]))
            {
                $parameters[':'.$column] = $params[$column];
            }
        }

        $update = array();
        foreach ($columns as $column)
        {
            $update[] = $column.' = :'.$column;
        }

        // $update = array_map(function($placeholder, $value) { return $placeholder.' = '.$value; }, $parameters);
        $update = implode(', ', $update);

        $sql = sprintf("update %s set %s where id = %d",
                $this->tableName,
                $update,
                $id
            );
        var_dump($sql);exit;

        $this->execute($sql, $parameters);
    }

}


$member = new Member();

$data_1 = array(
    'name' => 'Kashiwagi',
    'email' => 'kashiwagi@example.com',
);

$data_2 = array(
    'name' => 'Ohira',
    'email' => 'ohira@example.com',
);

$edit_data = array(
    'id' => 2,
    'name' => 'EDIT_NAME',
    'email' => 'EDIT_NAME@example.com',
);


$member->insert($data_1);
$member->insert($data_2);

$record_1 = $member->findOneById(1);
$records  = $member->findAll();

$member->delete(1);

$member->update($edit_data);


class Member extends Model {
    public $dbh;

    public $tableName = 'members';

}

$member = new Member();

$data_1 = array(
    'name' => 'Kashiwagi',
    'email' => 'kashiwagi@example.com',
);

$data_2 = array(
    'name' => 'Ohira',
    'email' => 'ohira@example.com',
);



// $member->insert($data_1);
// $member->insert($data_2);

// exit;
$record_1 = $member->findOneById(1);

// var_dump($record_1);

$records  = $member->findAll();
// var_dump($records);

// $member->delete(24);


$edit_data = array(
    'id' => 28,
    'name' => 'EDIT_NAME',
    'email' => 'EDIT_NAME@example.com',
);


$member->update($edit_data);
















