<?php
require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';
require $_SERVER["DOCUMENT_ROOT"].'/scripts/push.php';

$connect = DBConnect();

$targets = ['문부영', '하승종', '이태우', '홍승재', '김지성'];

for($i = 0; $i < count($targets); $i++)
{
	$studentsSID = $connect->query("select SID from user where UserName = '".$targets[$i]."';")->fetch_array()['SID'];
    //SendPush($connect, $studentsSID, "한양대학교 확인 필요!");
}
?>

<script>
window.close();
</script>