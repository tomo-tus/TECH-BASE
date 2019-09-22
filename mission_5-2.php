<!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8">
</head>
 

<body>
<from>
<?php
header('Content-Type:text/html; charset=utf-8');

$dsn='mysql:dbname='データベース名';host=localhost';
$user='ユーザー名';
$passward="パスワード";

try {
$pdo=new PDO($dsn,$user,$passward,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));//Mysqlの接続
} catch (PDOException $e) {
	echo '接続失敗:'.$e->getMessage();
	exit;
};
$sql = "CREATE TABLE IF NOT EXISTS mission5a"
	." ("
	."id INT AUTO_INCREMENT PRIMARY KEY,"
	."name char(32),"
	."comment TEXT,"
	."passward TEXT"
	.");";
	$stmt = $pdo->query($sql);//テーブルの作成


$date=date("Y年 m/d H:i:s");
$message = "おめでとう";

$sql ='SHOW TABLES';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[0];
		echo '<br>';
	}
	echo "<hr>";//テーブルの一覧表示

	//新規入力フォーム

if(!empty($_POST['comment']) && !empty($_POST['name']) && empty($_POST['number']))//もし何か入力されたら、
{
	if(!empty($_POST['passward1']))
	{
	$comment1=$_POST['comment'];
	$name1=$_POST['name'];
	$passward1=$_POST['passward1'];
	
	$sql=$pdo->prepare("INSERT INTO mission5a (name, comment, passward) VALUES (:name, :comment, :passward)");
	$sql->bindParam(':name', $name1, PDO::PARAM_STR);
	$sql->bindParam(':comment', $comment1, PDO::PARAM_STR);
	$sql->bindParam(':passward', $passward1, PDO::PARAM_STR);
	$sql->execute();

	$sql = 'SELECT * FROM mission5a';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row)
	{
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].'<br>';
		if($row['comment']=='完成')
		{
		echo $message;
		}
	echo "<hr>";
	}
	
	}
	if(empty($_POST['passward1']))
	{
	echo"パスワードを入力してください。";
	}
}

	//削除フォーム

if(!empty($_POST['delete']))//削除フォームに入力された時
{
	$sql = 'SELECT * FROM mission5a';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row)
	{
	if($_POST['delete']==$row['id'])//削除番号とMysqlの番号を比較
	{
		if($_POST['passward2']==$row['passward'])//パスワードの比較
		{
		$id = $_POST['delete'];;
		$sql = 'delete from mission5a where id=:id';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		
		$sql = 'SELECT * FROM mission5a';//mission5aテーブルにあるすべてのデータを取得するSQL文を変数に格納
		$stmt = $pdo->query($sql);//SQL文を実行するコードを変数に格納
		$results = $stmt->fetchAll();
		foreach ($results as $row)
		{
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].'<br>';
		echo "<hr>";
		}
		}
		if($_POST['passward2']!=$row['passward'])
		{
		echo"パスワードが違います。";
		}
	}
	}
	if(empty($_POST['passward2']))
	{
	echo"パスワードを入力してください。";
	}
}

	//編集フォーム1

if(!empty($_POST['edit']))//編集フォームに入力された時
{
	$sql = 'SELECT * FROM mission5a';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row)
	{
	if($_POST['edit']==$row['id'])//削除番号とMysqlの番号を比較
	{
		if($_POST['passward3']==$row['passward'])//パスワードの比較
		{
		$naiyou=$row['name'];
		$naiyou1=$row['comment'];
		$naiyou2=$row['id'];
		}
		if($_POST['passward3']!=$row['passward'])
		{
		echo"パスワードが違います。";
		}
	}
	}

	if(empty($_POST['passward3']))
	{
	echo"パスワードを入力してください。";
	}
}
?>
</from>
</body>

<title>テーマ：最近ハマッテいること</title>
<form method="POST" action="#">

	<p>テーマ：最近ハマっていること</p>
	<p>名前        <input type="text" name="name" value="<?php if(!empty($naiyou)){echo $naiyou;} ?>"></p>
	<p>コメント    <input type="text" name="comment" value="<?php if(!empty($naiyou1)){echo $naiyou1;} ?>"></p>
	<p>		<input type="hidden" name="number" value="<?php if(!empty($naiyou2)){echo $naiyou2;} ?>"></p>
	<p>パスワード   <input type="text" name="passward1"> <input type="submit"value="送信"></p>
	<p>削除番号    <input type="number" name="delete"></p>
	<p>パスワード   <input type="text" name="passward2"> <input type="submit"value="削除"></p>
	<p>編集対称番号<input type="number" name="edit"></p>
	<p>パスワード   <input type="text" name="passward3"> <input type="submit"value="編集"></p>

 </form>

<?php
//編集フォーム2

if(!empty($_POST['number']))
{
	$sql = 'SELECT * FROM mission5a';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row)
	{
	if($_POST['number']==$row['id'])//編集番号とMysqlの番号を比較
	{
		if(!empty($_POST['passward1']))
		{
		$id = $_POST['number']; //変更する投稿番号
		$name = $_POST['name'];
		$comment = $_POST['comment']; //変更したい名前、変更したいコメントは自分で決めること
		$sql = 'update mission5a set name=:name,comment=:comment where id=:id';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':name', $name, PDO::PARAM_STR);
		$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		
		$sql = 'SELECT * FROM mission5a';//mission5aテーブルにあるすべてのデータを取得するSQL文を変数に格納
		$stmt = $pdo->query($sql);//SQL文を実行するコードを変数に格納
		$results = $stmt->fetchAll();
		foreach ($results as $row)
		{
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].'<br>';
		echo "<hr>";
		}
		}
		if(empty($_POST['passward1']))
		{
		echo"パスワードを入力してください。";
		}
	}
	}
}
?>

</html>
