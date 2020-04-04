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

$connect = DBConnect();

if(!CookieLogin($connect) || !isset(
		$_POST['starthour'], $_POST['startminute'], $_POST['endhour'], $_POST['endminute'],
		$_POST['location'], $_POST['purpose'], $_POST['student'], $_POST['TeacherSID']
	))
{
		error(2);
		exit;
}
$starttime = $_POST['starthour'].$_POST['startminute'];
$endtime = $_POST['endhour'].$_POST['endminute'];
$query = "insert into apply(TeacherSID, Location, Purpose, StartTime, Endtime)";
$query .= "VALUE(".$_POST['TeacherSID'].", '".$_POST['location']."', '".$_POST['purpose']."', ".$starttime.", ".$endtime.");";
$connect->query($query);
$applyID = $connect->query("select LAST_INSERT_ID();")->fetch_array()['LAST_INSERT_ID()'];

$students=explode(',',$_POST['student']);
for($i = 0; $i < count($students); $i++)
{
	$studentsSID = $connect->query("select SID from user where UserName = '".$students[$i]."';")->fetch_array()['SID'];
	if(!isset($studentsSID))
	{
		$connect->query("delete from apply where ApplyID = ".$applyID);
		echo "<script>alert('이름을 제대로 적었는지 확인해 주세요');history.go(-2);</script>";
		exit;
	}
	if(isset($connect->query("select ApplyID from applystudents where SID = ".$studentsSID)->fetch_array()['ApplyID']))continue; // 이미 신청되있는 사람이면 스킵
	$query = "insert into applystudents(SID, ApplyID) VALUE(".$studentsSID.", ".$applyID.");";
	$connect->query($query);
}

$TargetSID = $_POST['TeacherSID'];
$applyStudent = $connect->query("select UserName from user where SID = ".$_COOKIE['UserSID'])->fetch_array()['UserName'];	
$message = $applyStudent;
if(count($students) > 1)
{
	$message .= ' 외 ' . count($students) . '명의 학생들이 ';
}
else
{
	$message .= ' 학생이 ';
}
$message .= $_POST['location'] . '(으)로 특별실을 신청했습니다. 확인해주세요.';
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
				<div class="btn btn-success" style="margin-bottom: 30px;" onclick="document.location.href='student-waiting.php';" >확인하기</div>
			</div>
		</div>
	</div>
    
	<?php require $_SERVER["DOCUMENT_ROOT"].'/scripts/ad.php'; EchoAd(); ?>
</body>
</html>



