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
	function getFtype($FileName){
		$num=strrpos($FileName,"."); 
		return ".".substr($FileName,$num+1);
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
		#found_left{
			width: 50%;
			float: left;

		}
		#found_right{
			width: 50%;
			float: left;

		}
	}	
		#login{
			padding: 8px 16px;
		}
		table {
	        font-family:"Microsoft JhengHei";

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

	</script>
</head>
<body>
	<div id="container">
	    <div><p><a href="index.php"><img src="assect/img/pdc_title.jpg" width="100%"></a></p></div>
		<div>
			<p><center><b><FONT SIZE="6" face="微軟正黑體">登錄遺失物品資訊</FONT></b></center></p>
		</div>
			<?php if(@$_POST['step'] == 2){ 
				$target_dir = "uploads/";
				$target_file = $target_dir . rand(0,99) . "_".basename($_FILES["pic"]["name"]);
				$uploadOk = 1;
				$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
				// Check if image file is a actual image or fake image
				if(isset($_POST["submit"])) {
				    $check = getimagesize($_FILES["pic"]["tmp_name"]);
				    if($check !== false) {
				        echo "File is an image - " . $check["mime"] . ".";
				        $uploadOk = 1;
				    } else {
				        echo "File is not an image.";
				        $uploadOk = 0;
				    }
				}
				// Check if file already exists
				if (file_exists($target_file)) {
				    echo "Sorry, file already exists.";
				    $uploadOk = 0;
				}
				// Check file size
				if ($_FILES["pic"]["size"] > 2048000) {
				    echo "Sorry, your file is too large.";
				    $uploadOk = 0;
				}
				// Allow certain file formats
				if($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "JPG") {
				    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
				    $uploadOk = 0;
				}
				// Check if $uploadOk is set to 0 by an error
				if ($uploadOk == 0) {
				    echo "Sorry, your file was not uploaded.";
				// if everything is ok, try to upload file
				} else {
				    if (move_uploaded_file($_FILES["pic"]["tmp_name"], $target_file)) {
				        $img_type = strtolower(getFtype($target_file));
				        $src = imagecreatefromjpeg($target_file);
				        $src_w = imagesx($src);
				        $src_h = imagesy($src);
				        if( $src_w > $src_h){
							$new_w = $src_h;
							$new_h = $src_h;
						}else{
							$new_w = $src_w;
							$new_h = $src_w;
						}
						$srt_w = ( $src_w - $new_w ) / 2;
						$srt_h = ( $src_h - $new_h ) / 2;
						$newpc = imagecreatetruecolor($new_w,$new_h);
						imagecopy($newpc, $src, 0, 0, $srt_w, $srt_h, $new_w, $new_h ) or die("new");
						$finpic = imagecreatetruecolor(250,250);
						imagecopyresampled($finpic, $newpc, 0, 0, 0, 0, 250, 250, $new_w, $new_h);
						$new_n_src = "uploads/n/n_".basename($_FILES["pic"]["name"]);
						imagejpeg($finpic, $new_n_src);
						$p_no = date('ymdHis');
						$creat_pic_sql = "insert into pic (P_NO,P_N_LOC,P_LOC) values ('".$p_no."', '".$new_n_src."', '".$target_file."')";
						mysqli_query($link, $creat_pic_sql);
				    } else {
				        echo "Sorry, there was an error uploading your file.";
				    }
				}
			?>
				<form action="found.php" method="POST">
				   <center><p><font face="微軟正黑體" size="4"><b>Step.2 輸入物品資料</b></font></p></center>
					  <div id="found_left">
						<center>
						<p><font face="微軟正黑體" size="2">*點擊圖片可以看原始圖*</font></p>
							<input type="hidden" name="step" value="3"></input>
							<?php echo "<input type='hidden' name='pic' value='".$p_no."'></input>"?>
								<?php echo "<a href='".$target_file."' target='_blank'><img src='".$new_n_src."'></img></a>"?><br/>
								<?php echo "<a href='found.php?del=".$p_no."'>圖片錯誤，請回上一頁重新上傳</a>"?></center>
                       </div>
                      
                       <div id="found_right">
                       <br/>
                       <br/>
                       <br/>
						<center><table>
							<tr>
								<td>拾獲時間</td>
								<td><input type="date" name="get_time"></input></td>
							</tr>
							<tr>
								<td>拾獲地點</td>
								<td>
									<select name="location">
										<option value="">請選擇</option>
										<?php
											$get_loc_sql = "select * from location";
											$query = mysqli_query($link,$get_loc_sql);
											while ($result = mysqli_fetch_array($query,MYSQLI_ASSOC)) {
												echo "<option value='".$result['LOC_ID']."'>".$result['LOC_NAME']."</option>";
											}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td>物品類別</td>
								<td>
									<select name="category">
										<option value="">請選擇</option>
										<?php
											$get_cat_sql = "select * from cat";
											$query = mysqli_query($link,$get_cat_sql);
											while ($result = mysqli_fetch_array($query,MYSQLI_ASSOC)) {
												echo "<option value='".$result['CAT_ID']."'>".$result['CAT_NAME']."</option>";
											}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<textarea name="dicpt"></textarea>
								</td>
							</tr>
							<tr>
								
								<td><input type="reset" value="重新填寫"></input></td>
								<td><input type="submit" value="下一步"></input></td>
							</tr>
						</table>
					  </div></center>	
				</form>
			<?php }elseif (@$_POST['step'] == 3) { ?>
				<div>
					<center><p><font face="微軟正黑體" size="4"><b>Step.3 確認物品資料</b></font></p></center>
					<form action="found.php" method="POST">
						<input type="hidden" name="step" value="4"></input>
						<?php
							$cat_n = $_POST['category'];
							$loc_n = $_POST['location'];
							$get_loc_sql = "select * from location where LOC_ID = '".$loc_n."'";
							$query = mysqli_query($link,$get_loc_sql);
							$result = mysqli_fetch_array($query,MYSQLI_ASSOC);
							$loc = $result['LOC_NAME'];
							$get_cat_sql = "select * from cat where CAT_ID = '".$cat_n."'";
							$query = mysqli_query($link,$get_cat_sql);
							$result = mysqli_fetch_array($query,MYSQLI_ASSOC);
							$cat = $result['CAT_NAME'];
							$pic_sql = "select * from pic where P_NO='".$_POST['pic']."'";
							$query = mysqli_query($link, $pic_sql);
							$result = mysqli_fetch_array($query,MYSQLI_ASSOC);
							$pic_n_src = $result["P_N_LOC"];
							$pic_src = $result["P_LOC"];
							echo "<input type='hidden' name='pic' value='".$_POST['pic']."'></input>";
							echo "<input type='hidden' name='get_time' value='".$_POST['get_time']."'></input>";
							echo "<input type='hidden' name='category' value='".$_POST['category']."'></input>";
							echo "<input type='hidden' name='location' value='".$_POST['location']."'></input>";
							echo "<input type='hidden' name='dicpt' value='".$_POST['dicpt']."'></input>";
							echo "<div id='found_left'>";
							echo "<center><img src='".$pic_n_src."'></img></center>";
							echo "</div>";
						?>
						<br/>
						<div id="found_right">
							<table>
								<tr>
									<td>拾獲時間</td>
									<td><?php echo $_POST['get_time'];?></td>
								</tr>
								<tr>
									<td>物品類別</td>
									<td><?php echo $cat;?></td>
								</tr>
								<tr>
									<td>拾獲地點</td>
									<td><?php echo $loc;?></td>
								</tr>
								<tr>
									<td>物品描述</td>
								</tr>
								<tr>
									<td><?php echo $_POST['dicpt'];?></td>
								</tr>
								<tr>
									<td><input type ="button" onclick="history.back()" value="回到上一頁"></input></td>
									<td><input type="submit" value="下一步"></input></td>
								</tr>
							</table>
						</div>
					</form>
				</div>
			<?php
				}elseif (@$_POST['step'] == 4) { ?>
				<div>
					<form action="found.php" method="POST">
						<input type='hidden' name='step' value='5'></input>
					<?php
						echo "<input type='hidden' name='pic' value='".$_POST['pic']."'></input>";
						echo "<input type='hidden' name='get_time' value='".$_POST['get_time']."'></input>";
						echo "<input type='hidden' name='category' value='".$_POST['category']."'></input>";
						echo "<input type='hidden' name='location' value='".$_POST['location']."'></input>";
						echo "<input type='hidden' name='dicpt' value='".$_POST['dicpt']."'></input>";
					?>
					<center><p><b><font face="微軟正黑體" size="4">Step.4 是否提供Facebook聯絡方式?</font></b></p></center>
						<table align="center">
						<?php
							$get_sql = "select count(*) from OAUTH where U_ID='".$u_id."'";
							$res = mysqli_query($link, $get_sql);
							$set = mysqli_fetch_array($res, MYSQLI_ASSOC);
							if($set['count(*)']==0){
						?>
								<center><p><font face="微軟正黑體" size=3>您似乎沒有綁定Facebook帳號</p></font>
								<p><font face="微軟正黑體" size=3>若您願意提供你的Facebook個人頁網址<br/>
								請在下方欄位填入，若無意則留空白</p></font></center>
								<center><input type="text" name = "fb_url" placeholder="Ex. https://www.facebook.com/profile.php?id=100005374846052"></input></center>
						<?php
							}else{
								$get_sql = "select * from OAUTH where U_ID='".$u_id."'";
								$res = mysqli_query($link, $get_sql) or die($get_sql);
								$set = mysqli_fetch_array($res, MYSQLI_ASSOC) or die("!!");
						?>
							<tr>
								<td colspan="2">
									<?php
										$fid = $set['F_ID'];
										echo "<img src='https://graph.facebook.com/".$fid."/picture?type=large'></img>";
									?>
								</td>
							</tr>
							<tr>
								<td><input type="radio" name="fb" value="1">是</input></td>
								<td><input type="radio" name="fb" value="0">否</input></td>
							</tr>
						
						<?php } ?>
							<tr>
								<td><input type ="button" onclick="history.back()" value="回到上一頁"></input></td>
								<td><input type="submit" value="下一步"></input></td>
							</tr>
						</table>
					</form>
				</div>
				
			<?php 
			
						}elseif (@$_POST['step'] == 5) { 
							$pic = $_POST['pic'];
							$time = $_POST['get_time'];
							$cat = $_POST['category'];
							$loc = $_POST['location'];
							$dicpt = $_POST['dicpt'];
                           
							if (isset($_POST['fb_url'])){
								if($f_url==""){
									$new_sql = "insert into item (I_ID, CAT_ID, LOC_ID, P_NO, TIME, U_ID, I_DICPT, facebook, fb_url, I_STAT) VALUES (NULL, '".$cat."', '".$loc."', '".$pic."', '".$time."', '".$u_id."', '".$dicpt."', '0', NULL, '0')";
								}else{
									$new_sql = "insert into item (I_ID, CAT_ID, LOC_ID, P_NO, TIME, U_ID, I_DICPT, facebook, fb_url, I_STAT) VALUES (NULL, '".$cat."', '".$loc."', '".$pic."', '".$time."', '".$u_id."', '".$dicpt."', '1', '".$_POST['fb_url']."', '0')";
								}

							}elseif (isset($_POST['fb'])) {
								$new_sql = "insert into item (I_ID, CAT_ID, LOC_ID, P_NO, TIME, U_ID, I_DICPT, facebook, fb_url, I_STAT) VALUES (NULL, '".$cat."', '".$loc."', '".$pic."', '".$time."', '".$u_id."', '".$dicpt."', '1', NULL, '0')";
							}
							mysqli_query($link, $new_sql);
				
			?>		
				<div>
				<font face="微軟正黑體" size=4>
					<center><p>物品資料新增成功</p>
					<a href="index.php">按此回到首頁</a></center></font>
				</div>
			<?php }else{ 
					if (isset($_GET['del'])){
						$get_pic_info = "select * from pic where P_NO = '".$_GET['del']."'";
						$pic_info = mysqli_query($link, $get_pic_info);
						$pic = mysqli_fetch_array($pic_info, MYSQLI_ASSOC);
						unlink($pic['P_N_LOC']);
						unlink($pic['P_LOC']);
						$del_pic_sql = "delete from pic where P_NO = '".$pic['P_NO']."'";
						mysqli_query($link, $del_pic_sql);
						header("Location found.php");
					}
			?>
				<form action="found.php" method="POST" enctype="multipart/form-data">
					<center><div>
						<p><font face="微軟正黑體" size="4"><b>Step.1 上傳照片</b></font></p>
						<input type="hidden" name="step" value="2" ></input>
						<input type="file" name="pic" id="file" accept="image/jpeg"style="width:170px;height:23px;border:2px #4CAF50 solid;">
						<p>

						<input type="submit" value="下一步"style="font-size:15px;" ></input></p>
						
					</div></center>
				</form>
			<?php }	?>
	</div>
</body>
</html>