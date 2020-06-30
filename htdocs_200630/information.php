<!DOCTYPE html>
<html>
    <head> 
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>로그인</title>

    <script src="lib/jquery/jquery-3.3.1.min.js"></script>
    <script src="lib/bootstrap/dist/js/bootstrap.min.js"></script>
    <link href="lib/bootstrap/dist/css/bootstrap.css" rel="stylesheet" />

    <link href="css/site.css" rel="stylesheet" />

    <link href="css/navbar.css" rel="stylesheet" />
    <link href="css/fonts.css" rel="stylesheet" />

    </head>
    <body>
        <h3>특별실 신청 현황</h3>
        <h6>
              <?php
              require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';
              $connect = DBConnect();
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
                  $tmp_row[$i]=mb_substr($str, 0, 1, 'utf-8').'*'.mb_substr($str, 2, 2, 'utf-8');
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
                  $filtered = mb_substr($row["teachername"],0,1,'utf-8').'*'.mb_substr($row['teachername'],2,1,'utf-8');
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
    </body>
</html>