<?php
	$cooke_name = "c_user";
	if($_COOKIE[$cooke_name]==null){
		header("Location: login.php");
	}//沒有登入狀態則導向登入畫面
	if(!isset($_GET['id'])){
		header("Location: index.php");
	}
	require_once("db_config.php");
	$u_id = $_COOKIE[$cooke_name];
	if($u_id == "guest"){
		header("Location: index.php");
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
			width: 40%;
			float: left;

		}
		#div_right{
			width: 60%;
			float: left;
		}
	}
	#login{
		padding: 8px 16px;
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
</head>
<body>
	<div id="container">
	<div><p><img src="assect/img/pdc_title.jpg" width="100%"></img></p></div>
		<?php
			$get_detail_sql = "SELECT * FROM item NATURAL JOIN location NATURAL JOIN cat NATURAL JOIN pic NATURAL JOIN user NATURAL JOIN OAUTH WHERE I_ID = '".$_GET['id']."'";
			$get_detail = mysqli_query($link, $get_detail_sql);
			$detail = mysqli_fetch_array($get_detail, MYSQLI_ASSOC);
			if($detail['I_STAT']==0){
				$est = date('Y-m-d');
				$update_info = "update item set I_STAT = '1' WHERE I_ID = '".$detail['I_ID']."'";
				$creat_lnf = "insert into history (I_ID, F_U_ID, L_U_ID, EST_TIME) values ('".$detail['I_ID']."', '".$detail['U_ID']."', '".$u_id."', '".$est."')";
				mysqli_query($link, $update_info);
				mysqli_query($link, $creat_lnf);
		?>
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
				echo "<tr>";
				echo "<td colspan='2'>系統已經先幫你通知拾獲者囉~聯絡他時記得要告訴他你是要認領東西的喔</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td><input type ='button' onclick='location.href=\"index.php\"' value='回到首頁'></input></td>";
				echo "<td><input type ='button' onclick='window.close();' value='關閉視窗'></input></td>";
				echo "</tr>";
				echo "</table>";
				echo "</center>";
			    require_once('../PHPMailer/PHPMailerAutoload.php');
			    $C_name=$detail['U_NAME'];
			    $C_email=$detail['U_EMAIL'];
			    $C_IID=$detail['I_ID'];
			    $url = "<a href='http://140.127.218.250:49012/nukim/my.php?mod_id=".$C_IID."'>".$C_IID."</a>";
			   
			    $mail= new PHPMailer();                          //建立新物件
			    $mail->IsSMTP();                                    //設定使用SMTP方式寄信
			    $mail->Host = "smtp.nuk.edu.tw";             //Gamil的SMTP主機
			    $mail->Port = 25;                                 //Gamil的SMTP主機的埠號(Gmail為465)。
			    $mail->CharSet = "utf-8";                       //郵件編碼
			    $mail->Username = ""; //Gamil帳號
			    $mail->Password = "";                 //Gmail密碼
			    $mail->From = "";        //寄件者信箱
			    $mail->FromName = "NUKIM_LostnFound";                  //寄件者姓名
			    $mail->Subject ="您於NUKIM失物招領系統登錄的物品，有人認領"; //郵件標題
			    $mail->Body = $C_name."您好,<br/>這是NUKIM失物招領系統自動發送的郵件<br/>您在本系統登入的物品--物品編號".$url."，已有人認領。"; //郵件內容
			    $mail->IsHTML(true);                             //郵件內容為html
			    $mail->AddAddress("$C_email");            //收件者郵件及名稱
			    if(!$mail->Send()){
			        echo "Error: " . $mail->ErrorInfo;
			    }
			}else{
				echo "這個物品已經被認領走了喔~";
			}
			?>
			</font>
			</center>
		</div>
	</div>
</body>