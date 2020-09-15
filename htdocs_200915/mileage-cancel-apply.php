<?php
require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';
require $_SERVER["DOCUMENT_ROOT"].'/scripts/cookies.php';
require $_SERVER["DOCUMENT_ROOT"].'/scripts/push.php';

$connect = DBConnect();
if($_COOKIE['UserSID'] >= 300){
    if(IsCookieSet() && CookieLogin($connect)){
      $SID = $_COOKIE['UserSID'];
      $username = $_COOKIE['UserName'];
    }
    else{
        error(2);
        exit;
    }
}
else
{
  error(2);
  exit;
}
$time = $_GET['time'];
$studentsid = $_GET['sid'];
$studentsql = mysqli_query($connect, "select * from mileagehistory where Checkdate='".$time."' AND SID = ".$studentsid);
$data = mysqli_fetch_array($studentsql);  

switch($data[3])
{
case 1:
    $road = mysqli_query($connect, "SELECT minus FROM totalmileage WHERE SID =".$data[0]) or die(mysqli_error($connect));
    $row2 = mysqli_fetch_array($road);
    $totalpoint = $row2[0]-$data[7];
    $update = mysqli_query($connect, "UPDATE totalmileage SET minus = ".$totalpoint." WHERE SID =".$data[0]) or die(mysqli_error($connect));
    $update = mysqli_query($connect, "DELETE FROM mileagehistory WHERE Checkdate='".$time."' AND SID = ".$studentsid) or die(mysqli_error($connect));

    echo "
    <script> 
        alert('성공적으로 취소가 완료되었습니다.'); document.location.href='mileage-cancel.php?sm=all&pom=all&searchbox=';
    </script>
    ";
    
  exit;

case 0:
    $road = mysqli_query($connect, "SELECT plus FROM totalmileage WHERE SID =".$data[0]) or die(mysqli_error($connect));
    $row2 = mysqli_fetch_array($road);
    $totalpoint = $row2[0]-$data[6];
    $update = mysqli_query($connect, "UPDATE totalmileage SET plus = ".$totalpoint." WHERE SID =".$data[0]) or die(mysqli_error($connect));
    $update = mysqli_query($connect, "DELETE FROM mileagehistory WHERE Checkdate='".$time."' AND SID = ".$studentsid) or die(mysqli_error($connect));

    echo "
  <script> 
      alert('성공적으로 취소가 완료되었습니다.'); document.location.href='mileage-cancel.php?sm=all&pom=all&searchbox=';
  </script>
  ";
    
  exit;
  
}



?>