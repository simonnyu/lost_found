<?php
	$cooke_name = "s_user";
	if(@$_COOKIE[$cooke_name]!=null){
		header("Location: index.php");
	}//有登入的cookie就不用再登入
	$login_msg = "";//登入錯誤訊息用
	if(@$_SERVER['REQUEST_METHOD'] == 'POST'){
		require_once("../db_config.php");//資料庫連線設定
		$user_sql = "select * from AUTH";
		$user = mysqli_query($link, $user_sql);
		$UID = $_POST['UID'];
		$PWD = md5($_POST['PWD']);//將密碼加密 用來比對資料庫中的密碼
		while ($result = mysqli_fetch_array($user, MYSQLI_ASSOC)) {//比對資料
			if($UID == $result['U_ID']){
				if($PWD == $result['U_PWD']){
					if($result['U_PRE']==1){
						setcookie($cooke_name, $UID); //登入狀態30分鐘
						header("Location: index.php");//登入成功 回到首頁
					}else{
						$login_msg = "此帳號權限不足!!";
					}
					break;
				}
				else{
					$login_msg = "密碼錯誤!!";
					break;
				}
			}else{
				$login_msg = "系統查無此使用者!! 請聯繫管理員!!";
			}
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
		}
		#login_right{
			width: 40%;
			float: left;
		}
		#login{
			padding: 8px 150px;
		}
	}
	.box{
		padding: 4px 4px;
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
		<div><p><img src="../assect/img/pdc_title.jpg" width="100%"></img></p></div>
		<div>
			<center>
			<p><font size="8"><b><center><font face="微軟正黑體">系統管理員登入</font></center></font></p></b>
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
					<input type="submit" value="登入"></input>
				</div>
			</form>
			</center>
		</div>
	</div>
</body>
</html>