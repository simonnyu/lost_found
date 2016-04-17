此系統需額外安裝
1. PHPMailer
2. tcpdf

資料夾說明
nukim -> 失物招領系統
PHPMailer -> PHPMailer
tcpdf -> tcpdf

nuk.sql為資料庫設定

系統預設管理員
後台位置:nukim/admin/
帳號: test
密碼: 20160410

系統在 forgot.php 及 pickup.php 有發送EMAIL功能(HOST為高雄大學)
需自行設定Username,Password,From

建議使用Chrome瀏覽器


【/nukim/login.php】
NUKIM失物招領系統
此為登入頁面，預設帳號為您的學號(英文字母大寫)
密碼為生日(ex.20160410)
左邊可進行第三方連結
登入後進入/nukim/first.php(第一次登入)



【/nukim/first.php】
此頁可進行進階個人資料設定
送出進入/nukim/index.php



【/nukim/index.php】
此頁可進行您要的動作
左邊兩張大圖分別可登錄

「東西不見」
進入/nukim/lost.php

「撿到東西」
進入/nukim/found.php

右邊四個小圖左上、左下、右上、右下
分別為

「我的拾獲資料」
進入/nukim/my.php

「修改個人資料」
進入/nukim/profile.php

「遺失資料統計」
進入/nukim/static.php

「系統登出」
進入/nukim/login.php




【/nukim/lost.php】
此為設定您遺失物品資料
左側可設定物品遺失時間、地點、類別
送出查詢顯示於右側
右側顯示根據您設定的範圍所有登報遺失物品與詳細資料

「詳細資料」
進入/nukim/detail.php?id=x




【/nukim/detail.php?id=x】
此頁為物品詳細資料
點入「我要認領」則顯示拾獲者資料
系統會自動幫您聯絡




【/nukim/found.php】
此頁為您登入您拾獲遺失物品資料




【/nukim/my.php】
此頁為檢視您的拾獲資料頁面





【/nukim/profile.php】
此頁可進行您個人資料的修改





【/nukim/static.php】
此頁為所有遺失物品排行統整





【/nukim/admin/login.php】
此頁為後臺登入頁面
登入進入/nukim/admin/index.php





【/nukim/admin/index.php】
管理員可進行後台管理