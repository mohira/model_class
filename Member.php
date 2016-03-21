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

    }

    public function findAll() {
        // SQL宣言
        $sql = "select * from members";

        // prepare()
        $stmt = $this->dbh->prepare($sql);

        // 実行
        $stmt->execute();

        // 値を返す
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function findOneById($id) {
        // SQL用意
        $sql = "select * from members where id = :id";

        // prepare()
        $stmt = $this->dbh->prepare($sql);

        // bindParam()
        $stmt->bindParam(":id", $id);

        // 実行
        $stmt->execute();

        // 取得したレコードを返す
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // $member->delete(3); ← みたいな感じで書くと動くように実装！！
    public function delete($id) {

        if ($this->findOneById($id)) {
            // 指定したIDを持つレコードが存在した時
            $sql = "delete from members where id = :id";

            $stmt = $this->dbh->prepare($sql);

            $stmt->bindParam(":id", $id);

            return $stmt->execute();

        }

        // レコードが見つからなかった時
        return false;
    }



}


$member = new Member();

$data_1 = array(
    'name' => 'kashiwagi',
    'email' => 'kashiwagi@example.com'
    );


// if ($member->insert($data_1)) {
//     echo '保存に成功しました!!!';
// } else {
//     echo '保存に失敗しました...';
// }


$members = $member->findAll();

// var_dump($members); // 全てのレコードが 配列形式 で 格納されている

// if ($record_1 = $member->findOneById(3)) {
//     echo 'レコードがありました！';
// } else {
//     echo 'ありませんでした。。。';
// }

$record_1 = $member->findOneById(4);

// var_dump($record_1);

// $record_not_find = $member->findOneById(000000);

// echo '<hr>';
// var_dump($record_not_find);


if ($member->delete(5)) {
    echo "削除できた！！";
} else {
    echo 'そんなデータはありませんでした';
}











