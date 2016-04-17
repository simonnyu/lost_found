<?php
	$cooke_name = "s_user";
	if($_COOKIE[$cooke_name]==null){
		header("Location: login.php");
	}//沒有登入狀態則導向登入畫面
	require_once("../db_config.php");
	$msg = "";
	if(isset($_POST['uid'])){
		$check_sql = "SELECT U_ID FROM user WHERE U_ID = '".$_POST['uid']."'";
		$check = mysqli_query($link, $check_sql);
		if (mysqli_num_rows($check)!=0){
			$msg = "<p align='center'><b>該使用者已存在!!</b></p>";
		}else{
			$newpwd = str_replace("-", "", $_POST['ubrth']);
			$pwd = md5($newpwd);
			$create_user_sql = "INSERT INTO user (U_ID, U_NAME, U_BRTH, U_EMAIL, U_CEL) VALUES ('".$_POST['uid']."', '".$_POST['uname']."', '".$_POST['ubrth']."', '".$_POST['uemail']."', '".$_POST['ucel']."')";
			$create_auth_sql = "INSERT INTO AUTH (U_ID, U_PWD, U_INIT, U_PRE) VALUES ('".$_POST['uid']."', '".$pwd."', 1, 0)";
			if(mysqli_query($link, $create_user_sql) && mysqli_query($link, $create_auth_sql)){
				$msg = "<p align='center'><b>使用者新增成功</p></b>";
			}else{
				$msg = "<p align='center'><b>使用者新增失敗</p></b>";
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>NUKIM - 失物招領</title>
	<style type="text/css">
	@media only screen and (min-width: 990px) {
		#container{
			width: 985px;
			margin:0px auto;
		}
		#div_left{
			width: 25%;
			float: left;
	        font-family:"Microsoft JhengHei";
		}
		
		#div_right{
			width: 75%;
			float: left;
			margin: 0px auto;
			font-family:"Microsoft JhengHei";
		}
	}
	.box{
		padding: 4px 4px;
	}
	body{
		background-color: #dfe3ee;
	}
	table {
    	border-collapse: collapse;
	}
	tr:nth-child(even) {
		background-color: #f2f2f2;
	}
	input[type=button], input[type=submit], input[type=reset], button[type=button] {
    	background-color: #4CAF50;
    	border: none;
    	color: white;
    	padding: 16px 32px;
    	text-decoration: none;
    	margin: 4px 2px;
    	cursor: pointer;
	}
	select {
    	padding: 16px 20px;
    	border: none;
    	border-radius: 4px;
    	background-color: #f1f1f1;
	}
	td,th{
	    padding: 12px 20px;
	    margin: 8px 0;
	    box-sizing: border-box;
	    -webkit-transition: 0.5s;
	    transition: 0.5s;
	    outline: none;
	}
	input[type=text],input[type=date]{
	    width: 100%;
	    padding: 12px 20px;
	    margin: 8px 0;
	    box-sizing: border-box;
	    border: 3px solid #ccc;
	    -webkit-transition: 0.5s;
	    transition: 0.5s;
	    outline: none;
	}
	input[type=text]:focus {
    	border: 3px solid #555;
	}
	</style>
</head>
<body>
	<div id="container">
		<div><a href="index.php"><img src="../assect/img/pdc_title.jpg" width="100%"></a></div>
		<?php
			if(isset($_POST['uid'])){
				echo $msg;
				echo "系統將於3秒後轉跳至管理頁面<br/>";
				header("Refresh: 3; url=user_mng.php");
			}else{
		?>
		<form action="adduser.php" method="POST">
			<table align="center">
				<tr>
					<td>帳號ID</td>
					<td><input type="text" name="uid" placeholder="建議使用學號" required></input></td>
				</tr>
				<tr>
					<td>姓名</td>
					<td><input type="text" name="uname" required></input></td>
				</tr>
				<tr>
					<td>生日</td>
					<td><input type="date" name="ubrth" required></input></td>
				</tr>
				<tr>
					<td>Email</td>
					<td><input type="text" name="uemail" required></input></td>
				</tr>
				<tr>
					<td>手機</td>
					<td><input type="text" name="ucel" maxlength="10" placeholder="Ex.0912345678" required></input></td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<button type="button" onclick="history.back()">取消</button> 
						<input type="submit" value="送出"></input>
					</td>
				</tr>
			</table>
		</form>
		<?php } ?>
</body>