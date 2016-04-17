<?php
	$code = $_GET['code'];
	$c_id = "847771775368798";
	$red_uri = "http://140.127.218.250:49012/nukim/fblogin.php";
	$c_sec = "500176864d1e9c4cb288670fa2f8371b";
	$uri = "https://graph.facebook.com/oauth/access_token?client_id=".$c_id."&redirect_uri=".$red_uri."&client_secret=".$c_sec."&code=".$code;
	$cont = file_get_contents($uri);
	$acc_end = strpos($cont, "&");
	$acc = substr($cont, 13, $acc_end-13);
	$exp = substr($cont, $acc_end+9);
	$uri = "https://graph.facebook.com/me?access_token=".$acc;
	$user_info = file_get_contents($uri);
	$info = json_decode($user_info);
	$fb_id = $info->id;
	if(!is_null($fb_id)){
		require_once("db_config.php");
		$get_sql = "select count(*) from OAUTH where F_ID='".$fb_id."'";
		$res = mysqli_query($link, $get_sql);
		$set = mysqli_fetch_array($res, MYSQLI_ASSOC);
		if($set['count(*)']==0){
			$fb_login="Location: first.php?oauth=1&fid=".$fb_id;
			header($fb_login);
		}else{
			$get_sql = "select * from OAUTH where F_ID='".$fb_id."'";
			$res = mysqli_query($link, $get_sql) or die($get_sql);
			$set = mysqli_fetch_array($res, MYSQLI_ASSOC) or die("!!");
			$UID = $set['U_ID'];
			echo $UID;
			$cooke_name = "c_user";
			setcookie($cooke_name, $UID);
			header("Location: index.php");
		}
		mysqli_close($link);
	}
?>