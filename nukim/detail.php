<?php
	$cooke_name = "c_user";
	if($_COOKIE[$cooke_name]==null){
		header("Location: login.php");
	}//沒有登入狀態則導向登入畫面
	if(!isset($_GET['id'])){
		header("Location: index.php");
	}
	require_once("db_config.php");
	$u_id = $_COOKIE[$cooke_name];
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<style type="text/css">
	@media only screen and (min-width: 990px) {
		#container{
			width: 985px;
			margin:0px auto;
		}
		#div_left{
			width: 40%;
			float: left;

		}
		#div_right{
			width: 60%;
			float: left;
		}
	}
	#login{
		padding: 8px 16px;
	}
	.col_1{
		text-align: right;
		width: 120px;
		padding: 5px;
	}
	.col_2{
		text-align: left;
		padding: 5px;
	}
	.box{
		padding: 4px 4px;
	}
	body{
		background-color: #dfe3ee;
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
		function pick(id){
			if (confirm("確定要認領嗎?")){
				location.href="pickup.php?id="+id;
			}
		}
	</script>
</head>
<body>
	<div id="container">
	<div><p><img src="assect/img/pdc_title.jpg" width="100%"></img></p></div>
		<?php
			$get_detail_sql = "SELECT * FROM item NATURAL JOIN location NATURAL JOIN cat NATURAL JOIN pic WHERE I_ID = '".$_GET['id']."'";
			$get_detail = mysqli_query($link, $get_detail_sql);
			$detail = mysqli_fetch_array($get_detail, MYSQLI_ASSOC);
		?>
		<div id="div_left">
		<font size="4" face="微軟正黑體">
			<center>
			<?php
				echo "<a href='".$detail['P_LOC']."' target='_blank'><img src='".$detail['P_N_LOC']."'></img></a>"; 
				echo "<p>點擊圖片可查看原始圖片</p>";
			?>
			</center>
		</div>
		<div id="div_right">
			<center>
			<?php
				echo "<table>";
				echo "<br/>";
				
				echo "<tr>";
				echo "<td class='col_1'>拾獲時間</td>";
				echo "<td class='col_2'>".$detail['TIME']."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td class='col_1'>物品類別</td>";
				echo "<td class='col_2'>".$detail['CAT_NAME']."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td class='col_1'>拾獲地點</td>";
				echo "<td class='col_2'>".$detail['LOC_NAME']."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td class='col_1'>物品描述</td>";
				echo "<td class='col_2'>".$detail['I_DICPT']."</td>";
				echo "</tr>";
		
				echo "<tr>";
				
				echo "<td><input type ='button' onclick='window.close();' value='關閉視窗'></input></td>";
				if($u_id!="guest"){
					echo "<td><input type ='button' onclick='pick(".$_GET['id'].")' value='我要認領'></input></td>";
				}
				echo "</tr>";
				echo "</table>";
			?>
			</font>
			</center>
		</div>
	</div>
</body>