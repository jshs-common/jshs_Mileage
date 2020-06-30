<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>노트북 신청 확인</title>

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
  if($_COOKIE['UserSID'] == 319||$_COOKIE['UserSID'] == 310){
    if(IsCookieSet() && CookieLogin($connect)){
      $SID = $_COOKIE['UserSID'];
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

  if(isset($_GET["attendance"]))
  {
    switch($_GET["attendance"])
    {
    case "on":
      $connect->query("update teachers set Attendance=1 where SID='".$SID."';");
      break;
    case "off":
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
          <p>노트북 신청 현황</p>
        </div>

        <?php

        $count = 0;
        $result = $connect->query("select * from apply_laptop where TeacherSID='".$SID."';");
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
          // $starttime = $row['StartTime'];
          // $endtime = $row['EndTime'];
          // $time = $time = array((int)($row['StartTime']), (int)($row['EndTime']));
          $students = '';
          // $tmp = $connect->query("select UserName from user where SID = any(select SID from applystudents where ApplyID = ".$row['ApplyID'].");");
          //이름 받아오기
          $tmp = $connect->query("select UserName from user where SID = any(select SID from apply_laptop where SID = ".$row['SID'].");");
          $students = $tmp->fetch_array()['UserName'];
          //노트북 과목 받아오기
          $laptop_name = '';
          $tmp2 = $connect->query("select subj from laptop_list where SubjNum = any(select SubjNum from apply_laptop where SubjNum = ".$row['SubjNum'].");");
          $laptop_name = $tmp2->fetch_array()['subj'];
          switch ($laptop_name) {
            case 'physics':
              $laptop_name = '물리';
              break;
            case 'life':
              $laptop_name ='생물';
              break;
            case 'chemistry':
              $laptop_name ='화학';
              break;
            case 'earth':
              $laptop_name = '지학';
              break;
            case 'information':
              $laptop_name = '정보';
              break;
            case 'math':
              $laptop_name = '수학';
              break;
          }
          //노트북 번호 받아오기
          $laptop_num = '';
          $tmp3 = $connect->query("select num from laptop_list where SubjNum = any(select SubjNum from apply_laptop where SubjNum = ".$row['SubjNum'].");");
          $laptop_num = $tmp3->fetch_array()['num'];

          $item .= '<div class="panel-heading">'.$title.'</div>';
          $item .= '<div class="panel-body"><ul>';
            $item .= '<li>노트북 번호 : '.$laptop_name.' '.$laptop_num.'</li>';
            $item .= '<li>신청 학생 : '.$students.'</li>';
            $item .= '<li>신청 기한 : '.$row['RetDay'].'일까지 대출을 원합니다. </li>';
            // $item .= '<li>신청 목적 : '.$row['Purpose'].'</li>';
            //$item .= '<li>기타 사유 : '.$row['Location'].'</li>';
          $item .= '</ul></div>';
          $item .= '
          <div class="panel-btn-group">
            <div class="btn btn-success" onclick="location.href=\'teacher-laptop-apply.php?ApplyID_laptop='.$row['ApplyID_laptop'].'&IsApproved=1\';">승인</div>
            <div class="btn btn-danger" onclick="location.href=\'teacher-laptop-apply.php?ApplyID_laptop='.$row['ApplyID_laptop'].'&IsApproved=-1\';">거부</div>
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
            노트북 대여를  신청한 학생이 없습니다
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
