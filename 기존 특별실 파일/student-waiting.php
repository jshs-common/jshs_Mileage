<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>특별실 신청 현황</title>

  <script src="lib/jquery/jquery-3.3.1.min.js"></script>
  <script src="lib/bootstrap/dist/js/bootstrap.min.js"></script>
  <link href="lib/bootstrap/dist/css/bootstrap.css" rel="stylesheet" />

  <link href="css/site.css" rel="stylesheet" />
  <link href="css/navbar.css" rel="stylesheet" />
  <link href="css/fonts.css" rel="stylesheet" />

  <link href="css/studentwaiting.css" rel="stylesheet" />
</head>
<?php
  require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';
  require $_SERVER["DOCUMENT_ROOT"].'/scripts/cookies.php';

  $connect = DBConnect();
  if(IsCookieSet() && CookieLogin($connect))
  {
      $SID = $_COOKIE['UserSID'];

      $row = $connect->query("select * from applystudents where SID=".$SID.";")->fetch_array();
      if(!isset($row))
      {
        echo "<meta http-equiv='refresh' content='0;url=student.php'>"; // 신청이 안되어 있을 시 student.php로 전송
        exit;
      }

      $ApplyID = $connect->query("select ApplyID from applystudents where SID = ".$SID.";")->fetch_array()['ApplyID'];
      $row = $connect->query("select * from apply where ApplyID = '".$ApplyID."';")->fetch_array();

      $isApproved = $row['IsApproved'];
      // 수락 여부 표시
      $purpose = $row['Purpose'];
      //목적 표시
      $time = array((int)($row['StartTime'] / 100), $row['StartTime'] % 100 / 10, (int)($row['EndTime'] / 100), $row['EndTime'] % 100 / 10);
      $time = $time[0].'시 '.$time[1].'0분 ~ '.$time[2].'시 '.$time[3].'0분';
      // 시간 표시
      $location = $row['Location'];
      // 장소 표시
      $teacher = $connect->query("select UserName from user where SID = ".$row['TeacherSID'])->fetch_array()['UserName'].' 선생님';
      // 신청 선생님 표시
      $students = ''; $num = 0;
      $result = $connect->query("select UserName from user where SID = ANY(select SID from applystudents where ApplyID = ".$ApplyID.");");
      while($row = $result->fetch_array())
      {
        $num++;
        $students .= $row['UserName'].', ';
      }
      $students = substr($students, 0, strlen($students) - 2).' - 총 '.$num.'인';
      // 신청 학생 이름 및 수 표시
  }
  else
  {
    error(2);
    exit;
  }
?>
<body class="htmlNoPages">
  <?php
  include $_SERVER["DOCUMENT_ROOT"].'/scripts/navbar.php';
  EchoNavBar(3);
  ?>

  <div class="container body-content">
    <div class="col-lg-3 col-md-2"></div>
    <div class="col-lg-6 col-md-8 card">
      <div class="logo-capsule">
        <p>신청 현황</p>
      </div>

      <ul class="application">
        <li><label>신청된 선생님 : <?php echo $teacher; ?></label></li>
        <li><label>신청 목적 : <?php echo $purpose; ?></label></li>
        <li><label>활동 장소 : <?php echo $location; ?></label></li>
        <li><label>신청 인원 : <?php echo $students; ?></label></li>
        <li><label>활동 시간 : <?php echo $time; ?></label></li>
      </ul>

      <div class="status-images">
        <div>
          <img src="img/Application.svg">
          <div>
            <label class="img-caption" style="margin-left: -3px;">신청 완료</label>
          </div>
        </div>
        <div style="margin-left: -8px;">
          <img src="img/Waiting.svg">
          <div>
            <label class="img-caption" style="margin-left: 1px;">승인 대기중</label>
          </div>
        </div>
        <div>
          <img src="img/Approved.svg">
          <div>
            <label class="img-caption" style="margin-left: 3px;">승인 완료</label>
          </div>
        </div>
      </div>
      <div class="progress">
        <?php
        $progbar = '<div class="progress-bar progress-bar-striped ';
        $progvalue = 0;
        switch($isApproved)
        {
          case 0:
            $progbar = $progbar.'progress-bar-info active" ';
            $progvalue = 50;
            break;
          case 1:
            $progbar = $progbar.'progress-bar-success" ';
            $progvalue = 100;
            break;
          case -1:
            $progbar = $progbar.'progress-bar-danger" ';
            $progvalue = 50;
            break;
        }
        $progbar = $progbar.'role="progressbar" aria-valuenow="'.$progvalue.'" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>';
        echo $progbar;
        echo '
        <script>
          window.onload = function() {
            t = document.getElementsByClassName("progress-bar")[0];
            t.style.width = "'.$progvalue.'%";
          };
        </script>
        '
        ?>
      </div>

      <div class="submit-button">
        <div class="btn btn-danger" onclick="document.location.href = 'student-cancel.php'">신청 취소</div>
      </div>
    </div>
  </div>

  <div class="container body-content">
    <div class="col-lg-3 col-md-2"></div>
    <div class="col-lg-6 col-md-8 card">
            <h3><b>[beta버전] 실시간 신청 상황 알림 페이지</b><h3>
              <h5>*지속적인 새로고침이 필요합니다. 자신의 팀이 승인되었는지 꼭 확인하기 바랍니다. 오류 시 제보 (신청취소된 순번은 없어지니 번호가 연속적이지 않습니다.)</h5>
              <h6>
              <?php
              $result = mysqli_query($connect, 'SELECT * FROM (SELECT ApplyID, group_concat(Username) FROM (SELECT applystudents.ApplyID, user.Username FROM applystudents, user WHERE applystudents.SID = user.SID) AS A GROUP BY A.ApplyID) AS B, apply WHERE B.ApplyID = apply. ApplyID');

              echo "<table class='table table-striped table-bordered table-hover, display:inline-block'>
                  <tr>
                  <th>신청번호</th>
                  <th>장소</th>
                  <th>목적</th>
                  <th>신청인단</th>
                  <th>승인</th>
                  </tr>";
              while ($row = mysqli_fetch_array($result)){
                echo "<tr>";
                echo "<td>" . $row[0] . "</td>";
                echo "<td>" . $row[4] . "</td>";
                echo "<td>" . $row[5] . "</td>";
                echo "<td>" . $row[1] . "</td>";
                if($row[8] == 1){
                  echo "<td>O</td>";
                }
                else {
                  echo "<td>X</td>";
                }
                echo "</tr>";
              }
              echo "</table>";

              mysqli_close($connect);
?>
          </h6>
        </div>
      </div>

  <?php require $_SERVER["DOCUMENT_ROOT"].'/scripts/ad.php'; EchoAd(); ?>
</body>
</html>
