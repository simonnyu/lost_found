<?php
	setcookie("s_user", "", time() - 3600); //登出->刪除cookie
	header("Location: index.php");
?>