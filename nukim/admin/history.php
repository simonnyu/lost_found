<?php
	$cooke_name = "s_user";
	if($_COOKIE[$cooke_name]==null){
		header("Location: login.php");
	}//沒有登入狀態則導向登入畫面
	require_once("../db_config.php");
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
			margin: 0px auto;
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
	<script type="text/javascript">
		function view(id){
			location.href="history.php?view_id="+id;
		}
		function done(id){
			if (confirm("物品已物歸原主了嗎?")){
				location.href="history.php?done_id="+id;
			}
		}
		function undo(id){
			if (confirm("確定聯絡過後，發現不是他的嗎?")){
				location.href="history.php?undo_id="+id;
			}
		}
	</script>
</head>
<body>
	<div id="container">
		<div><a href="index.php"><img src="../assect/img/pdc_title.jpg" width="100%"></a></div>
		<center>
			<?php
				if(isset($_GET['view_id'])){
					$get_detail_sql = "SELECT * FROM item NATURAL JOIN location NATURAL JOIN cat NATURAL JOIN pic WHERE I_ID = '".$_GET['view_id']."'";
					$get_lost_sql = "select * from user, history WHERE L_U_ID = U_ID AND I_ID = '".$_GET['view_id']."'";
					$get_detail = mysqli_query($link, $get_detail_sql);
					$get_lost = mysqli_query($link, $get_lost_sql);
					$lost = mysqli_fetch_array($get_lost, MYSQLI_ASSOC);
					$detail = mysqli_fetch_array($get_detail, MYSQLI_ASSOC);
			?>
				<div id="div_left">
					<center><?php echo "<a href='../".$detail['P_LOC']."'><img src='../".$detail['P_N_LOC']."'></img></a>"; ?></center>
				</div>
				<div id="div_right">
					<table>
						<?php
						    
							echo "<tr>";
							echo "<td>拾獲時間</td>";
							echo "<td>".$detail['TIME']."</td>";
							echo "</tr>";
							echo "<tr>";
							echo "<td>物品類別</td>";
							echo "<td>".$detail['CAT_NAME']."</td>";
							echo "</tr>";
							echo "<tr>";
							echo "<td>拾獲地點</td>";
							echo "<td>".$detail['LOC_NAME']."</td>";
							echo "</tr>";
							echo "<tr>";
							echo "<td colspan='2'>物品描述</td>";
							echo "</tr>";
							echo "<tr>";
							echo "<td colspan='2'>".$detail['I_DICPT']."</td>";
							echo "</tr>";
							echo "<tr>";
							echo "<td>認領者</td>";
							echo "<td>".$lost['U_NAME']."</td>";
							echo "</tr>";
							echo "<td>電子郵件</td>";
							echo "<td>".$lost['U_EMAIL']."</td>";
							echo "</tr>";
							echo "</table>";
							if($detail['I_STAT']!=2){
								echo "<input type ='button' onclick='done(".$_GET['view_id'].")' value='物品已歸還'></input>";
								echo "<input type ='button' onclick='undo(".$_GET['view_id'].")' value='繼續等待主人'></input>";
							}
				}elseif (isset($_GET['done_id'])){
					$done_sql = "update item, history set item.I_STAT = '2', history.RET_TIME = CURDATE() where history.I_ID = item.I_ID AND item.I_ID = '".$_GET['done_id']."'";
					mysqli_query($link, $done_sql);
					echo "<center>感謝您的協助<br/>系統將於3秒後轉跳至歷史頁面<br/></center>";
					header("Refresh: 3; url=history.php");
				}elseif (isset($_GET['undo_id'])){
					$undo_sql = "update item set I_STAT = '0' where I_ID = '".$_GET['undo_id']."'";
					mysqli_query($link, $undo_sql);
					$undo_sql = "delete from history where I_ID = '".$_GET['undo_id']."'";
					mysqli_query($link, $undo_sql);
					echo "<center>感謝您的協助<br/>系統將於3秒後轉跳至歷史頁面<br/></center>";
					header("Refresh: 3; url=history.php");
				}else{
			?>
				<table>
					<tr>
						<th>編號</th>
						<th>認領時間</th>
						<th>歸還時間</th>
						<th>拾獲者ID</th>
						<th>遺失者ID</th>
						<th></th>
					</tr>
					<?php
						$get_sql = "select * from history";
						$get = mysqli_query($link, $get_sql);
						while($result = mysqli_fetch_array($get, MYSQLI_ASSOC)){
							echo "<tr>";
							echo "<td>".$result['I_ID']."</td>";
							echo "<td>".$result['EST_TIME']."</td>";
							echo "<td>".$result['RET_TIME']."</td>";
							echo "<td>".$result['F_U_ID']."</td>";
							echo "<td>".$result['L_U_ID']."</td>";
							echo "<td><button type='button' onclick='view(".$result['I_ID'].")'>檢視</button></td>";
							echo "</td>";
						}
					?>
				</table>
			<?php 
				}
			?>
		</center>
	</div>
</body>
</html>