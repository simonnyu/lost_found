<?php
	$cooke_name = "c_user";
	if($_COOKIE[$cooke_name]==null){
		header("Location: login.php");
	}//沒有登入狀態則導向登入畫面
	require_once("db_config.php");
	$u_id = $_COOKIE[$cooke_name];
	if($u_id == "guest"){
		$name = "訪客";
	}else{
		$get_name_sql = "select U_NAME from user where U_ID='".$u_id."'";
		$query = mysqli_query($link,$get_name_sql) or die($get_name_sql);
		$result = mysqli_fetch_array($query,MYSQLI_ASSOC);
		$name = $result['U_NAME'];
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
		#div_left{
			width: 60%;
			float: left;
		}
		#div_right{
			width: 40%;
			float: left;
		}
		#login{
			padding: 8px 16px;
		}
	}
		.box{
			padding: 4px 4px;
		}
		body{
			background-color: #dfe3ee;
		}
	
	/*@media only screen and (max-width: 990px) {
		#container{
			width: 100%;
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
		#login{
			padding: 8px 16px;
		}
		.box{
			padding: 4px 4px;
		}
		body{
			background-color: #dfe3ee;
		}
		img{
			width: 100%;
		}
	}
	*/
	</style>
</head>
<body>
	<div id="container">
		<div><img src="assect/img/pdc_title.jpg" width="100%"></img></div>
		<div>
			<div id = "div_left">
			<center>
				<p>
					<a href="lost.php" class="box">
						<img src="assect/img/PDClost5.png" width="45%">
					</a>
				<?php
					if($u_id!="guest"){
				?>
					<a href="found.php" class="box">
						<img src="assect/img/PDCfound.png" width="42%">
					</a>
				</p>
				<?php
					}
				?>
				</center>
			</div>
			<div id = "div_right" >
			<center>
			    <p>
			    <font size="5" face="微軟正黑體">
					<?php echo "Hello!".$name;
					 echo "<br/>" ;
					?>
					</font>
				</p>
				<?php
					if($u_id!="guest"){
				?>
				<p>
					<a href="my.php"  class="box">
						<img src="assect/img/PDC1.png"  width="24%">
					</a>
				<?php
					}
				?>
					<a href="static.php" class="box">
						<img src="assect/img/PDC2.png" width="23%">
					</a>
				</p>
				<?php
					if($u_id!="guest"){
				?>
				<p>
					<a href="profile.php" class="box">
						<img src="assect/img/PDC4.png" width="24%">
					</a>
				<?php
					}
				?>
					<a href="logout.php" class="box">
						<img src="assect/img/PDC3.png" width="24%">
					</a>
				</p>
				</center>
			</div>
		</div>

	</div>
</body>
</html>