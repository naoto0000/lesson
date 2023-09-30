
<?php 

ini_set('log_errors','on');  //ログを取るか
ini_set('error_log','php_error.log');  //ログの出力ファイルを指定

$comment_array = array();

try {
    $pdo = new PDO('mysql:host=localhost;dbname=ad5', "root", "root");
} catch (PDOException $e) {
    echo $e->getMessage();
}

$sql = "SELECT * FROM `lesson`";
$comment_array = $pdo->query($sql);



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AD5 lesson</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css">

</head>
<body>

<header>
    <h1>社員一覧</h1>

</header>
    

<main>
    <table>
        <tr class="table_title">
            <th>氏名</th>
            <th>かな</th>
            <th>性別</th>
            <th>年齢</th>
            <th>生年月日</th>
        </tr>
  
    <?php foreach($comment_array as $comment) : ?>
       
       <?php
        // 現在日付
        $now = date('Ymd');

        // 誕生日
        $birthday = $comment['birthdate'] ;
        $birthday = str_replace("-", "", $birthday);

        // 年齢
        $age = floor(($now - $birthday) / 10000);


        // 性別
        if($comment['sex'] == 1){
            $comment['sex'] = '男';
        }elseif($comment['sex'] == 2){
            $comment['sex'] = '女';
        }else{
            $comment['sex'] = '不明';
        }

        ?>


        <tr class="table_contents">
            <td><?php echo $comment['name']; ?></td>
            <td><?php echo $comment['kana']; ?></td>
            <td><?php echo $comment['sex'];?></td>
            <td><?php echo $age; ?></td>
            <td><?php echo $comment['birthdate']; ?></td>
        </tr>
        
    <?php endforeach; ?>
    </table>
</main>
</body>
</html>