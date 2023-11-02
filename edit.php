<?php 

require_once('function.php');

// 二重送信防止対策
// 
session_start();

$id = $_GET['id'];

// POSTされたトークンとセッション変数のトークンの比較
    if(isset($_POST['edit_submit'])){
        if($_POST['edit_name'] !== "" && $_POST['edit_kana'] !== ""){
            if($_POST['token'] !== "" && $_POST['token'] == $_SESSION["token"]) {
                $sql = 'UPDATE employees SET name = :name, kana = :kana, sex = :sex, birthdate = :birthdate WHERE id = :id';
            try{
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id',$id,PDO::PARAM_STR);
            $stmt->bindValue(':name',$_POST['edit_name'],PDO::PARAM_STR);
            $stmt->bindValue(':kana',$_POST['edit_kana'],PDO::PARAM_STR);
            $stmt->bindValue(':sex',$_POST['edit_sex'],PDO::PARAM_STR);
            $stmt->bindValue(':birthdate',$_POST['edit_birth'],PDO::PARAM_STR);
    
            $stmt->execute();
    
            echo "更新しました";

    
            }catch(PDOException $e){
            echo $e->getMessage();
            }
        }else {
            echo"ERROR：不正な登録処理です";
        }
    }
}

$sexCotegory = [
    ['value' => '3', 'text' => '選択'],
    ['value' => '1', 'text' => '男'],
    ['value' => '2', 'text' => '女']
];

//トークンをセッション変数にセット
$_SESSION["token"] = $token = mt_rand();

// 編集対象のデータをidをもとにとってくる
try{
    $edit_sql = "SELECT * FROM employees WHERE id = :id";
    $edit_stmt = $pdo->prepare($edit_sql);
    $edit_stmt->bindValue(":id", $id);
    $edit_stmt->execute();
}catch(PDOException $e){
    echo $e->getMessage();
}

// 取得できたデータを変数に入れておく
$edit_row = $edit_stmt->fetch(PDO::FETCH_ASSOC);
$name = $edit_row['name'];
$kana = $edit_row['kana'];
$sex = $edit_row['sex'];
$birthdate = $edit_row['birthdate'];

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
    <a href="index.php"><h1>社員編集</h1></a>
</header>

<main>

<div class="registration">
    <form action="" method="post">

        <div class="regi_class">
            <div class="regi_indi">
                <label class="regi_label">氏名</label>
                <p class="indi_mes">必須</p>
            </div>
                <!-- 入力データ保持の条件分岐 -->
                <?php if(isset($_POST['edit_submit']) && $_POST['edit_name'] !== "" && $_POST['edit_kana'] !== ""): ?>
                    <input type="text" name="edit_name" class="regi_name">
                <?php else: ?>

                    <?php if(isset($_POST['edit_name'])) :?>
                        <!-- 編集があった場合、その内容を保持 -->
                        <input type="text" name="edit_name" class="regi_name" value="<?php echo $_POST['edit_name']; ?>">
                    <?php else: ?>
                        <!-- index.phpからのデータを保持 -->
                        <input type="text" name="edit_name" class="regi_name" value="<?php echo $name; ?>">
                    <?php endif; ?>
                <?php endif; ?>

            <span class="indi">
                <?php 
                if(isset($_POST['edit_submit'])){
                    if(empty($_POST['edit_name'])){
                        echo "入力必須項目です";
                    }  
                }
                ?>
            </span>
        </div>

        <div class="regi_class">
            <div class="regi_indi">
                <label class="regi_label">かな</label>
                <p class="indi_mes">必須</p>
            </div>
                <!-- 入力データ保持の条件分岐 -->
                <?php if(isset($_POST['edit_submit']) && $_POST['edit_name'] !== "" && $_POST['edit_kana'] !== ""): ?>
                    <input type="text" name="edit_kana" class="regi_name">
                <?php else: ?>
                    <?php if(isset($_POST['edit_kana'])) :?>
                        <input type="text" name="edit_kana" class="regi_name" value="<?php echo $_POST['edit_kana']; ?>">
                    <?php else: ?>
                        <input type="text" name="edit_kana" class="regi_name" value="<?php echo $kana; ?>">
                    <?php endif; ?>
                <?php endif; ?>
            <span class="indi">
                <?php 
                if(isset($_POST['edit_submit'])){
                    if(empty($_POST['edit_kana'])){
                        echo "入力必須項目です";
                    }  
                }
                ?>
            </span>

        </div>

        <div class="regi_class">
            <label class="regi_label">性別</label>
            <select name="edit_sex" class="regi_sex">

                <!-- 入力データ保持の条件分岐 -->
                <?php if(isset($_POST['edit_submit']) && $_POST['edit_name'] !== "" && $_POST['edit_kana'] !== ""): ?>
                    <option value="">選択</option>
                    <option value="1">男</option>
                    <option value="2">女</option>

                <?php else: ?>
                    <!-- 編集があった場合、その内容を保持 -->
                    <?php if(isset($_POST['edit_sex'])): ?>
                        <?php 
                        foreach($sexCotegory as $row){
                            if($_POST['edit_sex'] == $row['value']){
                                echo '<option value="'. $row['value'] . '"selected>' . $row['text'] . '</option>';
                            }else{
                                echo '<option value="'. $row['value'] . '">' . $row['text'] . '</option>';
                            }
                        }
                        ?>
                    <!-- index.phpからのデータを保持 -->
                    <?php else: ?>
                        <?php 
                        foreach($sexCotegory as $row){
                            if($sex == $row['value']){
                                echo '<option value="'. $row['value'] . '"selected>' . $row['text'] . '</option>';
                            }else{
                                echo '<option value="'. $row['value'] . '">' . $row['text'] . '</option>';
                            }
                        }
                        ?>
                    <?php endif; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="regi_class">
        <label class="regi_label">生年月日</label>

            <!-- 入力データ保持の条件分岐 -->
            <?php if(isset($_POST['edit_submit']) && $_POST['edit_name'] !== "" && $_POST['edit_kana'] !== ""): ?>
                <input type="date" name="edit_birth" max="9999-12-31" class="regi_birth">

            <?php else: ?>
                <?php if(isset($_POST['edit_birth'])): ?>
                    <input type="date" name="edit_birth" max="9999-12-31" class="regi_birth" value="<?php echo $_POST['edit_birth']; ?>">
                <?php else: ?>
                    <input type="date" name="edit_birth" max="9999-12-31" class="regi_birth" value="<?php echo $birthdate; ?>">
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <input type="hidden" name="token" value="<?php echo $token;?>">

        <input type="submit" name="edit_submit" value="保存" class="regi_submit">
        
    </form>
</div>

</main>

</body>
</html>