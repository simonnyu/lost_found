<?php
	$cooke_name = "s_user";
	if($_COOKIE[$cooke_name]==null){
		header("Location: login.php");
	}//沒有登入狀態則導向登入畫面
	require_once("../db_config.php");
	$msg = "<p><b>未指定使用者</b></p>";
	if (isset($_GET['id'])) {
		$get_sql = "select U_BRTH from user where U_ID = '".$_GET['id']."'";
		$get = mysqli_query($link, $get_sql);
		$result = mysqli_fetch_array($get, MYSQLI_ASSOC);
		$newpwd = str_replace("-", "", $result['U_BRTH']);
		$pwd = md5($newpwd);
		$update_sql = "update AUTH set U_PWD = '".$pwd."' where U_ID = '".$_GET['id']."'";
		if(mysqli_query($link, $update_sql)){
			$msg = "<p><b>密碼重設成功</b></p>";
		}else{
			$msg = "<p><b>密碼重設失敗</b></p>";
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
			width: 60%;
			float: left;
		}
		#div_right{
			width: 40%;
			float: left;
		}
		#menu{
			width: 50%;
			margin:0px auto;
		}
		#login{
			padding: 8px 16px;
		}
		.box{
			padding: 4px 4px;
		}
	}
	body{
		background-color: #dfe3ee;
	}
	ul {
	    list-style-type: none;
	    margin: 0;
	    padding: 0;
	    overflow: hidden;
		background-color: #333;
	}

	li {
	    float: left;
	}

	li a {
	    display: block;
	    color: white;
	    text-align: center;
	    padding: 14px 16px;
	    text-decoration: none;
	}

	li a:hover:not(.active) {
	    background-color: #111;
	}

	.active {
	    background-color: #4CAF50;
	}
	</style>
</head>
<body>
	<div id="container">
		<div><img src="../assect/img/pdc_title.jpg" width="100%"></img></div>
		<br/>
		<center>
			<?php
				echo $msg;
				echo "系統將於3秒後轉跳至管理頁面<br/>";
				header("Refresh: 3; url=user_mng.php");
			?>
		</center>
	</div>
</body>
</html>
