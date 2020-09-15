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
  $connect->query("update apply_laptop set IsApproved=".$_GET['IsApproved']." where ApplyID_laptop='".$_GET['ApplyID_laptop']."' and TeacherSID = ".$SID.";");
  $SubjNum = $connect->query("select SubjNum from apply_laptop where ApplyID_laptop = ".$_GET['ApplyID_laptop'].";")->fetch_array();
  $SubjNum = $SubjNum['SubjNum'];
  switch($_GET['IsApproved'])
  {
  case 1:
    $connect->query("update laptop_list set borrow=2 where subjnum=".$SubjNum.";");
    $message = "특별실 신청이 승인되었습니다";
    break;
  case -1:
    $connect->query("update laptop_list set borrow=-0 where subjnum=".$SubjNum.";");
    $message = "특별실 신청이 거부되었습니다";
    break;
  }
  $query = "select SID from apply_laptop_check where ApplyID = ".$_GET['ApplyID_laptop'];
  $result = $connect->query($query);
  while($SID_laptop = $result->fetch_array()['SID'])
  {
    SendPush($connect, $SID_laptop, $message);
  }

  echo "<meta http-equiv='refresh' content='0;url=teacher-laptop.php'>";
?>