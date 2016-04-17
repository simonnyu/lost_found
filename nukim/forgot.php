<?php
	$cooke_name = "c_user";
	if(@$_COOKIE[$cooke_name]!=null){
		header("Location: index.php");
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>NUKIM - 失物招領</title>
	<style type="text/css">
	#container{
		width: 985px;
		margin:0px auto;
	}
	#login_left{
		width: 60%;
		float: left;
	}
	#login_right{
		width: 40%;
		float: left;
	}
	#login{
		padding: 8px 150px;
	}
	.box{
		padding: 4px 4px;
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
	body{
		background-color: #dfe3ee;
	}
	input[type=text],input[type=password] {
	    width: 100%;
	    padding: 12px 20px;
	    margin: 8px 0;
	    box-sizing: border-box;
	    border: 3px solid #ccc;
	    -webkit-transition: 0.5s;
	    transition: 0.5s;
	    outline: none;
	}
	input[type=text],input[type=password]:focus {
    	border: 3px solid #555;
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
	<?php if($_GET['f']==1){?>
		function check(){
			if ((document.f.elements[2].value)!=(document.f.elements[3]).value){
				alert("新密碼不一致!!");
				return false;
			}//確認密碼沒有打錯
			return true;
		}
	<?php }	?>
	</script>
</head>
<body>
	<div id="container">
		<div><p><img src="assect/img/pdc_title.jpg"></img></p></div>
		<div>
			<center>
				<p><font size="8"><b><center><font face="微軟正黑體">忘記密碼</font></center></font></p></b>
				<?php
					if($_POST['uid']!="" && $_POST['step']!=2){
						require_once("db_config.php");
						$check_sql = "select * from user where U_ID = '".$_POST['uid']."'";
						$check = mysqli_query($link, $check_sql);
						$result = mysqli_fetch_array($check, MYSQLI_ASSOC);
						if(strcmp($_POST['umail'], $result['U_EMAIL'])==0){
							$cur_time = date('ymdHis');
							$token = md5($cur_time);
							$forgot_isset_sql = "select count(*) from forgot where U_ID = '".$result['U_ID']."'";
							$res = mysqli_query($link, $forgot_isset_sql);
							$set = mysqli_fetch_array($res, MYSQLI_ASSOC);
							if($set['count(*)']==0){
								$set_forgot = "insert into forgot (U_ID, U_EMAIL, TOKEN) values ('".$result['U_ID']."', '".$result["U_EMAIL"]."', '".$token."')";
							}else{
								$set_forgot = "update forgot set TOKEN = '".$token."' where U_ID = '".$result['U_ID']."'";
							}
							mysqli_query($link, $set_forgot);
							$acc_url = "http://140.127.218.250:49012/nukim/forgot.php?f=1&token=".$token;
							require_once('../PHPMailer/PHPMailerAutoload.php');
						    $C_name=$result['U_NAME'];
						    $C_email=$result['U_EMAIL'];
						    $mail= new PHPMailer();                          //建立新物件
						    $mail->IsSMTP();                                    //設定使用SMTP方式寄信
						    $mail->Host = "smtp.nuk.edu.tw";             //Gamil的SMTP主機
						    $mail->Port = 25;                                 //Gamil的SMTP主機的埠號(Gmail為465)。
						    $mail->CharSet = "utf-8";                       //郵件編碼
						    $mail->Username = ""; //Gamil帳號
						    $mail->Password = "";                 //Gmail密碼
						    $mail->From = "";        //寄件者信箱
						    $mail->FromName = "NUKIM_LostnFound";                  //寄件者姓名
						    $mail->Subject ="NUKIM失誤找領系統-忘記密碼"; //郵件標題
						    $mail->Body = $C_name."您好,<br/>這是NUKIM失物招領系統自動發送的郵件<br/>請點擊下列網址前往下一步<br/><a href='".$acc_url."'>".$acc_url."</a>"; //郵件內容
						    $mail->IsHTML(true);                             //郵件內容為html
						    $mail->AddAddress("$C_email");            //收件者郵件及名稱
						    if(!$mail->Send()){
						        echo "Error: " . $mail->ErrorInfo;
						    }else{
						    	echo "<p><b>系統已將重置密碼資訊送至您的EMAIL</b></p>";
						    	echo "<p>請至您的電子郵件信箱收件</p>";
						    	echo "<p>三秒後系統將自動導回登入畫面</p>";
								header("Refresh: 3; url=login.php");
						    }
						}else{
							echo "<p><b>您輸入的帳號與EMAIL不符合!!</b></p>";
							echo "<p>三秒後系統將自動導回登入畫面</p>";
							header("Refresh: 3; url=login.php");
						}
					}elseif ($_GET['f']==1) {
						require_once("db_config.php");
						$token = $_GET['token'];
						$check_sql = "select * from forgot where TOKEN = '".$token."'";
						$check = mysqli_query($link, $check_sql);
						if (mysqli_num_rows($check)==0){
							echo "<p><b>資料有誤!!</b></p>";
							echo "<p>三秒後系統將自動導回登入畫面</p>";
							header("Refresh: 3; url=login.php");
						}else{
							$result = mysqli_fetch_array($check, MYSQLI_ASSOC);
						?>
						<form action="forgot.php" method="POST" name="f" onsubmit="return check();">
							<input type="hidden" name="step" value="2"></input>
							<?php echo "<input type='hidden' name='uid' value='".$result['U_ID']."'></input>"?>
							<table>
								<tr>
									<td class="col_1">帳號</td>
									<td><?php echo $result['U_ID']; ?></td>
								</tr>
								<tr>
									<td class="col_1">新密碼</td>
									<td class="col_2"><input type="password" name="upwd1"></input></td>
								</tr>
								<tr>
									<td class="col_1">再次輸入新密碼</td>
									<td class="col_2"><input type="password" name="upwd2"></input></td>
								</tr>
								<tr>
									<td></td>
									<td><input type="submit" value="送出"></input></td>
								</tr>
							</table>
						</form>
						<?php
						}
					}elseif ($_POST['step']==2) {
						require_once("db_config.php");
						$pwd = md5($_POST['upwd1']);
						$update_sql = "update AUTH set U_PWD = '".$pwd."' where U_ID = '".$_POST['uid']."'";
						mysqli_query($link, $update_sql) or die("!@");
						$del_forgot_sql = "delete from forgot where U_ID = '".$_POST['uid']."'";
						mysqli_query($link, $del_forgot_sql);
						echo "<p>密碼修改成功!!</p>";
						echo "<p>三秒後系統將自動導回登入畫面</p>";
						header("Refresh: 3; url=login.php");
					}else{
				?>
				<form action="forgot.php" method="POST">
					<table>
						<tr>
							<td>帳號</td>
							<td><input type="text" name="uid" placeholder="Ex.A1033328" required></input></td>
						</tr>
						<tr>
							<td>E-Mail</td>
							<td><input type="text" name="umail" placeholder="請輸入申請時填入的E-MAIL" required></input></td>
						</tr>
						<tr>
							<td></td>
							<td><input type="submit" value="送出"></input>  <input type ="button" onclick="history.back()" value="回到上一頁"></input></td>
						</tr>
					</table>
				</form>
				<?php } ?>
			</center>
		</div>
	</div>
</body>
</html>