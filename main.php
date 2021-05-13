<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
// PHPmyadminに接続する。
$pdo=new PDO('mysql:host=localhost;dbname=shop;charset=utf8','root','zquickF6');
// REQUESTパラメーターを'command'に設定し、isset関数で確認。
if(isset($_REQUEST['command'])) {
// そのあとSwitch分でvalueの値を条件分岐する。
    switch ($_REQUEST['command']) {
 // このスクリプトの一番下にある追加用のボタンを押したとき下記のコードでSQLにアクセスし、項目を増やすことができる。
        case 'insert':
// ただし適当に押したらエラーが起こる用に設定するためempty関数で数字以外、また何も入力していなかったらボタンを押しても反応しないようにしている。
        if(empty($_REQUEST['name']) ||
           !preg_match('/[0-9]+/',$_REQUEST['price'])) break;
        $sql=$pdo->prepare('insert into product values(null,?,?)');
//  もし正しく入力できていたらexecute関数で項目を追加できるにように設定している。  
        $sql->execute(
            [htmlspecialchars($_REQUEST['name']), $_REQUEST['price']]);
        break;
//updateも上記と理屈が同じで正しく入力できていたらユーザーが入力した商品名と値段がデータに反映されるようになっている。
    case 'update':
        if(empty($_REQUEST['name']) ||
        !preg_match('/[0-9]+/',$_REQUEST['price'])) break;
     $sql=$pdo->prepare(
         'update product set name=?, price=? where id=?');
    $sql->execute([htmlspecialchars($_REQUEST['name']), $_REQUEST['price'],$_REQUEST['id']]);
     break;
//deleteは削除ボタンを押したら項目の内容を消去することができるように設定している。以下は上記と同じ仕組み。
     case 'delete':
        $sql=$pdo->prepare('delete from product where id=?');
        $sql->execute([$_REQUEST['id']]);
        break;
    }
}
//foreachでswitch文全部の内容を取得している。そしてechoにより、データを出力。
foreach($pdo->query('select * from product') as $row) {
    echo '<form class="ib" action="main.php" mathod="post">';
    echo '<input type="hidden" name="command" value="update">';
    echo '<input type="hidden" name="id" value="', $row['id'], '">';
    echo '<div class="td0">';
    echo $row['id'];
    echo '</div>';
    echo '<div class="td1">';
    echo '<input type="text" name="name" value="', $row['name'], '">';
    echo '</div>';
    echo '<div class="td1">';
    echo '<input type="text" name="price" value="', $row['price'], '">';
    echo '</div>';
    echo '<div class="td2">';
    echo '<input type="submit" value="更新">';
    echo '</div>';
    echo '</form>';
    echo '<form class="ib" action="main.php" method="post">';
    echo '<input type="hidden" name="command" value="delete">';
    echo '<input type="hidden" name="command" value="', $row['id'], '">';
    echo '<input type="submit" value="削除">';
    echo '</form>';
}
?> 
<!-- 以下は画面上に表示されるボタンのスクリプトである。 -->
<form action="main.php" method="post">
<input type="hidden" name="command" value="insert">
<div class="td0"></div>
<div class="td1"><input type="text" name="name"></div>
<div class="td1"><input type="text" name="price"></div>
<div class="td2"><input type="submit" value="追加"></div>
</form>
</body>
</html>