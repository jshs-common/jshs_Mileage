<?php
  require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';
  require $_SERVER["DOCUMENT_ROOT"].'/scripts/cookies.php';
  date_default_timezone_set('Asia/Seoul');
  //require $_SERVER["DOCUMENT_ROOT"].'/scripts/push.php';

  $connect = DBConnect();
  if($_COOKIE['UserSID'] == 319) {
    if(IsCookieSet() && CookieLogin($connect)) {
      $SID = $_COOKIE['UserSID'];
      $username = $_COOKIE['UserName'];
      $SubjNum = $_POST['SubjNum'];
      $borrow = $_POST['borrow'];
    }
    else{
        error(2);
        exit;
    }
  }
  else {
    error(2);
    exit;
  }
  echo $borrow;
  if($borrow == 2) {
  	$result = $connect->query("select due from apply_laptop where SubjNum=".$SubjNum.";");
  	$row = mysqli_fetch_array($result);
    $due= $row[0] * -1;

    $connect->query("update laptop_list set borrow=0 where SubjNum=".$SubjNum.";");
  	$result = $connect->query("select SID from apply_laptop where SubjNum=".$SubjNum.";");
  	$row = mysqli_fetch_array($result);
    $connect->query("update laptop_return set SubjNum = ".$SubjNum." where SID =".$row[0].";");
    $connect->query("update laptop_return set RecentReturn = 7 where SID =".$row[0].";");

    $result = $connect->query("select cancel from laptop_return where SID=".$row[0].";");
  	$row = mysqli_fetch_array($result);
    $cancel = $row[0];
    if($due > 0 && $cancel==0){
      $result = $connect->query("select due from laptop_return where SubjNum=".$SubjNum.";");
    	$row = mysqli_fetch_array($result);
      $due_return = (int)$due + (int)$row[0];

    	$result = $connect->query("select SID from apply_laptop where SubjNum=".$SubjNum.";");
    	$row = mysqli_fetch_array($result);
      $connect->query("update laptop_return set due =".$due_return." where SID =".$row[0].";");
      if($due_return >= 5){
        $ban= (2-(int)($due_return/5)%2) * 14;
        //$BorDay = date("Y-m-d");
        //$weekday = date("N", strtotime($BorDay));
        $plus = "+".$ban." days";
        $BanDay = (string)date("Y-m-d", strtotime($plus));
        $connect->query("update laptop_return set BanDay ='".$BanDay."' where SID =".$row[0].";");
        $connect->query("update laptop_return set ban =".$ban." where SID =".$row[0].";");
        $connect->query("update laptop_return set cancel = 1 where SID =".$row[0].";");
      }
    }

    $result = $connect->query("select SID from apply_laptop where SubjNum=".$SubjNum.";");
  	$row = mysqli_fetch_array($result);
  	$connect->query("delete from apply_laptop where SID =".$row[0].";");
  	$connect->query("delete from apply_laptop_check where SID =".$row[0].";");

    $message = "노트북이 반납되었습니다.";
  }
  else {
    $message = "error!";
  }
  echo "<script>alert(".$message.");</script>";
  //SendPush($connect, $SID, $message);
  echo "<meta http-equiv='refresh' content='0;url=teacher-laptop-return.php?subject=all&num=all'>";
?>
