<?php
	if(isset($_GET['oauth'])){
		if(@$_SERVER['REQUEST_METHOD'] == 'POST'){
			require_once("db_config.php");
			$check_name_id_sql = "select * from user where U_ID = '".$_POST['uid']."' AND U_NAME = '".$_POST['uname']."'";
			$check_name_id = mysqli_query($link, $check_name_id_sql);
			if(mysqli_num_rows($check_name_id)>0){
				if(isset($_GET['fid'])){
					$isexist_sql = "select U_ID from OAUTH where U_ID = '".$_POST['uid']."'";
					$isexist = mysqli_query($link, $isexist_sql);
					if(mysqli_num_rows($isexist)!=0){
						$err_msg = "<p><b>該ID已建立第三方登入連結</b></p>";
					}else{
						$auth_con_sql = "insert into OAUTH (U_ID, F_ID, F_PROFIILE_URL) values ('".$_POST['uid']."','".$_GET['fid']."','".$_POST['profile_url']."')";
					}
				}elseif ($_GET['gid']){
					$isexist_sql = "select U_ID from OAUTH_G where U_ID = '".$_POST['uid']."'";
					$isexist = mysqli_query($link, $isexist_sql);
					if(mysqli_num_rows($isexist)!=0){
						$err_msg = "該ID已建立第三方登入連結，若有疑問請洽管理員";
					}else{
						$auth_con_sql = "insert into OAUTH_G (U_ID, G_ID) values ('".$_POST['uid']."','".$_GET['gid']."')";
					}
				}
				if(mysqli_query($link, $auth_con_sql)){
					$update_sql = "update user set U_EMAIL = '".$_POST['mail']."', U_CEL = '".$_POST['cel']."', U_BRTH = '".$_POST['brth']."' where U_ID = '".$_POST['uid']."'";
					$update_sql1 = "update AUTH set U_INIT = '0' where U_ID = '".$_POST['uid']."'";
					if(mysqli_query($link, $update_sql) && mysqli_query($link, $update_sql1)){
						$cooke_name = "c_user";
						setcookie($cooke_name, $_POST['uid']); //登入狀態30分鐘
						header("Location: index.php");
					}else{
						$err_msg = "歐喔,發生錯誤了T_T 請聯繫管理員";
					}
				}
			}else{
				$err_msg = "姓名與ID不符合!，若有疑問請洽管理員";
			}
		}
	}else{
		$cooke_name = "c_user";
		if(!isset($_COOKIE[$cooke_name])){
			header("Location: login.php"); //沒有登入cookie 導向登入畫面
		}
		$UID = $_COOKIE[$cooke_name];
		require_once("db_config.php"); //資料庫連接設定
		$get_sql = "select * from AUTH NATURAL JOIN user where U_ID ='".$UID."'"; //抓該使用者的資料
		$get_info = mysqli_query($link, $get_sql);
		$result = mysqli_fetch_array($get_info, MYSQLI_ASSOC);
		if($result['U_INIT']==0){//有改過資料則不必繼續 回到首頁
			header("Location: index.php");
		}
		if(isset($_POST['mail'])){
			$pwd = md5($_POST['pwd1']);
			$update_sql = "update user set U_EMAIL = '".$_POST['mail']."', U_CEL = '".$_POST['cel']."', U_BRTH = '".$_POST['brth']."'where U_ID = '".$UID."'";
			$update_sql1 = "update AUTH set U_PWD = '".$pwd."', U_INIT = '0' where U_ID = '".$UID."'";
			$res = mysqli_query($link, $update_sql);
			$res = mysqli_query($link, $update_sql1);
			if(!$res){
				die('歐喔,發生錯誤了T_T 請聯繫管理員');
			}else{
				header("Location: index.php");
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
		<?php
			if(isset($err_msg)){
				echo "window.alert(\"".$err_msg."\");";
			}
		?>
		<?php
			if (isset($_GET['oauth'])) {
			}else{
		?>
		function check(){
			if ((document.f.elements[3].value)!=(document.f.elements[4]).value){
				alert("新密碼不一致!!");
				return false;
			}//確認密碼沒有打錯
			return true;
		}
		<?php } ?>
	</script>
</head>
<body>
	<div id="container">
		<div><img src="assect/img/pdc_title.jpg"></img></div>
		<br/>
		<div><center><font face="monospace" size="6">Welcome</font></center></div>
		<div>
			<form method="post" name="f" onsubmit="return check();">
			<font face="微軟正黑體">
				<center>
				<?php
					if (isset($_GET['oauth'])) {		
						echo "第一次登入需要建立基本資料";
					}
				?>
				</center>
			</font>
			<br/>
            <div id="login_left" align="center">
				<table>
					<tr>
						<td class="col_1"><b>姓名</b></td>
						<td class="col_2">
							<?php
								if(isset($_GET['oauth'])){
									echo "<input type='text' name='uname' required></input>";
								}else{
									echo $result['U_NAME'];
								}
							?>
						</td>
					</tr>
					<tr>
						<td class="col_1"><b>ID</b></td>
						<td class="col_2">
							<?php
								if(isset($_GET['oauth'])){
									echo "<input type='text' name='uid' placeholder='例: A1033301' required></input>";
								}else{
									echo $result['U_ID'];
								}
							?>
						</td>
					</tr>
					<tr>
						<td class="col_1"><b>E-Mail</b></td>
						<td class="col_2">
							<?php
								if(isset($_GET['gmail'])){
									echo "<input type='text' name='mail' value='".$_GET['gmail']."' required></input>";
								}else{
									echo "<input type='text' name='mail' required></input>";
								}
							?>
						</td>
					</tr>
					<tr>
						<td class="col_1"><b>出生日期</b></td>
						<td class="col_2">
							<?php echo "<input type='date' name='brth' value='".$result['U_BRTH']."' required></input>"; ?>
						</td>
					</tr>
					<tr>
						<td class="col_1"><b>手機號碼</b></td>
						<td class="col_2">
							<?php echo "<input type='text' name='cel' value='".$result['U_CEL']."' required></input>"; ?>
						</td>
					</tr>
				<?php
				if (isset($_GET['oauth'])) {}else{
				?>	
					<tr>
						<td class="col_1"><b>新密碼</b></td>
						<td class="col_2">
							<input type="password" name="pwd1" required></input>
						</td>
					</tr>
					<tr>
						<td class="col_1"><b>再次輸入新密碼</b></td>
						<td class="col_2">
							<input type="password" name="pwd2" required></input>
						</td>
							</tr>
				<?php
				}if(isset($_GET['fid'])){
				?>
					<tr>
						<td class="col_1"><b>FB個人網址</b></td>
						<td class="col_2">
							<textarea name="profile_url" placeholder="Ex. https://www.facebook.com/profile.php?id=1000053748" required></textarea>
						</td>
					</tr>
					<?php 
				} ?>
					<tr>
						<td colspan="2">
							<input type="submit" value="送出"></input>
						</td>
					</tr>
				</form>
			</table>	
			</div>
			<div id="login_right">
            <font face="微軟正黑體" size="3">
	            <br/>
	            <br/>
	            您好<br/>
	            歡迎使用NUKIM-失物招領系統<br/>
	            系統主要分為「尋獲」和「遺失」兩大項目<br/>
	            鑒於各位資管同學常將物品遺失於管院<br/>
	            本系統將為此處遺失物品進行管理<br/>
	            <br/>
	            為了簡化流程<br/>
	            系統預設您的帳號為學號(英文字母大寫)<br/>
	            密碼為您的生日，此頁面可供修改密碼<br/>
	            或是進行更進階個人資料設定<br/>
	            謝謝您的使用<br/>
	            如有任何疑問，歡迎詢問系統管理員-馬翔<br/>
	            </font>
			</div>
		</div>
	</div>
</body>
</html>
<?php mysqli_close($link); ?>