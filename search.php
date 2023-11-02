<?php
require_once('function.php');

if(isset($_GET['page'])) {
    $page = $_GET['page'];
}else{
    $page = 1;
}

if($page > 1){
    $start = ($page * 5) - 5;
}else{
    $start = 0;
}

try{
// 入力値を取得 文字前後の空白除去&エスケープ処理
$name = trim(htmlspecialchars($_GET['search_name'],ENT_QUOTES));
// 文字列の中の「　」(全角空白)を「」(何もなし)に変換
$name = str_replace("　","",$name);

$search_sex = $_GET["search_sex"];

$base_sql = "SELECT * FROM `employees` ";
$where_sql = "";
$limit_sql = "";

if($name == "" && $search_sex == ""){

    // ここでは検索結果と件数を取得
    $employees = $pdo->prepare($base_sql);
    $employees->execute();
    $search_count = $employees->rowCount();

    // ここでは上記で取得したデータを５件のみの表示にする
    $limit_sql = "SELECT * FROM `employees` LIMIT {$start},5";
    $employees = $pdo->query($limit_sql);

}else{
    // 氏名のみ入力時
if($name){
    $where_sql .= "WHERE (name LIKE :word OR kana LIKE :word2)";
    $base_sql .= $where_sql;
    $employees = $pdo->prepare($base_sql);
    $employees->bindValue(':word',"%{$name}%",PDO::PARAM_STR);
    $employees->bindValue(':word2',"%{$name}%",PDO::PARAM_STR);
    $employees->execute();

    $search_count = $employees->rowCount();

    $limit_sql = "SELECT * FROM `employees` WHERE (name LIKE :word OR kana LIKE :word2) LIMIT {$start},5";
    $employees = $pdo->prepare($limit_sql);
    $employees->bindValue(':word',"%{$name}%",PDO::PARAM_STR);
    $employees->bindValue(':word2',"%{$name}%",PDO::PARAM_STR);
    $employees->execute();

}
// 性別検索時
if($search_sex){
    // 指名も性別も入力されている時
    if($where_sql){
        $base_sql .= " AND sex = :sex";
        $employees = $pdo->prepare($base_sql);
        $employees->bindValue(':word',"%{$name}%",PDO::PARAM_STR);
        $employees->bindValue(':word2',"%{$name}%",PDO::PARAM_STR);    
        $employees->bindValue(':sex',$search_sex,PDO::PARAM_STR);
        $employees->execute();

        $search_count = $employees->rowCount();

        $limit_sql = "SELECT * FROM `employees` WHERE (name LIKE :word OR kana LIKE :word2) AND sex = :sex LIMIT {$start},5";
        $employees = $pdo->prepare($limit_sql);
        $employees->bindValue(':word',"%{$name}%",PDO::PARAM_STR);
        $employees->bindValue(':word2',"%{$name}%",PDO::PARAM_STR);
        $employees->bindValue(':sex',$search_sex,PDO::PARAM_STR);
        $employees->execute();
    
    // 性別のみ入力時
    }else{
        $where_sql .= "WHERE sex = :sex";
        $base_sql .= $where_sql;
        $employees = $pdo->prepare($base_sql);
        $employees->bindValue(':sex',$search_sex,PDO::PARAM_STR);
        $employees->execute();

        $search_count = $employees->rowCount();

        $limit_sql = "SELECT * FROM `employees` WHERE sex = :sex  LIMIT {$start},5";
        $employees = $pdo->prepare($limit_sql);
        $employees->bindValue(':sex',$search_sex,PDO::PARAM_STR);
        $employees->execute();
    }
}

}

$max_page = ceil($search_count / 5);

if($page == 1 || $page == $max_page){
    $range = 4;
}elseif($page == 2 || $page == $max_page - 1){
    $range = 3;
}else{
    $range = 2;
}

$from_record = ($page - 1) * 5 + 1;

if($page == $max_page && $search_count % 5 !== 0){
    $to_record = ($page - 1) * 5 + $search_count % 5;
}else{
    $to_record = $page * 5;
}

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
        <form action="" method="get">
            <label for="">氏名</label>
                <input type="text" name="search_name" class="input_name" value="<?php  echo $name ?>">
            <label for="">性別</label>
                <select name="search_sex" class="select_sex" value="">
                    <?php 
                        foreach($sexCotegory as $row){
                            if($search_sex == $row['value']){
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

    <?php if($search_count == 0): ?>
        <p class="search_none">該当する社員がいません</p>
    <?php else: ?>
        <table>
            <tr class="table_title">
                <th>氏名</th>
                <th>かな</th>
                <th>性別</th>
                <th>年齢</th>
                <th>生年月日</th>
                <th> </th>
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
                    <td><button name="edit_button" value="編集" class="edit_button"><a href="edit.php?id=<?php echo $employee['id'] ?>" class="edit_link">編集</a></button></td>
                </tr>
                
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

<!-- 検索結果が５件未満の場合ページネーションを表示させない -->
<?php if($search_count > 6): ?>

    <!-- ここからページネーション設定 -->
        <p class="from_to"><?php echo $search_count ?>件中 <?php echo $from_record; ?> - <?php echo $to_record; ?> 件目を表示</p>

        <!-- 戻るボタン -->
    <div class="pagenation">

        <?php if($page >= 2): ?>
            <!-- ここで検索結果のデータを２ページ以降に渡している -->
            <a href="search.php?page=<?php echo ($page - 1); ?> & search_name=<?php echo $name; ?> & search_sex=<?php echo $search_sex; ?>" class="page_feed">&laquo;</a>

        <?php else : ?>
            <span class="first_last_page">&laquo;</span>

        <?php endif; ?>

        <!-- ページ選択 -->
        <?php for($i = 1; $i <= $max_page; $i++) : ?>
            <?php if($i >= $page - $range && $i <= $page + $range) : ?>
                <?php if($i == $page) : ?>
                <span class="now_page_number"><?php echo $i; ?></span>
                <?php else: ?>
                <a href="?page=<?php echo $i; ?>& search_name=<?php echo $name; ?>& search_sex=<?php echo $search_sex; ?>" class="page_number"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endif; ?>
        <?php endfor; ?>

            <!-- 進むボタン -->
        <?php if($page < $max_page) : ?>
            <a href="search.php?page=<?php echo($page + 1); ?>& search_name=<?php echo $name; ?>& search_sex=<?php echo $search_sex; ?>" class="page_feed">&raquo;</a>
        <?php else: ?>
            <span class="first_last_page">&raquo;</span>
        <?php endif; ?>

    </div>

<?php endif; ?>

</main>
</body>
</html>