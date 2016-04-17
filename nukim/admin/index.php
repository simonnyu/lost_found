<?php
	$cooke_name = "s_user";
	if($_COOKIE[$cooke_name]==null){
		header("Location: login.php");
	}//沒有登入狀態則導向登入畫面
	require_once("../db_config.php");
	$u_id = $_COOKIE[$cooke_name];
	$get_name_sql = "select U_NAME from user where U_ID='".$u_id."'";
	$query = mysqli_query($link,$get_name_sql) or die($get_name_sql);
	$result = mysqli_fetch_array($query,MYSQLI_ASSOC);
	$name = $result['U_NAME'];
	mysqli_close($link);
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
			<ul>
				<li><a href="lost_mng.php">失物管理</a></li>
				<li><a href="loc_mng.php">地點管理</a></li>
				<li><a href="cat_mng.php">物品類別管理</a></li>
				<li><a href="user_mng.php">使用者管理</a></li>
				<li><a href="oauth_mng.php">第三方登入管理</a></li>
				<li><a href="history.php">歷史紀錄</a></li>
				<li style="float:right"><a class="active" href="logout.php">登出</a></li>
			</ul>
	</div>
</body>
</html>
