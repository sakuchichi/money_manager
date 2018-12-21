<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>エラー！</title><!--このページが表示されるのはエラーを吐いた時のみなので、タイトルはこれでよい-->
</head>
<body>
<?php
require_once 'ConnectDb.php';

//POST情報で取得したデータを対応する変数に格納
$year = $_POST['year'];
$day = $_POST['day'];
$month = $_POST['month'];
$hour = $_POST['hour'];
$min = $_POST['min'];
$price_id = $_POST['price_id'];
$comment = $_POST['remarks'];
$method = $_POST['exp_inc'];
var_dump($_POST['price'],$_POST['category'],$_POST['exp_inc'],$_POST['remarks']);

//date関数とmktime関数でPOST情報をDATETIME型で扱える形に整形
$shape_dt = date('Y-m-d H:i:s', mktime($hour, $min, 0, $month, $day, $year));

    if(checkdate($month,$day,$year)){//checkdate関数で年月日が妥当かをチェック

            if($hour >= 0 && $hour <= 23){//ifで分岐し、0時以上23時以下かチェック
            }else{//当てはまらない場合にはプログラムを終了
            ?>
                <a href="http://localhost/money_manager/add_form.php"> 不正な日時が入力されました。こちらのリンクから入力をもう一度入力を行ってください。</a>
            <?=exit;}

            if($min >= 0 && $min <=59){//ifで分岐し、0分以上59分以下かチェック
            }else{//当てはまらない場合にはプログラムを終了
            ?>
            <a href="http://localhost/money_manager/add_form.php"> 不正な日時が入力されました。こちらのリンクから入力をもう一度入力を行ってください。</a>
            <?=exit;}

    } else {//年月日が妥当でない場合にはプログラムを終了
    ?>
    <a href="http://localhost/money_manager/add_form.php"> 不正な日時が入力されました。こちらのリンクから入力をもう一度入力を行ってください。</a>
    <?=exit;}

try{
    $db = ConnectDb();

        //priceテーブルへ送信するデータをINSERT命令にセットする
        $setdb_price = $db->prepare('UPDATE price SET date = '.$shape_dt.', price = '.$_POST['price'].'WHERE id ='.$price_id.'');

        $setdb_price->execute();

            //priceの最大IDを取得し、$max_idに代入
            $price_maxid = $db->prepare('UPDATE price_meta SET category = '.$_POST['category'].', method = '.$method.', comment = '.$comment.' WHERE price_id ='.$price_id.'');

            //INSERT命令を実行
            $price_maxid->execute();


            //すべての処理が無事に終了したら収支の登録ページにリダイレクト（仮）
            header('Location: http://localhost/money_manager/top.php');

}catch(PDOException $e){
    echo $e->getMessage;
}
?>
</body>
</html>