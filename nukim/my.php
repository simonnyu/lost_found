<?php
	$cooke_name = "c_user";
	if($_COOKIE[$cooke_name]==null){
		header("Location: login.php");
	}//沒有登入狀態則導向登入畫面
	require_once("db_config.php");
	$u_id = $_COOKIE[$cooke_name];
	if($u_id == "guest"){
		header("Location: index.php");
	}
	if(isset($_GET['mod'])){
		$update_item_sql = "update item set CAT_ID='".$_POST['category']."', LOC_ID='".$_POST['location']."', TIME='".$_POST['time']."', I_DICPT='".$_POST['dicpt']."' WHERE I_ID='".$_POST['id']."'";
		mysqli_query($link, $update_item_sql);
	}
?>
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
			width: 55%;
			float: left;
		}
		#div_right{
			width: 45%;
			float: left;
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
	table {
    	border-collapse: collapse;
	}
	tr:nth-child(even) {
		background-color: #f2f2f2;
	}
	td,th{
	    padding: 12px 20px;
	    margin: 8px 0;
	    box-sizing: border-box;
	    -webkit-transition: 0.5s;
	    transition: 0.5s;
	    outline: none;
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
		function done(id){
			if (confirm("物品已物歸原主了嗎?")){
				location.href="my.php?done_id="+id;
			}
		}
		function undo(id){
			if (confirm("確定聯絡過後，發現不是他的嗎?")){
				location.href="my.php?undo_id="+id;
			}
		}
		function del(id){
			if(confirm("確定要刪除嗎?")){
				location.href="my.php?del_id="+id;
			}
		}
		function view(id){
			var url = "my.php?view_id="+id;
			window.open(url, '_blank');
		}
		function mod(id){
			var url = "my.php?mod_id="+id;
			window.open(url, '_blank');
		}
		function view_p(id){
			var url = "my.php?view_id_p="+id;
			window.open(url, '_blank');
		}
	</script>
</head>
<body>
	<div id="container">
	<center>
	<div><a href="index.php"><img src="assect/img/pdc_title.jpg" width="100%"></a></div>	
		<?php
			if (isset($_GET['mod_id'])){
			$get_detail_sql = "SELECT * FROM item NATURAL JOIN location NATURAL JOIN cat NATURAL JOIN pic WHERE I_ID = '".$_GET['mod_id']."'";
			$get_detail = mysqli_query($link, $get_detail_sql);
			$detail = mysqli_fetch_array($get_detail, MYSQLI_ASSOC);
			if (strcmp($u_id, $detail['U_ID'])!=0){
				echo "<p>您不能修改這筆資料，這筆資料並不屬於您。</p>";
				echo "<a href='index.php'>按此回到首頁</a>";
			}else{
		?>
			<div id="div_left">
				<center><?php echo "<a href='".$detail['P_LOC']."'><img src='".$detail['P_N_LOC']."'></img></a>"; ?></center>
			</div>
			<div id="div_right">
				<form action="my.php?mod=1" method="POST">
					<?php echo "<input type='hidden' name='id' value= '".$_GET['mod_id']."'></input>"?>
					<table>
					<?php
						echo "<tr>";
						echo "<td>拾獲時間</td>";
						echo "<td><input type='date' name='time' value='".$detail['TIME']."'></input></td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td>物品類別</td>";
						?>
						<td><select name="category">
							<option value="">請選擇</option>
						<?php
						$get_loc_sql = "select * from cat";
						$query = mysqli_query($link,$get_loc_sql);
							while ($result = mysqli_fetch_array($query,MYSQLI_ASSOC)) {
								if (strcmp($result['CAT_ID'], $detail['CAT_ID'])==0){
									echo "<option value='".$result['CAT_ID']."' selected >".$result['CAT_NAME']."</option>";
								}
								else{
									echo "<option value='".$result['CAT_ID']."'>".$result['CAT_NAME']."</option>";
								}
							}
						?>
						</select></td>
						<?php
						echo "</tr>";
						echo "<tr>";
						echo "<td>拾獲地點</td>";
						?>
						<td><select name="location">
							<option value="">請選擇</option>
						<?php
						$get_loc_sql = "select * from location";
						$query = mysqli_query($link,$get_loc_sql);
							while ($result = mysqli_fetch_array($query,MYSQLI_ASSOC)) {
								if (strcmp($result['LOC_ID'], $detail['LOC_ID'])==0){
									echo "<option value='".$result['LOC_ID']."' selected >".$result['LOC_NAME']."</option>";
								}
								else{
									echo "<option value='".$result['LOC_ID']."'>".$result['LOC_NAME']."</option>";
								}
							}
						?>
						</select></td>
						<?php
						echo "</tr>";
						echo "<tr>";
						echo "<td colspan='2'>物品描述</td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td colspan='2'><textarea name='dicpt'>".$detail['I_DICPT']."</textarea></td>";
						echo "</tr>";
					
					?>
						<tr>
							<td><input type="reset" value="重新填寫"></input></td>
							<td><input type="submit" value="送出資料"></input></td>
						</tr>
					</table>
				</form>
				<?php } ?>
			</div>
			
		<?php
			}elseif (isset($_GET['del_id'])){
				$del_sql = "delete from item where I_ID = '".$_GET['del_id']."'";
				mysqli_query($link, $del_sql);
				echo "<p><b>刪除成功</b></p>";
				echo "<p>系統將於三秒後回到失物管理頁面</p>";
				header("Refresh: 3; url=my.php");
			}elseif (isset($_GET['view_id'])){
				$get_detail_sql = "SELECT * FROM item NATURAL JOIN location NATURAL JOIN cat NATURAL JOIN pic WHERE I_ID = '".$_GET['view_id']."'";
				$get_lost_sql = "select * from user, history WHERE L_U_ID = U_ID AND I_ID = '".$_GET['view_id']."'";
				$get_detail = mysqli_query($link, $get_detail_sql);
				$get_lost = mysqli_query($link, $get_lost_sql);
				$lost = mysqli_fetch_array($get_lost, MYSQLI_ASSOC);
				$detail = mysqli_fetch_array($get_detail, MYSQLI_ASSOC);
				if (strcmp($u_id, $detail['U_ID'])!=0){
					echo "<p>您不能更動這筆資料，這筆資料並不屬於您。</p>";
					echo "<a href='index.php'>按此回到首頁</a>";
				}else{
			?>	
				<font face="微軟正黑體" size="6"><center><p><b>歷史資料</b></p></center></font>
				<div id="div_left">
					<center><?php echo "<a href='".$detail['P_LOC']."'><img src='".$detail['P_N_LOC']."'></img></a>"; ?></center>
				</div>
				<div id="div_right">
					<form action="my.php?mod=1" method="POST">
						<?php echo "<input type='hidden' name='id' value= '".$_GET['mod_id']."'></input>"?>

						<table width="100%">
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
							if ($detail['I_STAT']==1){
								echo "<input type ='button' onclick='done(".$_GET['view_id'].")' value='物品已歸還'></input>";
								echo "<input type ='button' onclick='undo(".$_GET['view_id'].")' value='繼續等待主人'></input>";
							}
                            echo "</center>";
						?>
						
					</form>
					<?php 
				}
			}elseif (isset($_GET['view_id_p'])){
				$get_detail_sql = "SELECT * FROM item NATURAL JOIN location NATURAL JOIN cat NATURAL JOIN pic NATURAL JOIN user NATURAL JOIN OAUTH WHERE I_ID = '".$_GET['view_id_p']."'";
				$get_detail = mysqli_query($link, $get_detail_sql);
				$detail = mysqli_fetch_array($get_detail, MYSQLI_ASSOC);
				?>
			<font face="微軟正黑體" size="6"><center><p><b>認領紀錄</b></p></center></font>
			<div id="div_left"><center>
			<?php echo "<a href='".$detail['P_LOC']."'><img src='".$detail['P_N_LOC']."'></img></a>"; ?></center>
			</div>
			<div id="div_right">
			<font size="4" face="微軟正黑體">
			<?php
				echo "<center>";
				echo "<table width='100%'>";
				echo "<b><tr><td colspan='2' align='center'>物品資訊</td></tr></b>";
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
				echo "<tr><td colspan='2'><hr/></td></tr>";
				echo "</tr>";
				echo "<tr><td colspan='2' align='center'>拾獲人聯絡資料</td></tr>";
				echo "<tr>";
				echo "<td class='col_1'>姓名</td>";
				echo "<td class='col_2'>".$detail['U_NAME']."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td class='col_1'>手機</td>";
				echo "<td class='col_2'>".$detail['U_CEL']."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td class='col_1'>E-mail</td>";
				echo "<td class='col_2'>".$detail['U_EMAIL']."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td class='col_1'>Facebook</td>";
				if ($detail['facebook']==1){
					echo "<td class='col_2'><a href='".$detail['F_PROFIILE_URL']."'>".$detail['F_PROFIILE_URL']."</a></td>";
				}elseif ($detail['facebook']==2) {
					echo "<td> class='col_2'<a href='".$detail['fb_url']."'>".$detail['fb_url']."</a></td>";
				}else{
					echo "<td class='col_2'>使用者尚未提供Facebook資訊</td>";
				}
				echo "</tr>";
				echo "</table>";
				echo "</center>";
			}elseif (isset($_GET['done_id'])){
				$done_sql = "update item, history set item.I_STAT = '2', history.RET_TIME = CURDATE() where history.I_ID = item.I_ID AND item.I_ID = '".$_GET['done_id']."'";
				mysqli_query($link, $done_sql);
				echo "<center>感謝您的協助<br/>系統將於3秒後轉跳至首頁<br/></center>";
				header("Refresh: 3; url=index.php");
			}elseif (isset($_GET['undo_id'])){
				$undo_sql = "update item set I_STAT = '0' where I_ID = '".$_GET['undo_id']."'";
				mysqli_query($link, $undo_sql);
				$undo_sql = "delete from history where I_ID = '".$_GET['undo_id']."'";
				mysqli_query($link, $undo_sql);
				echo "<center>感謝您的協助<br/>系統將於3秒後轉跳至首頁<br/></center>";
				header("Refresh: 3; url=index.php");
			}else{
			?>
			<font face="微軟正黑體" size="6"><p><b>我的拾獲資料</b></p></font>
			<table width="100%">
			<tr>
				<th>認領狀況</th>
				<th>編號</th>
				<th>拾獲時間</th>
				<th>物品類別</th>
				<th>拾獲地點</th>
				<th>檢視資料</th>
				<th></th>
				<th></th>
			</tr>
			<?php
				$get_item_sql = "SELECT * FROM item NATURAL JOIN location NATURAL JOIN cat WHERE U_ID='".$u_id."'";
				$result_query = mysqli_query($link, $get_item_sql);
					while ($result=mysqli_fetch_array($result_query,MYSQLI_ASSOC)) {
						if ($result['I_STAT']<2){
						echo "<tr>";
						if ($result['I_STAT']==1){
								echo "<td align='center'><input type ='button' onclick='view(".$result['I_ID'].")' value='有人認領'></input></td>";
						}else{

							echo "<td  align='center'><font color='red'>尚未認領</font></td>";
						}
							echo "<td align='center'>".$result['I_ID']."</td>";
							echo "<td align='center'>".$result['TIME']."</td>";
							echo "<td align='center'>".$result['CAT_NAME']."</td>";
							echo "<td align='center'>".$result['LOC_NAME']."</td>";
							echo "<td align='center'><input type ='button' onclick='view(".$result['I_ID'].")' value='檢視'></input></td>";
							echo "<td align='center'><input type ='button' onclick='mod(".$result['I_ID'].")' value='修改'></input></td>";
							echo "<td align='center'><button type='button' onclick='del(".$result['I_ID'].")'>刪除</button></td>";
							echo "</tr>";
						}
					}
				echo "</table>";
		?>
		<font face="微軟正黑體" size="6"><center><p><b>歷史資料</b></p></center></font>
		<table align="center" width="70%">
			<tr>
				<th>編號</th>
				<th>拾獲時間</th>
				<th>物品類別</th>
				<th>拾獲地點</th>
				<th></th>
			</tr>
			<?php
				$result_query = mysqli_query($link, $get_item_sql);
				while ($result=mysqli_fetch_array($result_query,MYSQLI_ASSOC)) {
					if($result['I_STAT']==2){
						echo "<tr>";
						echo "<td align='center'>".$result['I_ID']."</td>";
						echo "<td align='center'>".$result['TIME']."</td>";
						echo "<td align='center'>".$result['CAT_NAME']."</td>";
						echo "<td align='center'>".$result['LOC_NAME']."</td>";
						echo "<td align='center'><input type ='button' onclick='view(".$result['I_ID'].")' value='檢視'></input></td>";
						echo "</tr>";
					}
				}
			?>
		</table>
		<font face="微軟正黑體" size="6"><center><p><b>認領紀錄</b></p></center></font>
		<table align="center" width="70%">
			<tr>
				<th>編號</th>
				<th>認領時間</th>
				<th>歸還時間</th>
				<th></th>
			</tr>
			<?php
				$get_item_sql = "select * from history where L_U_ID='".$u_id."'";
				$result_query = mysqli_query($link, $get_item_sql);
				while ($result=mysqli_fetch_array($result_query,MYSQLI_ASSOC)) {
						echo "<tr>";
						echo "<td align='center'>".$result['I_ID']."</td>";
						echo "<td align='center'>".$result['EST_TIME']."</td>";
						echo "<td align='center'>".$result['RET_TIME']."</td>";
						echo "<td align='center'><input type ='button' onclick='view_p(".$result['I_ID'].")' value='檢視'></input></td>";
						echo "</tr>";
					}
				}
			?>
		</table>
		</center>
	</div>
</body>