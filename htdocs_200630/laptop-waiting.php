<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>노트북 신청 현황</title>

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

      $row = $connect->query("select * from apply_laptop where SID=".$SID.";")->fetch_array();
      if(!isset($row))
      {
        echo "<meta http-equiv='refresh' content='0;url=choose.php'>"; // 신청이 안되어 있을 시 choose.php로 전송
        exit;
      }

      $SubjNum = $row['SubjNum'];
      $row2 = $connect->query("select subj, num, borrow from laptop_list where SubjNum = ".$SubjNum.";")->fetch_array();
      $subject = $row2['subj'];
      $num = $row2['num'];
      $IsApproved = $row['IsApproved'];
      // 수락 여부 표시
      $purpose = $row['Purpose'];
      //목적 표시
      //$period = $row['Period'];
      // 시간 표시
      // 장소 표시
      //$teacher = $connect->query("select UserName from user where SID = ".$row['TeacherSID'])->fetch_array()['UserName'].' 선생님';
      // 신청 선생님 표시
      //$students = ''; $num = 0;
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
      <?php
        switch ($subject) {
          case 'physics':
              $my_subj="물리";
              break;
          case 'chemistry':
              $my_subj="화학";
              break;
          case 'life':
              $my_subj="생명과학";
              break;
          case 'earth':
              $my_subj="지구과학";
              break;
          case 'math':
              $my_subj="수학";
              break;
          default:
              $my_subj="정보";
              break;
      };
      ?>
      <ul class="application">
        <li><label>빌린 노트북 : <?php echo $my_subj." ".$num; ?></label></li>
        <li><label>대여 목적 : <?php echo $purpose; ?></label></li>
        <li><label style="color: crimson"><?php echo "* 집에 가기 전에 꼭 반납해주세요!"; ?></label></li>
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
        switch($IsApproved)
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
        <div class="btn btn-danger" onclick="document.location.href = 'laptop-cancel.php'">신청 취소</div>
      </div>
    </div>
  </div>

  <div class="container body-content">
    <div class="col-lg-3 col-md-2"></div>
    <div class="col-lg-6 col-md-8 card">
            <h3><b>실시간 신청 상황 알림 페이지</b><h3>
              <h5>*지속적인 새로고침이 필요합니다. 자신의 신청이 승인되었는지 꼭 확인하기 바랍니다.</h5>
              <h6>
                <div style='max-height:45vh; overflow-x:hidden; overflow-y:scroll;'>
                  <table class='table table-striped table-bordered table-hover, display:inline-block'>
                  <?php
                    $query = 'SELECT laptop_list.*,apply_laptop.BorDay,apply_laptop.RetDay,user.UserName FROM apply_laptop LEFT JOIN laptop_list ON laptop_list.SubjNum=apply_laptop.SubjNum LEFT JOIN user ON apply_laptop.SID=user.SID;';
                    $result = mysqli_query($connect, $query);
                    echo "<tr>
                            <th style='text-align:center;'>과목</th>
                            <th style='text-align:center;'>번호</th>
                            <th style='text-align:center;'>대여자</th>
                            <th style='text-align:center;'>대여기간</th>
                            <th style='text-align:center;'>승인</th>
                          </tr>";

                    while ($row = mysqli_fetch_array($result)){
                        switch ($row['subj']) {
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
                            case 'information':
                                $subj_kr="정보";
                                break;
                        }

                        
                        $date = $row['BorDay']." ~ ".$row['RetDay'];

                        $filtered = mb_substr($row["UserName"],0,1,'utf-8').'*'.mb_substr($row['UserName'],2,2,'utf-8');
                        echo "<tr>";
                        echo "<td style='text-align:center;'>" . $subj_kr . "</td>";
                        echo "<td style='text-align:center;'>" . $row['num'] . "</td>";
                        echo "<td style='text-align:center;'>" . $filtered . "</td>";
                        echo "<td style='text-align:center;'>" . $date . "</td>";

                        if($row['borrow']==2) {
                          echo "<td style='text-align:center;'>" . "O" . "</td>";
                        }
                        else{
                          echo "<td style='text-align:center;'>" . "X" . "</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                    mysqli_close($connect);
                  ?>
                </div>
          </h6>
        </div>
      </div>
  <?php require $_SERVER["DOCUMENT_ROOT"].'/scripts/ad.php'; EchoAd(); ?>
</body>
</html>
