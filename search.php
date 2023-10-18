<?php
require_once('function.php');

try{
// $_POST['word']で入力値を取得 文字前後の空白除去&エスケープ処理
$name = trim(htmlspecialchars($_POST['search_name'],ENT_QUOTES));
// 文字列の中の「　」(全角空白)を「」(何もなし)に変換
$name = str_replace("　","",$name);


$base_sql = 'select * from employees ';
$where_sql = '';

// 氏名検索時
if($name){
    $where_sql .= 'where (name like :word or kana like :word2) ';

    $base_sql .= $where_sql;

    $employees = $pdo->prepare($base_sql);
    $employees->bindValue(':word',"%{$name}%",PDO::PARAM_STR);
    $employees->bindValue(':word2',"%{$name}%",PDO::PARAM_STR);

}

// 性別検索時
if($_POST['search_sex']){
    if($where_sql){
        $base_sql .= 'and sex = :sex';

        $employees = $pdo->prepare($base_sql);

        $employees->bindValue(':word',"%{$name}%",PDO::PARAM_STR);
        $employees->bindValue(':word2',"%{$name}%",PDO::PARAM_STR);    
        $employees->bindValue(':sex',$_POST['search_sex'],PDO::PARAM_STR);



    }else{
        $where_sql .= 'where sex = :sex';

        $base_sql .= $where_sql;

        $employees = $pdo->prepare($base_sql);
        $employees->bindValue(':sex',$_POST['search_sex'],PDO::PARAM_STR);

    }
}
      
    // 実行処理
    $employees->execute();

    $count = $employees->rowCount();


}catch(PDOException $e) {
    echo $e->getMessage();
}

$sexCotegory = [
    ['value' => '', 'text' => '全て'],
    ['value' => '1', 'text' => '男'],
    ['value' => '2', 'text' => '女'],
    ['value' => '3', 'text' => '不明'],
];


$pdo = null;

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
<a href="index.php"><h1>社員一覧</h1></a>

</header>


<main>

    <div class="search">
        <form action="" method="post">

        <label for="">氏名</label>
        <input type="text" name="search_name" class="input_name" value="<?php  echo $_POST['search_name'] ?>">
        <label for="">性別</label>
        <select name="search_sex" class="select_sex" value="">

        <?php 
        foreach($sexCotegory as $row){
            if($_POST['search_sex'] == $row['value']){
                echo '<option value="'. $row['value'] . '"selected>' . $row['text'] . '</option>';
            }else{
                echo '<option value="'. $row['value'] . '">' . $row['text'] . '</option>';
            }
        }
        ?>

        </select>

        <input type="submit" name="search_submit" value="検索" class="search_submit">

        </form>

    </div>


    <?php if($count == 0): ?>
    

    <p class="search_none">該当する社員がいません</p>

    <?php else: ?>

    <table>
        <tr class="table_title">
            <th>氏名</th>
            <th>かな</th>
            <th>性別</th>
            <th>年齢</th>
            <th>生年月日</th>
        </tr>


    <?php foreach($employees as $employee) : ?>


       <?php
        // 現在日付
        $now = date('Ymd');

        // 誕生日
        $birthday = $employee['birthdate'] ;
        $birthday = str_replace("-", "", $birthday);

        // 年齢
        $age = floor(($now - $birthday) / 10000);


        // 性別
        $sex = $employee['sex'];

        if($sex == 1){
            $sex = '男';
        }elseif($sex == 2){
            $sex = '女';
        }else{
            $sex = '不明';
        }

        ?>
        <tr class="table_contents">
            <td><?php echo $employee['name']; ?></td>
            <td><?php echo $employee['kana']; ?></td>
            <td><?php echo $sex;?></td>
            <td><?php echo $age; ?></td>
            <td><?php echo $employee['birthdate']; ?></td>
        </tr>
        

    <?php endforeach; ?>


    </table>

<?php endif; ?>

</main>
</body>
</html>