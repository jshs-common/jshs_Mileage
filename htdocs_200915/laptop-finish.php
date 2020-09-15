<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>선생님 선택</title>
  
  <script src="lib/jquery/jquery-3.3.1.min.js"></script>
  <script src="lib/bootstrap/dist/js/bootstrap.min.js"></script>
  <link href="lib/bootstrap/dist/css/bootstrap.css" rel="stylesheet" />
  
  <link href="css/site.css" rel="stylesheet" />
  <link href="css/navbar.css" rel="stylesheet" /> 
  <link href="css/fonts.css" rel="stylesheet" />

  <link href="css/teacherselect.css" rel="stylesheet" />
</head>
<?php
require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';
require $_SERVER["DOCUMENT_ROOT"].'/scripts/cookies.php';
require $_SERVER["DOCUMENT_ROOT"].'/scripts/push.php';


$SID = $_COOKIE['UserSID'];
$student = $_COOKIE['UserName'];

$connect = DBConnect();

if(!CookieLogin($connect) || !isset(
	$_POST['laptop'], $_POST['lenddate'], $_POST['reason'], $_POST['addition']))
{
		error(2);
		exit;
}
$laptop = $_POST['laptop'];

//$lenddate = (int)$_POST['lenddate'];       최적화 작업할 때 lenddate 연결 끊어야됨

//---------시간 설정
date_default_timezone_set('Asia/Seoul');

$BorDay = date("Y-m-d");
$weekday = date("N", strtotime($BorDay));
$term = ($weekday < 7) ? (6-$weekday) : 6;
$plus = "+".$term." days";
$RetDay = (string)date("Y-m-d", strtotime($plus));
$date = date('Y-m-d');
//----------
$TargetSID = $_POST['TeacherSID'];

$query = "insert into apply_laptop(SID, TeacherSID, Purpose, SubjNum, Borday, RetDay, etc)";
$query .= "VALUE(".$SID.", ".$TargetSID.", '".$_POST['reason']."', ".$laptop.", '".$date."', '".$RetDay."', '".$_POST['addition']."');";


$connect->query($query);
$ADL = $connect->query("select LAST_INSERT_ID();")->fetch_array()['LAST_INSERT_ID()'];

$connect->query("update laptop_list set borrow=1 where subjnum=".$laptop.";");

if(!isset($connect->query("select SubjNum from apply_laptop where SID = ".$SID)->fetch_array()['SubjNum'])); // 이미 신청되있는 사람이면 스킵
$query = "insert into apply_laptop_check(SID, ApplyID) VALUE(".$SID.", ".$ADL.");";
$connect->query($query);

/*
for($i = 0; $i < count($students)-1; $i++)
{
	$studentsSID = $connect->query("select SID from user where UserName = '".$students[$i]."';")->fetch_array()['SID'];
	if(!isset($studentsSID))
	{
		$connect->query("delete from apply where ApplyID = ".$applyID);
		echo "<script>alert('이름을 제대로 적었는지 확인해 주세요');history.go(-2);</script>";
		exit;
	}
	//if(isset(
	$connect->query("select ApplyID from applystudents where SID = ".$studentsSID)->fetch_array()['ApplyID'];
	//))continue; // 이미 신청되있는 사람이면 스킵
	$query = "insert into applystudents(SID, ApplyID) VALUE(".$studentsSID.", ".$applyID.");";
	$connect->query($query);
}*/

$note = mysqli_query($connect, "SELECT subj,num FROM laptop_list WHERE SubjNum = '".$laptop."';");//$connect->query("select subj,num from laptop_list where SubjNum = '".$laptop."';")->fetch_array()['subject','num'];	
$notebook = mysqli_fetch_array($note);
$subject = $notebook['subj'];
switch ($subject) {
	case 'physics':
	  $subj_kr="물리";
	  break;
	case 'chemistry':
	  $subj_kr="화학";
	  break;
	case 'life':
	  $subj_kr="생명과학";
	  break;
	case 'earth':
	  $subj_kr="지구과학";
	  break;
	case 'math':
	  $subj_kr="수학";
	  break;
	default:
	  $subj_kr="정보";
	  break;
  }

$message = $student.'학생이 '.$subj_kr.' '.$notebook['num'].'번 노트북 대여를 신청했습니다. 확인해주세요.';

SendPush($connect, $TargetSID, $message);
?>
<body>
	<?php
	include $_SERVER["DOCUMENT_ROOT"].'/scripts/navbar.php';
	EchoNavBar(3);
	?>
	<div class="row">
		<div class="col-lg-3 col-md-3 col-xs-1"></div>
    	<div class="col-lg-6 col-md-6 col-xs-10 login-card">
			<div class="logo-capsule">
				<p>성공적으로<br>신청했습니다</p>
			</div>
			<div class="submit-button">
				<div class="btn btn-success" style="margin-bottom: 30px;" onclick="document.location.href='laptop-waiting.php';" >확인하기</div>
			</div>
		</div>
	</div>
    
	<?php require $_SERVER["DOCUMENT_ROOT"].'/scripts/ad.php'; EchoAd(); ?>
</body>
</html>



