<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>특별실 신청서 확인</title>

  <script src="lib/jquery/jquery-3.3.1.min.js"></script>
  <script src="lib/bootstrap/dist/js/bootstrap.min.js"></script>
  <link href="lib/bootstrap/dist/css/bootstrap.css" rel="stylesheet" />

  <link href="css/site.css" rel="stylesheet" />
  <link href="css/navbar.css" rel="stylesheet" />
  <link href="css/fonts.css" rel="stylesheet" />

  <link href="css/teacher.css" rel="stylesheet" />
</head>

<?php
  require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';
  require $_SERVER["DOCUMENT_ROOT"].'/scripts/cookies.php';

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

  if(isset($_GET['attendance']))
  {
    switch($_GET['attendance'])
    {
    case 'on':
      $connect->query("update teachers set Attendance=1 where SID='".$SID."';");
      break;
    case 'off':
      $connect->query("update teachers set Attendance=0 where SID='".$SID."';");
      break;
    }
  }
 ?>

<body>
  <?php
  include $_SERVER["DOCUMENT_ROOT"].'/scripts/navbar.php';
  EchoNavBar(3);
  ?>
  <div class="container body-content">
    <div class="row">
      <div class="col-lg-2 col-md-2"></div>
      <div class="col-lg-8 col-md-8 card">
        <div class="logo-capsule">
          <p>신청 현황</p>
        </div>
        <div style="padding: 0px 20px 0px 20px; height: 50px;">
          <form action="" method="GET" id="setattend" style="position: relative;">
            <div class="material-switch pull-right">
              <input type="hidden" name="attendance" value="off">
              <input id="switch" type="checkbox" name="attendance" value="on" onclick="document.getElementById('setattend').submit();" />
              <label for="switch" class="label-primary"></label>
            </div>
            <div style="font-weight: bold; font-size: 16px; position: absolute; top: -1px; right: 55px;" id="attendtag">학교에 남으시나요?</div>
          </form>
        </div>

        <?php
        if($connect->query("select Attendance from teachers where SID='".$SID."';")->fetch_array()['Attendance'])
        {
          echo "
          <script>
            document.getElementById('switch').checked = true;
            document.getElementById('attendtag').innerText = '오늘 남아계시는군요!';
          </script>";
        }

        $count = 0;
        $result = $connect->query("select * from apply where TeacherSID='".$SID."';");
        while($row = $result->fetch_array())
        {
          $count++;
          $item = '';
          $title = $row['Purpose'];
          switch($row['IsApproved'])
          {
            case 0:
              $title .= ' - 승인 대기중';
              $item = '<div class="panel panel-info">';
              break;
            case 1:
              $title .= ' - 승인됨';
              $item = '<div class="panel panel-success">';
              break;
            case -1:
              $title .= ' - 승인 거부됨';
              $item = '<div class="panel panel-danger">';
              break;
          }
          $starttime = $row['StartTime'];
          $endtime = $row['EndTime'];
          $time = array((int)($row['StartTime'] / 100), $row['StartTime'] % 100 / 10, (int)($row['EndTime'] / 100), $row['EndTime'] % 100 / 10);
          $students = '';
          $tmp = $connect->query("select UserName from user where SID = any(select SID from applystudents where ApplyID = ".$row['ApplyID'].");");
          while($name = $tmp->fetch_array()['UserName'])
          {
            $students .= $name.', ';
          }

          $item .= '<div class="panel-heading">'.$title.'</div>';
          $item .= '<div class="panel-body"><ul>';
            $item .= '<li>신청 학생 : '.substr($students, 0, -2).'</li>';
            $item .= '<li>신청 시간 : '.$time[0].'시 '.$time[1].'0분 ~ '.$time[2].'시 '.$time[3].'0분'.'</li>';
            $item .= '<li>신청 장소 : '.$row['Location'].'</li>';
          $item .= '</ul></div>';

          $item .= '
          <div class="panel-btn-group">
            <div class="btn btn-success" onclick="location.href=\'teacher-apply.php?ApplyID='.$row['ApplyID'].'&IsApproved=1\';">승인</div>
            <div class="btn btn-danger" onclick="location.href=\'teacher-apply.php?ApplyID='.$row['ApplyID'].'&IsApproved=-1\';">거부</div>
          </div>
          ';
          $item .= '</div>';
          echo $item;
        }

        if($count == 0)
        {
          echo '
          <div class="alert alert-warning" role="alert">
            <span class="glyphicon glyphicon-alert" style="margin-right: 8px;"></span>
            특별실을 신청한 학생이 없습니다
            <span class="glyphicon glyphicon-alert" style="margin-left: 6px;"></span>
          </div>';
        }
        ?>
      </div>
    </div>
  </div>

  <?php require $_SERVER["DOCUMENT_ROOT"].'/scripts/ad.php'; EchoAd(); ?>
</body>
</html>
