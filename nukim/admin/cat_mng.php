<?php
	$cooke_name = "s_user";
	if($_COOKIE[$cooke_name]==null){
		header("Location: login.php");
	}//沒有登入狀態則導向登入畫面
	require_once("../db_config.php");
	$u_id = $_COOKIE[$cooke_name];
	if($_POST['n']==1){
		$new_loc_sql = "insert into cat (CAT_ID, CAT_NAME) values (NULL, '".$_POST['item']."')";
		mysqli_query($link, $new_loc_sql);
	}elseif (isset($_POST['c_id'])){
		$update_sql = "update cat set CAT_NAME = '".$_POST['c_name']."' where CAT_ID = '".$_POST['c_id']."'";
		mysqli_query($link, $update_sql);
	}elseif (isset($_GET['del_id'])){
		$del_sql = "delete from cat where CAT_ID = '".$_GET['del_id']."'";
		mysqli_query($link, $del_sql);
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
	select {
    	padding: 16px 20px;
    	border: none;
    	border-radius: 4px;
    	background-color: #f1f1f1;
	}
	input[type=text]{
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
			location.href="cat_mng.php?mod_id="+id;
		}
		function del(id){
			if(confirm("確定要刪除嗎?")){
				location.href="cat_mng.php?del_id="+id;
			}
		}
	</script>
</head>
<body>
	<div id="container">
		<div><a href="index.php"><img src="../assect/img/pdc_title.jpg" width="100%"></a></div>
		<center>
			<table>
				<tr>
					<th align='center'>類別編號</th>
					<th align='center'>類別名稱</th>
					<th align='center'></th>
					<th align='center'></th>
				</tr>
				<?php
					$cat_sql = "select * from cat";
					$cat = mysqli_query($link, $cat_sql);
					while($result = mysqli_fetch_array($cat, MYSQLI_ASSOC)){
						echo "<tr>";
						echo "<td align='center'>".$result['CAT_ID']."</td>";
						if($_GET['mod_id']==$result['CAT_ID']){
							echo "<form action='cat_mng.php' method='POST'>";
							echo "<input type='hidden' name='c_id' value='".$result['CAT_ID']."'></input>";
							echo "<td align='center'><input type='text' name='c_name' value='".$result['CAT_NAME']."'></input></td>";
							echo "<td align='center'><input type='submit' value='送出'></input></td>";
							echo "</form>";
							echo "<td align='center'></td>";
						}else{
							echo "<td align='center'>".$result['CAT_NAME']."</td>";
							echo "<td align='center'><button type='button' onclick='view(".$result['CAT_ID'].")'>修改</button></td>";
							echo "<td align='center'><button type='button' onclick='del(".$result['CAT_ID'].")'>刪除</button></td>";
						}
						echo "</tr>";
					}
					if(!isset($_GET['mod_id'])){
				?>
				<form action="cat_mng.php" method="POST">
				<input type="hidden" name="n" value="1"></input>
					<tr>
						<td></td>
						<td>
							<input type="text" name="item" placeholder="新增類別" required></input>
						</td>
						<td><input type="submit" value="送出"></input></td>
						<td></td>
					</tr>
				</form>
				<?php } ?>
			</table>
		</center>
	</div>
</body>
</html>