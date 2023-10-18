<?php
require_once('function.php');
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
        <form action="search.php" method="post">

        <label for="">氏名</label>
        <input type="text" name="search_name" class="input_name">
        <label for="">性別</label>
        <select name="search_sex" class="select_sex">
            <option value="">全て</option>
            <option value="1">男</option>
            <option value="2">女</option>
            <option value="3">不明</option>
        </select>

        <input type="submit" name="search_submit" value="検索" class="search_submit">

        </form>

    </div>

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
        }elseif($sex == 3){
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
</main>
</body>
</html>