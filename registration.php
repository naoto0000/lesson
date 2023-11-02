<?php 
require_once('function.php');

session_start();

    // トークンの比較で二重送信対策
    if(isset($_POST['regi_submit'])){
        if($_POST['regi_name'] !== "" && $_POST['regi_kana'] !== ""){
            if($_POST['token'] !== "" && $_POST['token'] == $_SESSION["token"]) {
            $sql = 'INSERT INTO 
                        employees(name, kana, sex, birthdate)
                     VALUES
                        (:name, :kana, :sex, :birthdate)';
    
            try{
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':name',$_POST['regi_name'],PDO::PARAM_STR);
            $stmt->bindValue(':kana',$_POST['regi_kana'],PDO::PARAM_STR);
            $stmt->bindValue(':sex',$_POST['regi_sex'],PDO::PARAM_STR);
            $stmt->bindValue(':birthdate',$_POST['regi_birth'],PDO::PARAM_STR);
    
            $stmt->execute();
    
            echo "登録しました";

    
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
    ['value' => '2', 'text' => '女'],
];

//トークンをセッション変数にセット
$_SESSION["token"] = $token = mt_rand();

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

<div class="registration">
    <form action="" method="post">

        <div class="regi_class">
            <div class="regi_indi">
                <label class="regi_label">氏名</label>
                <p class="indi_mes">必須</p>
            </div>

            <!-- 入力データ保持の条件分岐 -->
            <?php if(isset($_POST['regi_submit']) && $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== ""): ?>
                <input type="text" name="regi_name" class="regi_name">
            <?php else: ?>
                <input type="text" name="regi_name" class="regi_name" value="<?php echo $_POST['regi_name']; ?>">
            <?php endif; ?>

            <span class="indi">
                <?php 
                if(isset($_POST['regi_submit'])){
                    if(empty($_POST['regi_name'])){
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
            <?php if(isset($_POST['regi_submit']) && $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== ""): ?>
                <input type="text" name="regi_kana" class="regi_name">
            <?php else: ?>
                <input type="text" name="regi_kana" class="regi_name" value="<?php echo $_POST['regi_kana']; ?>">
            <?php endif; ?>

            <span class="indi">
                <?php 
                if(isset($_POST['regi_submit'])){
                    if(empty($_POST['regi_kana'])){
                        echo "入力必須項目です";
                    }  
                }
                ?>
            </span>
        </div>

        <div class="regi_class">
            <label class="regi_label">性別</label>
            <select name="regi_sex" class="regi_sex">

                <!-- 入力データ保持の条件分岐 -->
                <?php if(isset($_POST['regi_submit']) && $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== ""): ?>
                    <option value="">選択</option>
                    <option value="1">男</option>
                    <option value="2">女</option>

                <?php else: ?>
                    <?php 
                    foreach($sexCotegory as $row){
                        if($_POST['regi_sex'] == $row['value']){
                            echo '<option value="'. $row['value'] . '"selected>' . $row['text'] . '</option>';
                        }else{
                            echo '<option value="'. $row['value'] . '">' . $row['text'] . '</option>';
                        }
                    }
                    ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="regi_class">
            <label class="regi_label">生年月日</label>

            <!-- 入力データ保持の条件分岐 -->
            <?php if(isset($_POST['regi_submit']) && $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== ""): ?>
                <input type="date" name="regi_birth" max="9999-12-31" class="regi_birth">
            <?php else: ?>
                <input type="date" name="regi_birth" max="9999-12-31" class="regi_birth" value="<?php echo $_POST['regi_birth']; ?>">
            <?php endif; ?>

        </div>

        <input type="hidden" name="token" value="<?php echo $token;?>">

        <input type="submit" name="regi_submit" value="登録" class="regi_submit">
    </form>
</div>
</main>
</body>
</html>