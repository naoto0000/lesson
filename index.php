<?php
require_once('function.php');

// ここからページネーション設定
// =======================

// データ取得のための変数
$count_sql = 'SELECT COUNT(*) as cnt FROM employees';

// ページ数を取得する。GETでページが渡ってこなかった時（最初のページ）は$pageに１を格納する。
if(isset($_GET['page'])) {
    $page = $_GET['page'];
}else{
    $page = 1;
}

$counts = $pdo -> query($count_sql);
$count = $counts -> fetch(PDO::FETCH_ASSOC);
$max_page = ceil($count['cnt'] / 5);

// ページの数字ボタンを最大５個のみ表示
if($page == 1 || $page == $max_page){
    $range = 4;
}elseif($page == 2 || $page == $max_page - 1){
    $range = 3;
}else{
    $range = 2;
}

// 件数表示
$from_record = ($page - 1) * 5 + 1;

if($page == $max_page && $count['cnt'] % 5 !== 0){
    $to_record = ($page - 1) * 5 + $count['cnt'] % 5;
}else{
    $to_record = $page * 5;
}

if($page > 1){
    $start = ($page * 5) - 5;
}else{
    $start = 0;
}

// 取得データを５件のみ表示
$base_sql = "SELECT * FROM `employees` LIMIT {$start},5";
$employees = $pdo->query($base_sql);
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
    
    <!-- 下のは仮で入れてるだけやから気にせんどいて！ -->
    <a href="registration.php">登録画面へ</a>

    <div class="search">
        <form action="search.php" method="get">

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
            <th></th>
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

                if($sex === '1'){
                    $sex = '男';
                }elseif($sex === '2'){
                    $sex = '女';
                }elseif($sex === '3'){
                    $sex = '不明';
                }
            ?>

            <tr class="table_contents">
                <td><?php echo $employee['name']; ?></td>
                <td><?php echo $employee['kana']; ?></td>
                <td><?php echo $sex;?></td>
                <td><?php echo $age; ?></td>
                <td><?php echo $employee['birthdate']; ?></td>
                <td><button name="edit_button" class="edit_button"><a href="edit.php?id=<?php echo $employee['id'] ?>" class="edit_link">編集</a></button></td>
            </tr>
            
        <?php endforeach; ?>
    </table>

    <!-- ここからページネーション設定 -->
    <p class="from_to"><?php echo $count['cnt'] ?>件中 <?php echo $from_record; ?> - <?php echo $to_record; ?> 件目を表示</p>

    <!-- 戻るボタン -->
    <div class="pagenation">

    <?php if($page >= 2): ?>
        <a href="index.php?page=<?php echo ($page - 1); ?>" class="page_feed">&laquo;</a>

    <?php else : ?>
        <span class="first_last_page">&laquo;</span>

    <?php endif; ?>

    <!-- ページ選択 -->
    <?php for($i = 1; $i <= $max_page; $i++) : ?>
        <?php if($i >= $page - $range && $i <= $page + $range) : ?>
            <?php if($i == $page) : ?>
                <span class="now_page_number"><?php echo $i; ?></span>
            <?php else: ?>
                <a href="?page=<?php echo $i; ?>" class="page_number"><?php echo $i; ?></a>
            <?php endif; ?>
        <?php endif; ?>
    <?php endfor; ?>

    <!-- 進むボタン -->
    <?php if($page < $max_page) : ?>
        <a href="index.php?page=<?php echo($page + 1); ?>" class="page_feed">&raquo;</a>
    <?php else: ?>
        <span class="first_last_page">&raquo;</span>
    <?php endif; ?>

    </div>
</main>
</body>
</html>