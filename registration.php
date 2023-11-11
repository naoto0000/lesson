<?php 
require_once('function.php');

session_start();

$_POST['regi_mail'] == "";

$mail_result = preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $_POST['regi_mail']);

var_dump($_POST['regi_mail']);
var_dump($mail_result);

kkkkk

    // トークンの比較で二重送信対策
    if(isset($_POST['regi_submit'])){
        if($_POST['regi_name'] !== "" && $_POST['regi_kana'] !== "" && $_POST['regi_mail'] !== "" && $mail_result == 1){
            if($_POST['token'] !== "" && $_POST['token'] == $_SESSION["token"]) {

            $sql = 'INSERT INTO employees(name, kana, sex, birthdate, email, comm_time, blood_type, married) VALUES (:name, :kana, :sex, :birthdate, :email, :comm_time, :blood_type, :married)';
                
            try{
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':name',$_POST['regi_name'],PDO::PARAM_STR);
            $stmt->bindValue(':kana',$_POST['regi_kana'],PDO::PARAM_STR);
            $stmt->bindValue(':sex',$_POST['regi_sex'],PDO::PARAM_INT);
            $stmt->bindValue(':birthdate',$_POST['regi_birth'],PDO::PARAM_STR);
            $stmt->bindValue(':email',$_POST['regi_mail'],PDO::PARAM_STR);
            $stmt->bindValue(':comm_time',$_POST['regi_com'],PDO::PARAM_STR);
            $stmt->bindValue(':blood_type',$_POST['regi_blood'],PDO::PARAM_INT);
            $stmt->bindValue(':married',$_POST['regi_married'],PDO::PARAM_INT);
    
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

$bloodCotegory = [
    ['value' => '1', 'text' => 'A型'],
    ['value' => '2', 'text' => 'B型'],
    ['value' => '3', 'text' => 'AB型'],
    ['value' => '4', 'text' => 'O型'],
    ['value' => '5', 'text' => '不明'],
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
            <?php if(isset($_POST['regi_submit']) && $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== "" && $_POST['regi_mail'] !== "" && $mail_result == 1): ?>
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
            <?php if(isset($_POST['regi_submit']) && $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== "" && $_POST['regi_mail'] !== "" && $mail_result == 1): ?>
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
                <?php if(isset($_POST['regi_submit']) && $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== "" && $_POST['regi_mail'] !== "" && $mail_result == 1): ?>
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
            <?php if(isset($_POST['regi_submit']) && $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== "" && $_POST['regi_mail'] !== "" && $mail_result == 1): ?>
                <input type="date" name="regi_birth" max="9999-12-31" class="regi_birth">
            <?php else: ?>
                <input type="date" name="regi_birth" max="9999-12-31" class="regi_birth" value="<?php echo $_POST['regi_birth']; ?>">
            <?php endif; ?>

        </div>

        <div class="regi_class">
            <div class="regi_indi">
                <label class="regi_label">メールアドレス</label>
                <p class="indi_mes">必須</p>
            </div>

            <!-- 入力データ保持の条件分岐 -->
            <?php if(isset($_POST['regi_submit']) && $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== "" && $_POST['regi_mail'] !== "" && $mail_result == 1): ?>
                <input type="text" name="regi_mail" class="regi_mail">
            <?php else: ?>
                <input type="text" name="regi_mail" class="regi_mail" value="<?php echo $_POST['regi_mail']; ?>">
            <?php endif; ?>

            <span class="indi">
                <?php 
                if(isset($_POST['regi_submit'])){
                    if(empty($_POST['regi_mail'])){
                        echo "入力必須項目です";
                    }elseif($mail_result == 0){
                        echo "メールアドレスの形式でご記入ください";
                    }
                }
                ?>
            </span>
        </div>

        <div class="regi_class">
            <label class="regi_label">通勤時間(分)</label>

            <!-- 入力データ保持の条件分岐 -->
            <?php if(isset($_POST['regi_submit']) && $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== "" && $_POST['regi_mail'] !== "" && $mail_result == 1): ?>
                <input type="text" name="regi_com" class="regi_com">
            <?php else: ?>
                <input type="text" name="regi_com" class="regi_com" value="<?php echo $_POST['regi_com']; ?>">
            <?php endif; ?>

        </div>

        <div class="regi_class_new">
            <label class="regi_label">血液型</label>

            <!-- 入力データ保持の条件分岐 -->
            <?php if(isset($_POST['regi_submit']) && $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== "" && $_POST['regi_mail'] !== "" && $mail_result == 1): ?>
                <div class="regi_blood">
                    
                    <div>
                        <input type="radio"  name="regi_blood" value="1" checked />
                        <label for="A型">A型</label>
                    </div>

                    <div>
                        <input type="radio"  name="regi_blood" value="2" />
                        <label for="B型">B型</label>
                    </div>

                    <div>
                        <input type="radio" name="regi_blood" value="3" />
                        <label for="O型">O型</label>
                    </div> 

                    <div>
                        <input type="radio" name="regi_blood" value="4" />
                        <label for="AB型">AB型</label>
                    </div>  

                    <div>
                        <input type="radio" name="regi_blood" value="5" />
                        <label for="不明">不明</label>
                    </div> 
                </div> 
            <?php else: ?>
                <div class="regi_blood">
                    <?php 
                        foreach($bloodCotegory as $blood){
                            if(isset($_POST['regi_blood']) && $_POST['regi_blood'] == $blood['value']){
                                echo 
                                '<div>
                                    <input type="radio" name="regi_blood" value="'. $blood['value'] . '" selected/>
                                    <label for="'. $blood['value'] . '">'. $blood['text'] . '</label>
                                </div>'; 
                                        
                            }else{
                                echo 
                                '<div>
                                    <input type="radio" name="regi_blood" value="'. $blood['value'] . '"/>
                                    <label for="'. $blood['value'] . '">'. $blood['text'] . '</label>
                                </div>'; 
                            }
                        }
                        ?>
                </div>

            <?php endif; ?>

        </div>


        <div class="regi_class_new">
            <label class="regi_label">既婚</label>

            <!-- 入力データ保持の条件分岐 -->
            <?php if(isset($_POST['regi_submit']) && $_POST['regi_name'] !== "" && $_POST['regi_kana'] !== "" && $_POST['regi_mail'] !== "" && $mail_result == 1): ?>
                <div>
                    <input type="checkbox"  name="regi_married" value="1" />
                    <label for="既婚">既婚</label>
                </div>            
            <?php else: ?>
                <div>
                    <input type="checkbox" name="regi_married" value="1"/>
                    <label for="既婚">既婚</label>
                </div>            
            <?php endif; ?>

        </div>


        <input type="hidden" name="token" value="<?php echo $token;?>">

        <input type="submit" name="regi_submit" value="登録" class="regi_submit">
    </form>
</div>
</main>
</body>
</html>