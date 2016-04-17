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
		#div_oauth{
			width: 50%;
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
		function unlink_fb(id){
			if(confirm("確定要刪除嗎?")){
				location.href="oauth_mng.php?del_id_f="+id;
			}
		}
		function unlink_g(id){
			if(confirm("確定要刪除嗎?")){
				location.href="oauth_mng.php?del_id_g="+id;
			}
		}

	</script>
</head>
<body>
	<div id="container">
		<div><a href="index.php"><img src="../assect/img/pdc_title.jpg" width="100%"></a></div>
		<div>
			<center>
			<?php
				if(isset($_GET['del_id_f'])){
					$del_sql = "delete from OAUTH where U_ID = '".$_GET['del_id_f']."'";
					if(mysqli_query($link, $del_sql)){
						echo "<p><b>取消關聯成功</b></p>";
						echo "系統將於3秒後轉跳至管理頁面<br/>";
						header("Refresh: 3; url=oauth_mng.php");
					}else{
						echo "<p><b>取消關聯失敗</b></p>";
						echo "系統將於3秒後轉跳至管理頁面<br/>";
						header("Refresh: 3; url=oauth_mng.php");
					}
				}elseif(isset($_GET['del_id_g'])){
					$del_sql = "delete from OAUTH_G where U_ID = '".$_GET['del_id_g']."'";
					if(mysqli_query($link, $del_sql)){
						echo "<p><b>取消關聯成功</b></p>";
						echo "系統將於3秒後轉跳至管理頁面<br/>";
						header("Refresh: 3; url=oauth_mng.php");
					}else{
						echo "<p><b>取消關聯失敗</b></p>";
						echo "系統將於3秒後轉跳至管理頁面<br/>";
						header("Refresh: 3; url=oauth_mng.php");
					}
				}else{
			?>
				<div id="div_oauth">
					<table>
						<tr>
							<th>ID</th>
							<th>姓名</th>
							<th>Facebook</th>
						</tr>
						<?php
							$get_sql = "SELECT * FROM user NATURAL JOIN OAUTH";
							$get = mysqli_query($link, $get_sql);
							while ($result=mysqli_fetch_array($get, MYSQLI_ASSOC)) {
								echo "<tr>";
								echo "<td>".$result['U_ID']."</td>";
								echo "<td>".$result['U_NAME']."</td>";
								if($result['F_ID']!=NULL){
									echo "<td><button type='button' onclick='unlink_fb(\"".$result['U_ID']."\")'>取消關聯</button></td>";
								}else{
									echo "<td></td>";
								}
								echo "</tr>";
							}
						?>
					</table>
				</div>
				<div id="div_oauth">
					<table>
						<tr>
							<th>ID</th>
							<th>姓名</th>
							<th>Google</th>
						</tr>
						<?php
							$get_sql = "SELECT * FROM user NATURAL JOIN OAUTH_G";
							$get = mysqli_query($link, $get_sql);
							while ($result=mysqli_fetch_array($get, MYSQLI_ASSOC)) {
								echo "<tr>";
								echo "<td>".$result['U_ID']."</td>";
								echo "<td>".$result['U_NAME']."</td>";
								if($result['G_ID']!=NULL){
									echo "<td><button type='button' onclick='unlink_g(\"".$result['U_ID']."\")'>取消關聯</button></td>";
								}else{
									echo "<td></td>";
								}
								echo "</tr>";
							}
						}
						?>
					</table>
				</div>
			</center>
		</div>
	</div>
</body>
</html>