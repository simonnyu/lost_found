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
			location.href="user_mng.php?view_id="+id;
		}
		function mod(id){
			location.href="user_mng.php?mod_id="+id;
		}
		function resetpwd(id){
			if(confirm("確定要重設嗎?")){
				location.href="pwdreset.php?id="+id;
			}
		}function del(id){
			if(confirm("確定要刪除嗎?")){
				location.href="user_mng.php?del_id="+id;
			}
		}
		function adduser(){
			location.href="adduser.php";
		}
	</script>
</head>
<body>
	<div id="container">
		<div><a href="index.php"><img src="../assect/img/pdc_title.jpg" width="100%"></a></div>
		<div>
			<center>
			<?php
				if(isset($_GET['view_id'])){
					$get_sql = "select * from user where U_ID = '".$_GET['view_id']."'";
					$get = mysqli_query($link, $get_sql);
					$result = mysqli_fetch_array($get, MYSQLI_ASSOC);
			?>
				<br/>		
				<table>
					<tr>
						<td align="center">ID</td>
						<td><?php echo $result['U_ID']; ?></td>
					</tr>
					<tr>
						<td align="center">姓名</td>
						<td><?php echo $result['U_NAME']; ?></td>
					</tr>
					<tr>
						<td align="center">生日</td>
						<td><?php echo $result['U_BRTH']; ?></td>
					</tr>
					<tr>
						<td align="center">EMAIL</td>
						<td><?php echo $result['U_EMAIL']; ?></td>
					</tr>
					<tr>
						<td align="center">手機</td>
						<td><?php echo $result['U_CEL']; ?></td>
					</tr>
					<tr>
						<td colspan="2" align="right"><?php echo "<button type='button' onclick='mod(\"".$result['U_ID']."\")'>修改</button>"; ?></td>
					</tr>
				</table>
			<?php
				}elseif(isset($_GET['mod_id'])){
					$get_sql = "select * from user,AUTH where user.U_ID = AUTH.U_ID and user.U_ID = '".$_GET['mod_id']."'";
					$get = mysqli_query($link, $get_sql);
					$result = mysqli_fetch_array($get, MYSQLI_ASSOC);
			?>
				<br/>		
				<table>
					<form action="user_mng.php" method="POST">
						<tr>
							<td align="center">ID</td>
							<?php echo "<td align='center' class='td1'><input type='hidden' name='id' value='".$result['U_ID']."' required>".$result['U_ID']."</input></td>"; ?>
						</tr>
						<tr>
							<td align="center">姓名</td>
							<?php echo "<td align='center'><input type='text' name='name' value='".$result['U_NAME']."' required></input></td>"; ?>
						</tr>
						<tr>
							<td align="center">生日</td>
							<?php echo "<td align='center'><input type='date' name='brth' value='".$result['U_BRTH']."' required></input></td>"; ?>
						</tr>
						<tr>
							<td align="center">EMAIL</td>
							<?php echo "<td align='center'><input type='text' name='mail' value='".$result['U_EMAIL']."' required></input></td>"; ?>
						</tr>
						<tr>
							<td align="center">手機</td>
							<?php echo "<td align='center'><input type='text' name='cel' value='".$result['U_CEL']."' required></input></td>"; ?>
						</tr>
						<tr>
							<td align="center">管理者</td>
							<?php
								if($result['U_PRE']==1){
									echo "<td><input type='radio' name='pre' value='1' checked required>是</input>";
									echo "<input type='radio' name='pre' value='0' required>否</input></td>";
								}else{
									echo "<td><input type='radio' name='pre' value='1' required>是</input>";
									echo "<input type='radio' name='pre' value='0' checked required>否</input></td>";
								}
							?>
						</tr>
						<tr>
							<?php echo "<td align='right' colspan='2'><button type='button' onclick='history.back()'>取消</button> <button type='button' onclick='resetpwd(\"".$result['U_ID']."\")'>密碼重設</button> <input type='submit' value='送出''></input></td>"; ?>
						</tr>
					</form>
				</table>
				<?php
				}elseif(isset($_POST['id'])){
					$update_sql = "update user,AUTH set user.U_NAME = '".$_POST['name']."', user.U_BRTH = '".$_POST['brth']."', user.U_EMAIL = '".$_POST['mail']."', user.U_CEL = '".$_POST['cel']."', AUTH.U_PRE = '".$_POST['pre']."' where AUTH.U_ID = user.U_ID and user.U_ID = '".$_POST['id']."'";
					if(mysqli_query($link, $update_sql)){
						echo "<p><b>資料更新成功</b></p>";
						echo "系統將於3秒後轉跳至管理頁面<br/>";
						header("Refresh: 3; url=user_mng.php");
					}else{
						echo "<p><b>資料更新失敗</b></p>";
						echo "系統將於3秒後轉跳至管理頁面<br/>";
						header("Refresh: 3; url=user_mng.php");
					}
				}elseif (isset($_GET['del_id'])) {
					$del_auth = "DELETE from AUTH where U_ID = '".$_GET['del_id']."'";
					$del_user = "DELETE from user where U_ID = '".$_GET['del_id']."'";
					$is_oauth_sql = "SELECT * from OAUTH where U_ID = '".$_GET['del_id']."'";
					$is_oauth = mysqli_query($link, $is_oauth_sql);
					if(mysqli_num_rows($is_oauth)==0){
						if(mysqli_query($link, $del_auth) && mysqli_query($link, $del_user)){
						echo "<p><b>使用者刪除成功</b></p>";
						echo "系統將於3秒後轉跳至管理頁面<br/>";
						header("Refresh: 3; url=user_mng.php");
					}else{
						echo "<p><b>使用者刪除失敗</b></p>";
						echo "系統將於3秒後轉跳至管理頁面<br/>";
						header("Refresh: 3; url=user_mng.php");
					}
					}else{
						$del_oauth = "DELETE from OAUTH where U_ID = '".$_GET['del_id']."'";
						if(mysqli_query($link, $del_auth) && mysqli_query($link, $del_user) && mysqli_query($link, $del_oauth)){
							echo "<p><b>使用者刪除成功</b></p>";
							echo "系統將於3秒後轉跳至管理頁面<br/>";
							header("Refresh: 3; url=user_mng.php");
						}else{
							echo "<p><b>使用者刪除失敗</b></p>";
							echo "系統將於3秒後轉跳至管理頁面<br/>";
							header("Refresh: 3; url=user_mng.php");
						}
					}
					

				}else{
				?>
					<table>
						<tr>
							<th align='center'>ID</th>
							<th align='center'>姓名</th>
							<th align='center'></th>
							<th align='center'></th>
							<th align="center"></th>
						</tr>
						<?php
							$get_sql = "select * from user";
							$get = mysqli_query($link, $get_sql);
							while ($result = mysqli_fetch_array($get, MYSQLI_ASSOC)) {
								echo "<tr>";
								echo "<td align='center'>".$result['U_ID']."</td>";
								echo "<td align='center'>".$result['U_NAME']."</td>";
								echo "<td align='center'><button type='button' onclick='view(\"".$result['U_ID']."\")'>檢視</button></td>";
								echo "<td align='center'><button type='button' onclick='mod(\"".$result['U_ID']."\")'>修改</button></td>";
								echo "<td align='center'><button type='button' onclick='del(\"".$result['U_ID']."\")'>刪除</button></td>";
								echo "</tr>";
							}
						?>
						<tr>
							<td colspan="5" align="center"><button type="button" onclick="adduser()">新增使用者</button></td>
						</tr>
					</table>
				<?php } ?>
			</center>
		</div>
	</div>
</body>
</html>