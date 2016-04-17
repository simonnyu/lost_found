<?php
	$cooke_name = "c_user";
	if($_COOKIE[$cooke_name]==null){
		header("Location: login.php");
	}//沒有登入狀態則導向登入畫面
	require_once("db_config.php");
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
		#div_rank{
			width: 50%;
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
	table {
    	border-collapse: collapse;
    	width: 99%;
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
	    text-align: center;
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
		function view(id){
			var url = "detail.php?id="+id;
			window.open(url, '_blank');
		}
	</script>
</head>
<body>
	<div id="container">
		<div><a href="index.php"><img src="assect/img/pdc_title.jpg" width="100%"></a></div>
		<div>
			<div id = "div_left">
				<p><font size="6" face="微軟正黑體"><b><center>資料匯出</center></font></b></font></p>
				<form action="export_file.php" method="GET">
					<input type="hidden" name="set" value="1"></input>
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
							<p>匯出檔案類型</p>
							<input type="radio" name="ftype" value="csv" required>csv</input>
							<input type="radio" name="ftype" value="pdf" required>pdf</input>
						</div>
						<div>
							<input type="reset" value="重新設定"></input>
							<input type="submit" value="匯出檔案"></input>
						</div>
					</center>
				</form>
			</div>
			<div id="div_right">
			<form action="export_file.php" method="POST">
				<input type="hidden" name="rank" value="1"></input>
				<?php
					$get_sql = "select * from item NATURAL JOIN location NATURAL JOIN cat";
					$get = mysqli_query($link, $get_sql);
					$arr_cat = "";
					$arr_loc = "";
					while($result = mysqli_fetch_array($get,MYSQLI_ASSOC)){
						if(array_key_exists($result['CAT_NAME'], $arr_cat)){
							$arr_cat[$result['CAT_NAME']] += 1;
						}else{
							$arr_cat[$result['CAT_NAME']] = 1;
						}
						if(array_key_exists($result['LOC_NAME'], $arr_loc)){
							$arr_loc[$result['LOC_NAME']] += 1;
						}else{
							$arr_loc[$result['LOC_NAME']] = 1;
						}
					}
					arsort($arr_cat);
					arsort($arr_loc);
				?>
					<div id = "div_rank">
						<br/>
						<table>
							<caption>遺失物品類別排行</caption>
							<tr>
								<th>名次</th>
								<th>物品類別</th>
								<th>件數</th>
							</tr>
							<?php 
								$rank = 1;
								foreach ($arr_cat as $key => $value) {
									echo "<tr><td>".$rank."</td><td>".$key."</td><td>".$value."</td></tr>";
									$rank += 1;
								}
							?>
						</table>
					</div>
					<div id = "div_rank">
						<br/>
						<table>
							<caption>遺失地點排行</caption>
							<tr>
								<th>名次</th>
								<th>地點</th>
								<th>件數</th>
							</tr>
							<?php 
								$rank = 1;
								foreach ($arr_loc as $key => $value) {
									echo "<tr><td>".$rank."</td><td>".$key."</td><td>".$value."</td></tr>";
									$rank += 1;
								}
							?>
							</table>
					</div>
					<p align="center"><input type="submit" value="匯出排行資料(PDF)"></input></p>
				</form>
			</div>
		</div>
	</div>
</body>
</html>