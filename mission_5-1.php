<?php
// DB接続設定
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, 
       array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

if($_POST["hiddennum"] != NULL){
	
    //UPDATE文で入力されているデータレコードの内容を編集
    $id = $_POST["hiddennum"]; 
    $dbname = $_POST["name"];
    $dbcomment = $_POST["comment"]; 
    $dbdate = date("Y/m/d H:i:s");
    $dbpassword = $_POST["password"];
    $sql = 'UPDATE tbmission5 SET dbname=:dbname,dbcomment=:dbcomment,dbdate=:dbdate,dbpassword=:dbpassword WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':dbname', $dbname, PDO::PARAM_STR);
    $stmt->bindParam(':dbcomment', $dbcomment, PDO::PARAM_STR);
    $stmt->bindParam(':dbdate', $dbdate, PDO::PARAM_STR);
    $stmt->bindParam(':dbpassword', $dbpassword, PDO::PARAM_STR);
    $stmt->execute();
    
}else{
       if(isset($_POST["submit1"])){
        
        //INSERT文で,データ（レコード）を登録
        $sql = $pdo -> prepare("INSERT INTO tbmission5 (id, dbname, dbcomment, dbdate, dbpassword) 
                                VALUES (:id, :dbname, :dbcomment, :dbdate, :dbpassword)");
        $sql -> bindParam(':id', $id, PDO::PARAM_INT);
        $sql -> bindParam(':dbname', $dbname, PDO::PARAM_STR);
        $sql -> bindParam(':dbcomment', $dbcomment, PDO::PARAM_STR);
        $sql -> bindParam(':dbdate', $dbdate, PDO::PARAM_STR);
        $sql -> bindParam(':dbpassword', $dbpassword, PDO::PARAM_STR);
        $dbname = $_POST["name"];
        $dbcomment = $_POST["comment"];
        $dbdate = date("Y/m/d H:i:s");
        $dbpassword = $_POST["password"];
        $sql -> execute();
        
    }elseif(isset($_POST["submit2"])){
        $delenum = $_POST["delenum"];
        $password = $_POST["password"];
        
        //SELECT文で,テーブルに登録された特定のデータを取得
        $id = $delenum; 
        $sql = 'SELECT * FROM tbmission5 WHERE id=:id ';
        $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
        $stmt->execute();                             // ←SQLを実行する。
        $results = $stmt->fetchAll(); 
        foreach ($results as $row){
            if($row['dbpassword'] == $password){
                
                // DELETE文で入力したデータレコードを削除
                $id = $delenum;
                $sql = 'delete from tbmission5 where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                
            }else{
                echo "パスワードが違います<br>";
            }
        }
        
    }elseif(isset($_POST["submit3"])){
        $editnum = $_POST["editnum"];
        $password = $_POST["password"];
        
         //SELECT文で,テーブルに登録された特定のデータを取得
        $id = $editnum; 
        $sql = 'SELECT * FROM tbmission5 WHERE id=:id ';
        $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
        $stmt->execute();                             // ←SQLを実行する。
        $results = $stmt->fetchAll(); 
        foreach ($results as $row){
            if($row['dbpassword'] == $password){
                $hiddennum = $editnum;
                $first_name = $row['dbname'];
                $first_comment = $row['dbcomment'];
            }else{
                 echo "パスワードが違います<br>";
            }
        }
        
    }else{
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="name" 
        value="<?php echo $first_name; ?>" placeholder = "名前">
        <input type="text" name="comment" 
        value="<?php echo $first_comment; ?>" placeholder = "コメント">
        <input type="text" name="password" placeholder = "パスワード">
        <input type="hidden" name="hiddennum"
        value="<?php echo $editnum; ?>">
        <input type="submit" name="submit1">
    </form>
    <form action="" method="post">
        <input type="number" name="delenum" placeholder = "削除対象番号">
        <input type="text" name="password" placeholder = "パスワード">
        <button type="submit" name="submit2">削除</button>
    </form>
    <form action="" method="post">
        <input type="number" name="editnum" placeholder = "編集対象番号">
        <input type="text" name="password" placeholder = "パスワード">
        <button type="submit" name="submit3">編集</button>
    </form>
</body>
</html>

<?php

 //SELECT文で,テーブルに登録されたデータを取得
$sql = 'SELECT * FROM tbmission5';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
	echo $row['id'].' ';
	echo $row['dbname'].' ';
	echo $row['dbcomment'].' ';
	echo $row['dbdate'].'<br>';
}

?>