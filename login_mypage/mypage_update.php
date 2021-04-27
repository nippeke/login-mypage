<?php
mb_internal_encoding("utf8");

//セッションスタート
session_start();

//DB接続・try catch文
try{
    $pdo=new PDO("mysql:dbname=lesson01;host=localhost;","root","");
}catch(PDOException $e){
    die("<p>申し訳ございません。現在サーバーが混み合っており一時的にアクセスが出来ません。<br>しばらくしてから再度ログインをしてください。</p>
        <a href='http://localhost/login_mypage/login.php'>ログイン画面へ</a>"
       );
}

//preparedステートメント(update)でSQLをセット$ //bindValueメッソドでパラメータをセット
$stmt=$pdo->prepare("update login_mypage set name = ?, mail = ?, password = ?, comments = ? where id = ?");

$stmt->bindValue(1,$_POST['name']);
$stmt->bindValue(2,$_POST['mail']);
$stmt->bindValue(3,$_POST['password']);
$stmt->bindValue(4,$_POST['comments']);
$stmt->bindvalue(5,$_SESSION['id']);

//executeでクエリを実行
$stmt->execute();

$stmt=$pdo->prepare("select * from login_mypage where mail = ? && password = ?");

$stmt->bindValue(1,$_POST['mail']);
$stmt->bindValue(2,$_POST['password']);

$stmt->execute();

//fetch・while文でデータを取得し、sessionに代入
while($row=$stmt->fetch()){
    $_SESSION['id']=$row['id'];
    $_SESSION['name']=$row['name'];
    $_SESSION['mail']=$row['mail'];
    $_SESSION['password']=$row['password'];
    $_SESSION['picture']=$row['picture'];
    $_SESSION['comments']=$row['comments'];
}

//データベースを切断
$stmt=NULL;

//mypage.phpへリダイレクト
header("Location: mypage.php");

?>