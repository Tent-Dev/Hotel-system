<?php
#!/usr/bin/env php
include("class_db.php");

$mysql = new Main_db;
$mysql->Connect_db(); //เชื่อมต่อdb
$mysql->SetCharacter();

$sql = "DELETE FROM h_transaction WHERE h_trans_session < NOW() - INTERVAL 5 MINUTE and h_trans_codesession != '' ";
$mysql->Delete_db($sql);

//command for terminal MacOs contact autodelete.php
//crontab -e
//press i button
//*/3 * * * * /Applications/AMPPS/php-5.6/bin/php /Applications/AMPPS/www/myport/system/autodeletebooking.php > /Applications/AMPPS/www/myport/system/logfile.log 2>&1
//press ESC
//press ZZ button
//check by crontab -l
?>

