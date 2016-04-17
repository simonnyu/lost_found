<?php
	$cooke_name = "c_user";
	if(@$_COOKIE[$cooke_name]!=null){
		header("Location: index.php");
	}//有登入的cookie就不用再登入
	$login_msg = "";//登入錯誤訊息用
	if(isset($_GET['u'])){
		if($_GET['u']=="guest"){
			setcookie($cooke_name, "guest");
			header("Location: index.php");
		}
	}elseif(@$_SERVER['REQUEST_METHOD'] == 'POST'){
		require_once("db_config.php");//資料庫連線設定
		$UID = $_POST['UID'];
		$user_sql = "select * from AUTH where U_ID = '".$UID."'";
		$user = mysqli_query($link, $user_sql);
		$PWD = md5($_POST['PWD']);//將密碼加密 用來比對資料庫中的密碼
		if(mysqli_num_rows($user)>0){
			$result = mysqli_fetch_array($user, MYSQLI_ASSOC);
			if($PWD == $result['U_PWD']){
				setcookie($cooke_name, $result['U_ID']);
				if($result['U_INIT']==0){
					header("Location: index.php");//登入成功 回到首頁
				}else{
					header("Location: first.php");//登入成功 資料設定
				}
			}else{
				$login_msg = "密碼錯誤!!";
			}
		}else{
			$login_msg = "系統查無此使用者!! 請聯繫管理員!!";
		}
		mysqli_close($link);
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
		#login_left{
			width: 60%;
			float: left;
			margin:0px auto;
		}
		#login_right{
			width: 40%;
			float: left;
			margin:0px auto;
		}
		#login{
			padding: 8px 150px;
		}
	}
	.box{
		padding: 4px 4px;
		margin:0px auto;
	}
	body{
		background-color: #dfe3ee;
	}
	input[type=text],input[type=password] {
	    width: 100%;
	    padding: 12px 20px;
	    margin: 8px 0;
	    box-sizing: border-box;
	    border: 3px solid #ccc;
	    -webkit-transition: 0.5s;
	    transition: 0.5s;
	    outline: none;
	}
	input[type=text],input[type=password]:focus {
    	border: 3px solid #555;
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
	</style>
	<script type="text/javascript">
		
	</script>
</head>
<body>
	<div id="container">
		<center>
		<div><p><img src="assect/img/pdc_title.jpg" width="100%"></img></p></div>
		<div>
			
			<div id = "login_left">
			<p><font size="8"><b><font face="微軟正黑體">系統登入</font></font></p></b>
			<form method="post" id="login">
				<div class="box">
				
					<table>
						<tr>
							<td><font face="微軟正黑體" size="5">帳號</font></td>
						</tr>
						<tr>
							<td><input type="text" name="UID" placeholder="例:A1033301" size="20" required></input></td>
						</tr>
					</table>
					
				</div>
				<div class="box">
				
					<table>
						<tr>
							<td><font face="微軟正黑體" size="5">密碼</font></td>
						</tr>
						<tr>
							<td><input type="password" name="PWD" placeholder="預設為生日 Ex.20160311" size="20" required></input></td>
						</tr>
						
					</table>
					<?php
						echo $login_msg;
					?>
				</div>
				<div class="box">
					<button type="button" onclick="location.href='forgot.php'">忘記密碼</button>
					<input type="submit" value="登入"></input>
				</div>
				<div class="box">
					<button type="button" onclick="location.href='login.php?u=guest'">訪客登入</button>
				</div>
			</form>
			</div>
			<div id = "login_right">
				<p><font face="微軟正黑體" size="5">其他登入方法</font></p>
				<a href="https://www.facebook.com/dialog/oauth?client_id=847771775368798&redirect_uri=http://140.127.218.250:49012/nukim/fblogin.php"><img src="assect/img/PDC-FB.png" width="350" height="100"></a>
				<a href="https://accounts.google.com/o/oauth2/auth?response_type=token&client_id=509544004520-ptlpv67add3uq74cq2bnbnnrmsoo8fgk.apps.googleusercontent.com&redirect_uri=http://140-127-218-250.nuk.edu.tw:49012/nukim/glogin.php&scope=https://www.googleapis.com/auth/userinfo.profile+https://www.googleapis.com/auth/userinfo.email"><img src="assect/img/google-login-register.png" width="350" height="100"></a>
			</div>
		</div>
		</center>
	</div>
</body>
</html>