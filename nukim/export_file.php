<?php
	$cooke_name = "c_user";
	if($_COOKIE[$cooke_name]==null){
		header("Location: login.php");
	}//沒有登入狀態則導向登入畫面
	require_once("db_config.php");
	$file_name = "NUKIM-".date('ymdHis');
	$get_item_sql = "SELECT I_ID, TIME, CAT_NAME, LOC_NAME, I_DICPT, I_STAT FROM item NATURAL JOIN location NATURAL JOIN cat";
	if (isset($_GET['set'])) {
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
		$result_query = mysqli_query($link, $get_item_sql);
		if(mysqli_num_rows($result_query)==0){
			echo "<p align='center' color='red'><b>查無資料!</b></p>";
			echo "<center>請重新查詢<br/>系統將於3秒後轉跳至統計資料頁面<br/></center>";
			header("Refresh: 3; url=static.php");
		}else{
			if($_GET['ftype']=="csv"){
				// output headers so that the file is downloaded rather than displayed
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename='.$file_name.'.csv');

				// create a file pointer connected to the output stream
				$output = fopen('php://output', 'w');

				// output the column headings
				fputcsv($output, array('編號', '拾獲時間', '物品類別', '拾獲地點', '物品描述', '物品狀態'));
				$rows = mysqli_query($link, $get_item_sql);
				while ($row = mysqli_fetch_array($rows, MYSQLI_ASSOC)) fputcsv($output, $row);
			}elseif($_GET['ftype']=="pdf"){
				
				$content = "<!DOCTYPE html><html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
				$content .= "<style type='text/css'>table{border-collapse: collapse;}td,th{padding: 12px 20px;margin: 8px 0;box-sizing: border-box;-webkit-transition: 0.5s;transition: 0.5s;outline: none;}</style></head>";
				$content .= "<body><table><tr><th>編號</th><th>拾獲時間</th><th>物品類別</th><th>拾獲地點</th><th>物品描述</th><th>物品狀態</th></tr>";
				while ($result=mysqli_fetch_array($result_query,MYSQLI_ASSOC)) {
					if($result['I_STAT']==0){
						$content .= "<tr><td>".$result['I_ID']."</td><td>".$result['TIME']."</td><td>".$result['CAT_NAME']."</td><td>".$result['LOC_NAME']."</td><td>".$result['I_DICPT']."</td><td>尚未認領</td></tr>";
					}elseif($result['I_STAT']==1){
						$content .= "<tr><td>".$result['I_ID']."</td><td>".$result['TIME']."</td><td>".$result['CAT_NAME']."</td><td>".$result['LOC_NAME']."</td><td>".$result['I_DICPT']."</td><td>認領中</td></tr>";
					}elseif($result['I_STAT']==2){
						$content .= "<tr><td>".$result['I_ID']."</td><td>".$result['TIME']."</td><td>".$result['CAT_NAME']."</td><td>".$result['LOC_NAME']."</td><td>".$result['I_DICPT']."</td><td>已被認領</td></tr>";
					}
				}
				$content .= "<p>匯出時間".date('Y/m/d H:i:s')."</p><p>匯出者ID:".$_COOKIE[$cooke_name]."</p>";
				require_once('../tcpdf/examples/tcpdf_include.php');

				// create new PDF document
				$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

				// set document information
				$pdf->SetCreator(PDF_CREATOR);
				$pdf->SetAuthor('NUKIM失物招領');
				$pdf->SetTitle($file_name);
				$pdf->SetSubject('NUKIM失物招領');
				// set default monospaced font
				$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

				// set margins
				$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
				$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
				$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

				// set auto page breaks
				$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

				// set image scale factor
				$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

				// set some language-dependent strings (optional)
				if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
					require_once(dirname(__FILE__).'/lang/eng.php');
					$pdf->setLanguageArray($l);
				}

				// ---------------------------------------------------------

				// set font
				$pdf->SetFont('msungstdlight', '', 12);

				// add a page
				$pdf->AddPage();

				// output the HTML content
				$pdf->writeHTML($content, true, 0, true, 0);

				// reset pointer to the last page
				$pdf->lastPage();

				// ---------------------------------------------------------

				//Close and output PDF document
				$pdf->Output($file_name.'.pdf', 'D');
			}
		}
	}elseif(isset($_POST['rank'])){
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
		$content = "<!DOCTYPE html><html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head>";
		$content .= "<body><p>遺失物品類別排行</p><table><tr><th>名次</th><th>物品類別</th><th>件數</th></tr>";
		$rank = 1;
		foreach ($arr_cat as $key => $value) {
			$content .= "<tr><td>".$rank."</td><td>".$key."</td><td>".$value."</td></tr>";
			$rank += 1;
		}
		$content .= "</table>";
		$content .= "<p>遺失地點排行</p><table><tr><th>名次</th><th>地點</th><th>件數</th></tr>";
		$rank = 1;
		foreach ($arr_loc as $key => $value) {
			$content .= "<tr><td>".$rank."</td><td>".$key."</td><td>".$value."</td></tr>";
			$rank += 1;
		}
		$content .= "</table>";
		$content .= "<p>匯出時間".date('Y/m/d H:i:s')."</p><p>匯出者ID:".$_COOKIE[$cooke_name]."</p></body></html>";
		require_once('../tcpdf/examples/tcpdf_include.php');

				// create new PDF document
				$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

				// set document information
				$pdf->SetCreator(PDF_CREATOR);
				$pdf->SetAuthor('NUKIM失物招領');
				$pdf->SetTitle($file_name);
				$pdf->SetSubject('NUKIM失物招領');
				// set default monospaced font
				$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

				// set margins
				$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
				$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
				$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

				// set auto page breaks
				$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

				// set image scale factor
				$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

				// set some language-dependent strings (optional)
				if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
					require_once(dirname(__FILE__).'/lang/eng.php');
					$pdf->setLanguageArray($l);
				}

				// ---------------------------------------------------------

				// set font
				$pdf->SetFont('msungstdlight', '', 12);

				// add a page
				$pdf->AddPage();

				// output the HTML content
				$pdf->writeHTML($content, true, 0, true, 0);

				// reset pointer to the last page
				$pdf->lastPage();

				// ---------------------------------------------------------

				//Close and output PDF document
				$pdf->Output($file_name.'.pdf', 'D');
	}else{
		header("Location: static.php");
	}
?>