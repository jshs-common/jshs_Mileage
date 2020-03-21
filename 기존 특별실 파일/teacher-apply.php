<?php
  require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';
  require $_SERVER["DOCUMENT_ROOT"].'/scripts/cookies.php';
  require $_SERVER["DOCUMENT_ROOT"].'/scripts/push.php';

  $connect = DBConnect();
  if(IsCookieSet() && CookieLogin($connect))
  {
      $SID = $_COOKIE['UserSID'];
  }
  else
  {
    error(2);
    exit;
  }
  $connect->query("update apply set IsApproved=".$_GET['IsApproved']." where ApplyID='".$_GET['ApplyID']."' and TeacherSID = ".$SID.";");

  switch($_GET['IsApproved'])
  {
  case 1:
    $message = "특별실 신청이 승인되었습니다";
    break;
  case -1:
    $message = "특별실 신청이 거부되었습니다";
    break;
  }
  $query = "select SID from applystudents where ApplyID = ".$_GET['ApplyID'];
  $result = $connect->query($query);
  while($studentSID = $result->fetch_array()['SID'])
  {
    SendPush($connect, $studentSID, $message);
  }

  echo "<meta http-equiv='refresh' content='0;url=teacher.php'>";
?>