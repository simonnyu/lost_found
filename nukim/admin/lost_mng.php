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
	</style>
	<script type="text/javascript">
		function view(id){
			location.href="mod.php?mod_id="+id;
		}
		function del(id){
			if(confirm("確定要刪除嗎?")){
				location.href="mod.php?del_id="+id;
			}
		}
	</script>
</head>
<body>
	<div id="container">
		<div><a href="index.php"><img src="../assect/img/pdc_title.jpg" width="100%"></a></div>
		<div>
			<div id = "div_left">
				<p><font size="6" face="微軟正黑體"><b><center>搜尋設定</center></font></b></font></p>
				<form action="lost_mng.php" method="GET">
					<input type="hidden" name="search" value="1"></input>
					<center>
						<div>
						<font face="微軟正黑體">
							<p>時間</p>
							<p>從<input type="date" name="sta_time" <?php if(isset($_GET['sta_time'])){echo "value='".$_GET['sta_time']."'";} ?>></input></p>
							<p>到<input type="date" name="end_time" <?php if(isset($_GET['end_time'])){echo "value='".$_GET['end_time']."'";} ?>></input></p>
							</font>
						</div>
						<div>
							<p>
							<font face="微軟正黑體">
								地點
								<select name="location">
									<option value="">請選擇</option>
									<?php
										$get_loc_sql = "select * from location";
										$query = mysqli_query($link,$get_loc_sql);
										while ($result = mysqli_fetch_array($query,MYSQLI_ASSOC)) {
											if(isset($_GET['location']) && ($_GET['location'] == $result['LOC_ID'])){
												echo "<option value='".$result['LOC_ID']."' selected>".$result['LOC_NAME']."</option>";
											}else{
												echo "<option value='".$result['LOC_ID']."' >".$result['LOC_NAME']."</option>";
											}
										}
									?>
								</select>
								</font>
							</p>
						</div>
						<div>
							<p>
							<font face="微軟正黑體">
								物品類別
								<select name="category">
									<option value="">請選擇</option>
							</font>
									<?php
										$get_cat_sql = "select * from cat";
										$query = mysqli_query($link,$get_cat_sql);
										while ($result = mysqli_fetch_array($query,MYSQLI_ASSOC)) {
											if(isset($_GET['category']) && ($_GET['category'] == $result['CAT_ID'])){
												echo "<option value='".$result['CAT_ID']."' selected>".$result['CAT_NAME']."</option>";
											}else{
												echo "<option value='".$result['CAT_ID']."' >".$result['CAT_NAME']."</option>";
											}
										}
									?>
								</select>
							</p>
						</div>
						<div>
							
							<input type="reset" value="重新設定"></input>
							<input type="submit" value="送出查詢"></input>
						</div>
					</center>
				</form>
			</div>
			<div id = "div_right">
				<table width="100%">
					<?php
					echo "<br/>";
					echo "<br/>";
						$current_url="";
						$get_item_sql = "SELECT * FROM item NATURAL JOIN location NATURAL JOIN cat";
						if (isset($_GET['search'])) {
							$current_url = "search=1&sta_time=".$_GET['sta_time']."&end_time=".$_GET['end_time']."&location=".$_GET['location']."&category=".$_GET['category'];
							$search_loc = 1;
							$count = 0;
							if($_GET['sta_time']!=""){
								if($_GET['end_time']!=""){
									$get_item_sql .= " WHERE TIME BETWEEN '".$_GET['sta_time']."' AND '".$_GET['end_time']."'";
								}else{
									$get_item_sql .= " WHERE TIME BETWEEN '".$_GET['sta_time']."' AND CURDATE()";
								}
								$count = 1;
							}
							if($_GET['location']!=""){
								if($count==1){
									$get_item_sql .= " AND LOC_ID='".$_GET['location']."'";
								}else{
									$get_item_sql .= " WHERE LOC_ID='".$_GET['location']."'";
									$count=1;
								}
							}
							if($_GET['category']!=""){
								if($count==1){
									$get_item_sql .= " AND CAT_ID='".$_GET['category']."'";
								}else{
									$get_item_sql .= " WHERE CAT_ID='".$_GET['category']."'";
									$count=1;
								}
							}
						}
						if(isset($_GET['s_id'])){
							if($_GET['s_id']==0){
								$get_item_sql .= " ORDER BY I_ID ASC";
							}else{
								$get_item_sql .= " ORDER BY I_ID DESC";
							}
						}
						if(isset($_GET['s_ti'])){
							if($_GET['s_ti']==0){
								$get_item_sql .= " ORDER BY TIME ASC";
							}else{
								$get_item_sql .= " ORDER BY TIME DESC";
							}
						}
						if(isset($_GET['s_ca'])){
							if($_GET['s_ca']==0){
								$get_item_sql .= " ORDER BY CAT_NAME ASC";
							}else{
								$get_item_sql .= " ORDER BY CAT_NAME DESC";
							}
						}
						if(isset($_GET['s_lo'])){
							if($_GET['s_lo']==0){
								$get_item_sql .= " ORDER BY LOC_NAME ASC";
							}else{
								$get_item_sql .= " ORDER BY LOC_NAME DESC";
							}
						}
						$set_url = "lost.php?".$current_url;
						$sort_id = (@$_GET['s_id']==1) ? 0 : 1 ;
						$sort_ti = (@$_GET['s_ti']==1) ? 0 : 1 ;
						$sort_ca = (@$_GET['s_ca']==1) ? 0 : 1 ;
						$sort_lo = (@$_GET['s_lo']==1) ? 0 : 1 ;
						echo "<tr>";
						echo "<th><a href='".$set_url."&s_id=".$sort_id."'><h3>編號</h3></a></th>";
						echo "<th><a href='".$set_url."&s_ti=".$sort_ti."'><h3>拾獲時間</h3></a></th>";
						echo "<th><a href='".$set_url."&s_ca=".$sort_ca."'><h3>物品類別</h3></a></th>";
						echo "<th><a href='".$set_url."&s_lo=".$sort_lo."'><h3>拾獲地點</h3></a></th>";
						echo "<th></th>";
						echo "<th></th>";
						echo "</tr>";
						$result_query = mysqli_query($link, $get_item_sql);
						while ($result=mysqli_fetch_array($result_query,MYSQLI_ASSOC)) {
							if($result['I_STAT']==0){
								echo "<tr>";
								echo "<td align='center'>".$result['I_ID']."</td>";
								echo "<td align='center'>".$result['TIME']."</td>";
								echo "<td align='center'>".$result['CAT_NAME']."</td>";
								echo "<td align='center'>".$result['LOC_NAME']."</td>";
								echo "<td align='center'><button type='button' onclick='view(".$result['I_ID'].")'>修改</button></td>";
								echo "<td align='center'><button type='button' onclick='del(".$result['I_ID'].")'>刪除</button></td>";
								echo "</tr>";
							}
						}
						echo "</center>";
					?>
				</table>
			</div>
		</div>
	</div>
</body>
</html>