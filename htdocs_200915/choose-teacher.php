<!DOCTYPE html>
<html>
    <head>
    <?php
    require $_SERVER["DOCUMENT_ROOT"].'/scripts/cookies.php';
    require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';
    ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>선택창</title>

    <script src="lib/jquery/jquery-3.3.1.min.js"></script>
    <script src="lib/bootstrap/dist/js/bootstrap.min.js"></script>
    <link href="lib/bootstrap/dist/css/bootstrap.css" rel="stylesheet" />

    <link href="css/site.css" rel="stylesheet" />

    <link href="css/navbar.css" rel="stylesheet" />
    <link href="css/fonts.css" rel="stylesheet" />
    <link href="css/choose.css" rel="stylesheet" />
    </head>
    <body>

    <script type="text/javascript">
    window.onload  = function() {
      $('#info').load('information2.php')
    }
    var auto_refresh = setInterval(
    function ()
    {
    $('#info').load('information2.php');
    }, 10000);
    </script>

    <?php
    include $_SERVER["DOCUMENT_ROOT"].'/scripts/navbar.php';
    EchoNavBar();

    $connect = DBConnect();

    date_default_timezone_set('Asia/Seoul');

    for($i=100; $i<301; $i++) {
        $query2 = "SELECT SID, RetDay FROM apply_laptop WHERE SID=".$i.";";
        $result2 = mysqli_query($connect, $query2);
        $row2 = mysqli_fetch_array($result2);
        if(isset($row2)) {
            $dRetDay = date('Y-m-d', strtotime($row2['RetDay']));

            $day = (strtotime($dRetDay)-strtotime(date("Y-m-d")))/86400;
            $SID = $row2['SID'];
            $query = "update apply_laptop set due='".$day."' where SID=".$SID.";";
            $connect->query($query);
        }

        $result = $connect->query("select * from laptop_return where SID=".$i.";");
      	$row = mysqli_fetch_array($result);
        $today = date('Y-m-d');
        if($row['cancel'] == 1){
          if($row['BanDay']==$today){
            $connect->query("update laptop_return set ban = 0 where SID =".$i.";");
            $connect->query("update laptop_return set cancel = 0 where SID =".$i.";");
          }
          else{
            $due_return = (strtotime($row['BanDay'])-strtotime($today))/86400;
            $connect->query("update laptop_return set ban = ".$due_return." where SID =".$i.";");
          }
        }
    }



    if($_COOKIE['UserSID'] >= 300){
        if(IsCookieSet() && CookieLogin($connect)){
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
    ?>
        <script type="text/javascript">
            var images = ['그림1.png', '그림2.png', '그림3.png', '그림4.png', '그림5.png', '그림6.png', '그림7.png', '그림8.png', '그림9.png', '그림10.png', '그림11.png', '그림12.png'];
            window.onload = function(){
            $('#box2').css({'background-image': 'url(img/' + images[Math.floor(Math.random() * images.length)] + ')'});
            $('#box21').css({'background-image': 'url(img/' + images[Math.floor(Math.random() * images.length)] + ')'});
            }
        </script>
          <style>
            h2{
              display:inline;
            }
          #re {
            -webkit-animation: rotation 5s infinite linear;
            width:2%;
            height:2%;
            margin-bottom:1%;
          }

          @-webkit-keyframes rotation {
              from {-webkit-transform: rotate(0deg);}
              to   {-webkit-transform: rotate(359deg);}
          }

          #icon{
            width:70%;
            height:70%;
            margin-top:10%;
            margin-bottom:10%;
          }
          @media (max-width: 576px){
                    #icon{
                      padding:10%;

                    }
                    #login_card{
                      padding-bottom: 3%;
                      margin-top: 10%;
                    }
                    h2{
                      margin-top:10%;
                    }
                }
          </style>
        <div class="container">
            <h1 id="bigtitle" style="text-align:center; margin-top:3%;">안녕하세요! <?php echo $_COOKIE['UserName']; ?>선생님</h1>
            <div class="row" style="margin-top:2.5%; width:100%">
            <h2 style="font-weight:700;">실시간 안내상황판 </h2>
              <img id="re" src="img/re.png" alt="">
              10초마다 업데이트
              <div id="info">
              <h3>특별실 신청 현황</h3>
                <h6>
                      <?php
                      $result = mysqli_query($connect, 'SELECT * FROM (SELECT ApplyID, group_concat(Username) FROM (SELECT applystudents.ApplyID, user.Username FROM applystudents, user WHERE applystudents.SID = user.SID) AS A GROUP BY A.ApplyID) AS B, apply WHERE B.ApplyID = apply. ApplyID');

                      echo "<table class='table table-striped table-bordered table-hover, display:inline-block'>
                          <tr>
                          
                          <th>장소</th>
                          <th>목적</th>
                          <th>신청인단</th>
                          <th>승인</th>
                          </tr>";
                      while ($row = mysqli_fetch_array($result)){
                        echo "<tr>";
                        //echo "<td>" . $row[0] . "</td>";
                        echo "<td>" . $row[4] . "</td>";
                        echo "<td>" . $row[5] . "</td>";

                        $names=explode(',', $row[1]);
                        $tmp_row =[];
                        for($i = 0 ; $i < count($names) ; $i++){
                          $str=$names[$i];
                          $tmp_row[$i]=$str;
                        }
                        $new_row=implode(",", $tmp_row);
                        echo "<td>" . $new_row . "</td>";

                        if($row[8] == 1){
                          echo "<td>O</td>";
                        }
                        else {
                          echo "<td>X</td>";
                        }
                        echo "</tr>";
                      }
                      echo "</table>";

                    ?>
                </h6>
            
                <h3>노트북 신청 현황</h3>
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

                          $filtered = $row["UserName"];
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
                      ?>
                  </div>
                </h6>

                <h3>상점 공고 목록</h3>
                <h6>
                  <div style='max-height:45vh; overflow-x:hidden; overflow-y:scroll;'>
                    <table class='table table-striped table-bordered table-hover, display:inline-block'>
                    <?php
                      $query = 'SELECT * FROM mileagenotice ORDER BY noticedate DESC;';
                      $result = mysqli_query($connect, $query);
                      echo "<tr>
                              <th style='text-align:center;'>일자</th>
                              <th style='text-align:center;'>제목</th>
                              <th style='text-align:center;'>공고자</th>
                              <th style='text-align:center;'>남은 인원 수</th>
                              <th style='text-align:center;'>부여 상점</th>
                              <th style='text-align:center;'>마감여부</th>
                            </tr>";

                      while ($row = mysqli_fetch_array($result)){
                          $filtered = $row["teachername"];
                          $num = $row[2] - $row['studentnum'];
                          echo "<tr>";
                          echo "<td style='text-align:center;'>" . $row[0] . "</td>";
                          echo "<td style='text-align:center;'>" . $row[1] . "</td>";
                          echo "<td style='text-align:center;'>" . $filtered . "</td>";
                          echo "<td style='text-align:center;'>" . $num . "명</td>";
                          echo "<td style='text-align:center;'>" . $row[3] . "점</td>";

                          if($row['isend']!=0) {
                            echo "<td style='text-align:center;'>" . "O" . "</td>";
                          }
                          else{
                            echo "<td style='text-align:center;'>" . "X" . "</td>";
                          }
                          echo "</tr>";
                      }
                      echo "</table>";
                      ?>
                  </div>
                </h6>
              </div>
            </div>
            <div class="row" style="margin-top:2.5%; width:100%">
                <div class="col-sm-12" id="box1" onclick="location.href='teacher.php';">
                        <div id="title" style="font-size: 3em;">특별실 신청 확인</div>
                        <div id="description" style="font-size: 1em;">특별실 신청 수락 및 거부</div>
                        <div id="description" style="font-size: 1em;">신청 현황 확인</div>
                </div>
                <div class="col-sm-6" id="box2" onclick="location.href='teacher-mileage.php';">
                        <div id="title" style="font-size: 3em;"><span>상벌점 조회</span></div>
                        <div id="description" style="font-size: 1em;">상벌점 순위 조회</div>
                        <div id="description" style="font-size: 1em;">상벌점 부여</div>
                        <div id="description" style="font-size: 1em;">상점 받을 학생 모집</div>
                </div>
                <div class="col-sm-6" id="box3" onclick="location.href='teacher-laptop-choose.php';">
                        <div id="title" style="font-size: 3em; z-index:2; margin-bottom:3%;">노트북 대여</div>
                        <div id="description" style="font-size: 1em; z-index:2; margin-bottom:2%;">노트북 대여 확인</div>
                        <div id="description" style="font-size: 1em; z-index:2; margin-bottom:4%;">노트북 대여 수락(진우용 선생님)</div>
                </div>
                <div class="col-sm-6" id="box4" onclick="location.href='#';">
                        <div id="title" style="font-size: 3em; z-index:2; margin-bottom:3%;">준비중</div>
                        <div id="description" style="font-size: 1em; z-index:2; margin-bottom:2%;">추가 희망 기능이 있다면 말씀해주세요</div>
                        <div id="description" style="font-size: 1em; z-index:2; margin-bottom:4%;">추가 희망 배너가 있다면 말씀해주세요</div>
                </div>
                <div class="col-sm-6" id="box21" onclick="location.href='student.php';" style="margin-bottom:5%;">
                        <div id="title" style="font-size: 3em; z-index:2; margin-bottom:3%;">특별실 신청</div>
                        <div id="description" style="font-size: 1em; z-index:2; margin-bottom:2%;">브릿지 수업 등 </div>
                        <div id="description" style="font-size: 1em; z-index:2; margin-bottom:4%;">선생님들이 특별실을 신청할 수 있는 공간입니다.</div>
                </div>
            </div>
        </div>
    </body>
</html>
