<?php
require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';
$connect = DBConnect();

$teamkey = $_POST['teamkey']; // 아이디

$hashed = hash("sha256", $teamkey);
$query = "select * from castle where hashed='$hashed'";
$result = mysqli_query($connect, $query);
$row = mysqli_fetch_array($result);


if($hashed==$row['hashed']){ // id와 pw가 맞다면 login

   echo "<script>location.href='".$row['hashed'].".php';</script>";

}else{ // id 또는 pw가 다르다면 login 폼으로

   echo "<script>window.alert('teamkey 입력을 다시 확인해주세요 :) ');</script>"; // 잘못된 아이디 또는 비빌번호 입니다
   echo "<script>location.href='main.php';</script>";

}
?>
