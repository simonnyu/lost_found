<?php
	$cooke_name = "c_user";
	if(@$_COOKIE[$cooke_name]==null){
		header("Location: login.php"); //沒有登入cookie 導向登入畫面
	}
	$UID = $_COOKIE[$cooke_name];
	if($UID == "guest"){
		header("Location: index.php");
	}
	require_once("db_config.php");
	$get_info_sql = "select * from user where U_ID = '".$UID."'";
	$result = mysqli_query($link, $get_info_sql) or die("get error");
	$user_info = mysqli_fetch_array($result, MYSQLI_ASSOC)or die("RRR");
	if(@$_SERVER['REQUEST_METHOD'] == 'POST'){
		if($_POST['opwd']=="" && $_POST['pwd1']==""){
			$update_info = "update user set U_EMAIL = '".$_POST['mail']."', U_CEL = '".$_POST['cel']."', U_BRTH = '".$_POST['brth']."' where U_ID = '".$UID."'";
			mysqli_query($link, $update_info);
			header("Location: index.php");
		}else{
			$PWD = md5($_POST['opwd']);
			$N_PWD = md5($_POST['pwd1']);
			$user_pwd_sql = "select U_PWD from AUTH where U_ID = '".$UID."'";
			$user_pwd = mysqli_query($link, $user_pwd_sql)or die("!");
			$pwd = mysqli_fetch_array($user_pwd, MYSQLI_ASSOC);
			if(strcmp($pwd['U_PWD'], $PWD)==0){
				$update_info = "update user set U_EMAIL = '".$_POST['mail']."', U_CEL = '".$_POST['cel']."', U_BRTH = '".$_POST['brth']."' where U_ID = '".$UID."'";
				mysqli_query($link, $update_info);
				$update_pwd = "update AUTH set U_PWD = '".$N_PWD."' where U_ID = '".$UID."'";
				mysqli_query($link, $update_pwd);
				header("Location: index.php");
			}else{
				$mesg = "舊密碼錯誤!!";
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title></title>
	<style type="text/css">
	@media only screen and (min-width: 990px) {
		#container{
			width: 985px;
			margin:0px auto;
		}
		#login_left{
			width: 100%;
			float: left;
			margin: auto;

		}
		#login{
			padding: 8px 16px;
		}
		.box{
			padding: 4px 4px;
		}
	}
	.col_1{
			text-align: right;
			padding: 5px;
		}
	.col_2{
		text-align: left;
		padding: 5px;		
	}
	body{
		background-color: #dfe3ee;
	}
	input[type=text],input[type=password],input[type=date] {
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
	td,th{
	    padding: 12px 20px;
	    margin: 8px 0;
	    box-sizing: border-box;
	    outline: none;
	}
	table {
    	border-collapse: collapse;
	}
	tr:nth-child(even) {
		background-color: #f2f2f2;
	}
	</style>
	<script type="text/javascript">
		function check(){
			if ((document.f.elements[4].value)!=(document.f.elements[5]).value){
				alert("新密碼不一致!!");
				return false;
			}
			return true;
		}
	</script>
</head>
<body>
	<div id="container">
	  <div><p><img src="assect/img/pdc_title.jpg" width="100%"></p></div>
		<div><b><center><FONT SIZE="6" face="微軟正黑體">修改個人資料</FONT></center></b></div><br/>
		<div>
			<div id="login_left" align="center">
				<form action="profile.php" method="post" name="f" onsubmit="return check();">
					<table>
						<tr>
							<td class="col_1"><b><font face="微軟正黑體">姓名</font></b></td>
							<td class="col_2">
							<font size="4" face="微軟正黑體">
								<?php
									echo $user_info['U_NAME'];
								?>
							</font>
							</td>
						</tr>
						<tr>
							<td class="col_1"><b><font face="monospace" size="4">ID</font></b></td>
							<td class="col_2">
							<font size="4" face="monospace">
								<?php
									echo $user_info['U_ID'];
								?>
							</td>
							</font>
						</tr>
						<tr>
							<td class="col_1"><b><font face="monospace" size="4">E-Mail</font></b></td>
							<td class="col_2">
							<font size="4" face="monospace">
								<?php echo "<input type='text' name='mail' value='".$user_info['U_EMAIL']."' required></input>"; ?>
								</font>
							</td>
						</tr>
						<tr>
							<td class="col_1"><b><font face="微軟正黑體">出生日期</font></b></td>
							<td class="col_2">
								<?php echo "<input type='date' name='brth' value='".$user_info['U_BRTH']."' required></input>"; ?>
							</td>
						</tr>
						<tr>
							<td class="col_1"><b><font face="微軟正黑體">手機號碼</b></font></td>
							<td class="col_2">
								<?php echo "<input type='text' name='cel' value='".$user_info['U_CEL']."' required></input>"; ?>
								</td>
						</tr>
						<tr>
							<td class="col_1"><b><font face="微軟正黑體">舊密碼</font></b></td>
							<td class="col_2">
								<input type="password" name="opwd"></input>
							</td>
						</tr>
						<tr>
							<td class="col_1"><b><font face="微軟正黑體">新密碼</font></b></td>
							<td class="col_2">
								<input type="password" name="pwd1"></input>
							</td>
						</tr>
							<tr>
								<td class="col_1"><b><font face="微軟正黑體">再次輸入新密碼</font></b></td>
								<td class="col_2">
									<input type="password" name="pwd2"></input>
								</td>
							</tr>
							<?php
									if(isset($mesg)){
										echo "<tr><td>";
										echo $mesg;
										echo "</tr></td>";
									}
							?>
						<tr>
							<td colspan="2" align="center">
								<button type="button" onclick="history.back()">回上一頁</button>
								<input type="submit" value="送出"></input>
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</div>
</body>
</html>
<?php mysqli_close($link); ?>