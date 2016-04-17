<?php
  if(isset($_GET['step'])){
  	$acc_token = $_GET['access_token'];
  	$url = "https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=".$acc_token;
  	$user_info = file_get_contents($url);
    echo $user_info;
  	$info = json_decode($user_info);
    $g_id = $info->user_id;
    $mail = $info->email;
    if(!is_null($g_id)){
      require_once("db_config.php");
      $get_sql = "select * from OAUTH_G where G_ID='".$g_id."'";
      $res = mysqli_query($link, $get_sql);
      $set = mysqli_fetch_array($res, MYSQLI_ASSOC);
      if(mysqli_num_rows($res)==0){
        $g_login="Location: http://140.127.218.250:49012/nukim/first.php?oauth=1&gid=".$g_id."&gmail=".$mail;
        mysqli_close($link);
        header($g_login);
      }else{
        $UID = $set['U_ID'];
        echo $UID;
        $cooke_name = "c_user";
        setcookie($cooke_name, $UID);
        mysqli_close($link);
        header("Location: http://140.127.218.250:49012/nukim/index.php");
      }
    }
  }else{
?>
<script type="text/javascript">
  var str1 = window.location.hash.substr(1);
  var str_end = str1.indexOf("&");
  var acc_token = str1.slice(0,str_end);
  var query_str = "glogin.php?"+acc_token+"&step=2";
  document.location.href = query_str;
</script>
<?php } ?>