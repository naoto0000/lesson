<?php 
ini_set('log_errors','on');  //ログを取るか
ini_set('error_log','php_error.log');  //ログの出力ファイルを指定

$employees = array();
try {
    $pdo = new PDO('mysql:host=localhost;dbname=ad5', "root", "root");
} catch (PDOException $e) {
    echo $e->getMessage();
}

?>
