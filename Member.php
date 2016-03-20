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

    public function insert($params) {
        /* 引数の形式
            $params = array(
                'name' => 'kashiwagi',
                'email' => 'kashiwagi@example.com'
                );
        */

        // SQLの宣言 membersテーブルへのINSERT文
        $sql = "insert into members (najdsaifodspjfiosdapjfiosdpjfisaodpjfiosdame, email, created_at, updated_at)
                values (:name, :email, :created_at, :updated_at)";

        // PDOStatementを準備
        $stmt = $this->dbh->prepare($sql);

        // bindParamする
        $now = date('Y-m-d H:i:s'); // 時刻を取得

        $stmt->bindParam(":name", $params['name']);
        $stmt->bindParam(":email", $params['email']);
        $stmt->bindParam(":created_at", $now);
        $stmt->bindParam(":updated_at", $now);

        // 実行する
        return $stmt->execute();


        // if ($stmt->execute()) {
        //     // 保存成功時
        //     echo '保存できたよ！！';
        //     return true;
        // } else {
        //     // 保存失敗
        //     echo '保存できませんでした...';
        //     return false
        // }
    }


}


$member = new Member();

$data_1 = array(
    'name' => 'kashiwagi',
    'email' => 'kashiwagi@example.com'
    );


if ($member->insert($data_1)) {
    echo '保存に成功しました!!!';
} else {
    echo '保存に失敗しました...';
}



/* CakePHPの場合
if ($this->Post->save($this->request->data)) {
    // 保存成功
    $this->Flash->success('保存に成功しました!!!');
} else {
    // 保存出来なかった場合
    $this->Flash->error('保存に失敗しました...')
}
*/

/*

// functions.php
function connectDb() {
    try
    {
        return new POD(DSN, DB_USER, DB_PASSWORD);
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
}


// config.php
define('DSN', 'mysql:host=localhost;dbname=sample_db;charset=utf8');
define('DB_USER', 'root');
define('DB_PASSWORD', '');

// index.php
require 'config.php';
require 'functions.php';


$dbh = connectDb();

$sql = "";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(":id", $id)
$stmt->execute();

*/






















