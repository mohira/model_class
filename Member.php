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
        // SQL宣言 → 引数で渡されるので書かなくてOK

        // プリペアードステートメントの準備
        $stmt = $this->dbh->prepare($sql);

        // bindParam()はせずに, PDOStatement::execute()に配列を渡す
        $stmt->execute($params);

        return $stmt;
    }

    public function insert($params) {

        // SQLの宣言 membersテーブルへのINSERT文
        $sql = "insert into members (name, email, created_at, updated_at)
                values (:name, :email, :created_at, :updated_at)";

        return $this->execute($sql, array(
            ':name'       => $params['name'],
            ':email'      => $params['email'],
            ':created_at' => date('Y-m-d H:i:s'),
            ':updated_at' => date('Y-m-d H:i:s')
            ));

        // // PDOStatementを準備
        // $stmt = $this->dbh->prepare($sql);

        // // bindParamする
        // $now = date('Y-m-d H:i:s'); // 時刻を取得

        // $stmt->bindParam(":name", $params['name']);
        // $stmt->bindParam(":email", $params['email']);
        // $stmt->bindParam(":created_at", $now);
        // $stmt->bindParam(":updated_at", $now);

        // // 実行する
        // return $stmt->execute();

    }

    public function findAll() {
        // SQL宣言
        $sql = "select * from members";

        return $this->execute($sql)->fetchAll(PDO::FETCH_ASSOC);

        // // prepare()
        // $stmt = $this->dbh->prepare($sql);

        // // 実行
        // $stmt->execute();


    }

    public function findOne($sql, $params = array()) {
        return $this->execute($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }

    public function findOneById($id) {
        // SQL用意
        $sql = "select * from members where id = :id";

        return $this->findOne($sql, array(':id' => $id));
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

    public function update($params) {

        // var_dump($params);

        // $params = array(
        //     'id' => 17,
        //     'name' => 'EDIT_NAME_2',
        // );

        // echo '<hr>';

        $oldRecord = $this->findOneById($params['id']);

        // $oldRecord = array(
        //     'id' => 17,
        //     'name' => 'kashiwagi',  // ← ココを編集した
        //     'email' => 'kashiwagi@example',
        //     'created_at' => '2016-03-21 09:44:08',
        //     'updated_at' => '2016-03-21 09:44:08';
        //     )

        // var_dump($oldRecord);

        // $final_params = array(
        //     'id' => 17,
        //     'name' => 'EDIT_NAME_2',  // ← ココが編集したいやつ
        //     'email' => 'kashiwagi@example',
        //     'created_at' => '2016-03-21 09:44:08',
        //     'updated_at' => '2016-03-21 09:44:08';
        //     )

        if (!$oldRecord) {
            // return false;
            return 'NotFound';
        }

        $params = array_merge($oldRecord, $params);
        // $params = array_merge($params, $oldRecord);
        // var_dump($params);
        // exit;

        // ここからはレコードが存在するときの処理 => 更新処理をする
        $sql = "update members set name = :name, email = :email,
                updated_at = :updated_at where id = :id";

        $stmt = $this->dbh->prepare($sql);

        $stmt->bindParam(":id"        , $params['id']);
        $stmt->bindParam(":name"      , $params['name']);
        $stmt->bindParam(":email"     , $params['email']);
        $stmt->bindParam(":updated_at", date('Y-m-d H:i:s'));

        return $stmt->execute();

    }

// 編集用データ
// $edit_data = array(
//     'id'    => 18, // 必ず渡される
//     'name'  => 'EDIT_NAME',
// );


// // // 編集処理
// $member->update($edit_data);




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

// $member->insert($data_2);


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

$record_1 = $member->findOneById(20);

var_dump($record_1);
exit;
// $record_not_find = $member->findOneById(000000);

// echo '<hr>';
// var_dump($record_not_find);


// if ($member->delete(5)) {
//     echo "削除できた！！";
// } else {
//     echo 'そんなデータはありませんでした';
// }

// 編集用データ
$edit_data = array(
    'id' => 168888888888888888888888888888,
    'name' => 'EDIT_NAME_2222222222222',
    // 'email' => 'EDIT_NAME@example.com',
);


// 編集処理
$result = $member->update($edit_data);
if ($result === 'NotFound') {
    echo 'レコードが見つかりませんでした';
} elseif ($result === true) {
    echo '編集に成功しました！';
} else {
    echo '編集できませんでした。。。';
}

var_dump($member->update($edit_data));








