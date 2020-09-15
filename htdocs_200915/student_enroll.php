<?php

require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';
require $_SERVER["DOCUMENT_ROOT"].'/scripts/cookies.php';
require $_SERVER["DOCUMENT_ROOT"].'/scripts/push.php';

$connect = DBConnect();

if(IsCookieSet() && CookieLogin($connect))
{
    $username = $_COOKIE['UserName'];
}
else
{
  error(2);
  exit;
}

$time = $_GET['time'];
$data = mysqli_query($connect, "SELECT students, nop, studentnum FROM mileagenotice where noticedate='".$time."';") or die(mysqli_error($connect));
$studentrow = mysqli_fetch_array($data);
if(($studentrow[1]-$studentrow[2])<=0){
    echo "
    <script> 
        alert('선착순에 실패했습니다.'); document.location.href='student-mileage.php?sm=all&pom=all&searchbox=';
    </script>
    ";
    exit;
}
$studentresult = $studentrow[0];
if($studentresult==""){
    $studentresult = $username;
}
else{
    $studentresult = $studentrow[0].", ".$username;
}
$num = $_GET['num']+1;
if(($studentrow[1]-$studentrow[2])<=0){
    $updateisend = mysqli_query($connect, "UPDATE mileagenotice SET isend=1 WHERE noticedate='".$time."';") or die(mysqli_error($connect));
}
$updateisend = mysqli_query($connect, "UPDATE mileagenotice SET students='".$studentresult."' WHERE noticedate='".$time."';") or die(mysqli_error($connect));
$updateisend2 = mysqli_query($connect, "UPDATE mileagenotice SET studentnum=".$num." WHERE noticedate='".$time."';") or die(mysqli_error($connect));

echo "
<script> 
    alert('성공적으로 신청이 완료되었습니다. 담당 선생님께서 따로 호출하기 전까지 대기하세요.'); document.location.href='student-mileage.php?sm=all&pom=all&searchbox=';
</script>
";

?>