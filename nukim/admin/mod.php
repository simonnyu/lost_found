<?php
	$cooke_name = "s_user";
	if($_COOKIE[$cooke_name]==null){
		header("Location: login.php");
	}//沒有登入狀態則導向登入畫面
	require_once("../db_config.php");
	$u_id = $_COOKIE[$cooke_name];
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
			width: 40%;
			float: left;
	        
		}
		
		#div_right{
			width: 60%;
			float: left;
			margin: 0px auto;
			font-family:"Microsoft JhengHei";
		}
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
	select {
    	width: 100%;
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
	</style>
</head>
<body>
	<div id="container">
		<div><p><img src="../assect/img/pdc_title.jpg" width="100%"></img></p></div>
		<center>
		<div><p><b>修改物品資訊</b></p></div>
		<?php
			if(isset($_GET['ismod'])){
				$update_item_sql = "update item set CAT_ID='".$_POST['category']."', LOC_ID='".$_POST['location']."', TIME='".$_POST['time']."', I_DICPT='".$_POST['dicpt']."' WHERE I_ID='".$_POST['id']."'";
				mysqli_query($link, $update_item_sql);
				echo "<p><b>修改成功!!</b></p>";
				echo "<p>系統將於三秒後回到失物管理頁面</p>";
				header("Refresh: 3; url=lost_mng.php");
			}elseif(isset($_GET['del_id'])){
				$del_sql = "delete from item where I_ID = '".$_GET['del_id']."'";
				mysqli_query($link, $del_sql);
				echo "<p><b>刪除成功</b></p>";
				echo "<p>系統將於三秒後回到失物管理頁面</p>";
				header("Refresh: 3; url=lost_mng.php");
			}elseif(isset($_GET['mod_id'])){
				$get_detail_sql = "SELECT * FROM item NATURAL JOIN location NATURAL JOIN cat NATURAL JOIN pic NATURAL JOIN user WHERE I_ID = '".$_GET['mod_id']."'";
				$get_detail = mysqli_query($link, $get_detail_sql);
				$detail = mysqli_fetch_array($get_detail, MYSQLI_ASSOC);
		?>
			<div id="div_left">
				<center><?php echo "<a href='../".$detail['P_LOC']."'><img src='../".$detail['P_N_LOC']."'></img></a>"; ?></center>
			</div>
			<div id="div_right">
				<form action="mod.php?ismod=1" method="POST">
					<?php echo "<input type='hidden' name='id' value= '".$_GET['mod_id']."'></input>"?>
					<table>
					<?php
						echo "<tr>";
						echo "<td>拾獲者編號</td>";
						echo "<td>".$detail['U_ID']."</td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td>拾獲者姓名</td>";
						echo "<td>".$detail['U_NAME']."</td>";
						echo "</tr>";
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
							<td><input type="button" onclick="history.back()" value="上一頁"></input></td>
							<td><input type="submit" value="送出資料"></input></td>
						</tr>
					</table>
				</form>
				<?php } ?>
			</div>
		</center>
	</div>
</body>