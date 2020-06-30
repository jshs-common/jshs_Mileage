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
echo $time;
$updateisend = mysqli_query($connect, "update mileagenotice set isend=".$_GET['isend']." where noticedate='".$time."';") or die(mysqli_error($connect));

$studentsql = mysqli_query($connect, "select students from mileagenotice where noticedate='".$time."'");
$studentrow = mysqli_fetch_array($studentsql);

switch($_GET['isend'])
{
case 1:
  $message = "공고가 마감되었습니다.";
  echo "
  <script> 
      alert('마감되었습니다'); document.location.href='noticecheck.php';
  </script>
  ";
  exit;
case 2:
    $message = "상점 지급이 완료되었습니다.";
    break;
}

$studentarray = explode( ', ', $studentrow[0] );
$cnt = count($studentarray);

date_default_timezone_set("Asia/Seoul");
$date = date("Y-m-d H:i:s");
$usernamesplited = explode("(", $username); 

for($i = 0 ; $i < $cnt ; $i++){
    if($studentarray[$i] == ""){
        continue;
    }
    $data = mysqli_query($connect, "SELECT SID FROM user WHERE ID ='".$studentarray[$i]."'") or die(mysqli_error($connect));
    $noticedata = mysqli_query($connect, "SELECT * FROM mileagenotice WHERE noticedate='".$time."'") or die(mysqli_error($connect));
    $row = mysqli_fetch_array($data);
    $noticerow = mysqli_fetch_array($noticedata);
    $insert = mysqli_query($connect, "INSERT INTO mileagehistory (SID, Checkdate, period, Isminus, type, description, plus, minus, teachername) VALUES (".$row[0].", '".$date."', 1, 0, '기타', '".$noticerow['title']."', ".$noticerow['score'].", 0, '".$usernamesplited[0]."')") or die(mysqli_error($connect));

    $road = mysqli_query($connect, "SELECT plus FROM totalmileage WHERE SID =".$row[0]) or die(mysqli_error($connect));
    $row2 = mysqli_fetch_array($road);
    $totalpoint = $row2[0]+$noticerow['score'];
    $update = mysqli_query($connect, "UPDATE totalmileage SET plus = ".$totalpoint." WHERE SID =".$row[0]) or die(mysqli_error($connect));
}

echo "
<script> 
    alert('성공적으로 상점 부여가 완료되었습니다. 상점 부여 후에도 상점 수치가 바뀌지 않는다면 3학년 김채린 학생을 불러주세요.'); document.location.href='noticecheck.php';
</script>
";


?>