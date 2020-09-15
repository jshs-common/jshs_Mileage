<!DOCTYPE html>
<html>
    <head> 
    <?php
    require $_SERVER["DOCUMENT_ROOT"].'/scripts/cookies.php';
    require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';
    ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>학생 상벌점 관리</title>

    <!-- <script type="text/javascript" src="script/jquery.autocomplete.js>"></script>
    <link rel="stylesheet" type="text/css" href="script/jquery.autocomplete.css"/>  -->

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- <script src="lib/jquery/jquery-3.3.1.min.js"></script> -->
    <script src="lib/bootstrap/dist/js/bootstrap.min.js"></script>
    <link href="lib/bootstrap/dist/css/bootstrap.css" rel="stylesheet" />

    <link href="css/site.css" rel="stylesheet" />
    <link href="css/navbar.css" rel="stylesheet" />
    <link href="css/fonts.css" rel="stylesheet" />
    <link href="css/teacher-mileage.css" rel="stylesheet" />

    <!-- <script type="text/javascript" src="script/lib/jquery.js>"></script>
    <script type="text/javascript" src="script/lib/jquery.bgiframe.min.js>"></script>
    <script type="text/javascript" src="script/lib/jquery.ajaxQueue.js>"></script> -->

    <script>
    document.addEventListener('keydown', function(event) {
        if (event.keyCode === 13 && event.srcElement.type != 'input') {
            event.preventDefault();
        };
        }, true);
    </script>
    </head>
    <body>
        <?php
            include $_SERVER["DOCUMENT_ROOT"].'/scripts/navbar.php';
            include $_SERVER["DOCUMENT_ROOT"].'/scripts/sidebar.php';
            EchoNavBar();

            $connect = DBConnect();
            if($_COOKIE['UserSID'] >= 300){
                if(IsCookieSet() && CookieLogin($connect)){
                  $id = $_COOKIE['UserName'];
                  $realid = explode( '(', $id);
                  $SID = $_COOKIE['UserSID'];
                  $period = $_GET["sm"]; 
                  $isminus = $_GET["pom"];
                  $search = $_GET["searchbox"];
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
<body>
    <link href="css/sidebar.css" rel="stylesheet"/>
<div class="container-fluid">
  <div class="row">
        <div class="col-xs-2" style="padding: 0 0 0 0;">
            <?php EchoSideBar(); ?>
        </div>
        <div class="col-xs-10" style="padding-left: 2%;">
            <h2>상벌점 취소</h2>
            <h4>부여했던 상벌점을 다시 취소할 수 있습니다.(최신순으로 정렬되어 있음)
            </h4>
            <h4>취소되어도 데이터베이스에는 남게 설정되어 있습니다! 확인이 필요하실 경우 김채린 학생을 불러주세요.</h4><br><br>
            <form method="get" action="">
        <div class="function" style="background-color:#EAEAEA;">
            &nbsp;&nbsp;학기
            <select class="custom-select" name="sm" id="sm" style="margin-right:0.5%;">
                <option value="all" <?php if($period == "all"){ echo "selected"; } ?>>전체</option>
                <option value="1" <?php if($period == "1"){ echo "selected"; } ?>>1학기</option>
                <option value="2" <?php if($period == "2"){ echo "selected"; } ?>>2학기</option>
            </select>
            상/벌
            <select class="custom-select" name="pom" id="pom" style="margin-right:24%;">
                <option value="all" <?php if($isminus == "all"){ echo "selected"; } ?>>전체</option>
                <option value="1" <?php if($isminus == "1"){ echo "selected"; } ?>>벌점</option>
                <option value="0" <?php if($isminus == "0"){ echo "selected"; } ?>>상점</option>
            </select>
            <!-- 유형 <select class="custom-select" name="unit" id="unit"
            style="margin-right:8%;"> <option value="all">전체</option> </select> -->
            <div class="search" style="display:inline-block;">
                <input
                    name="searchbox"
                    type="text"
                    placeholder="이름 검색시 정확히 입력해주세요"
                    value="<?php echo $search;?>">
            </div>
            <button type="submit" id="search" class="btn btn-outline-secondary">검색</button>
        </div>
    </form>
      <div id="result" style ="max-height:100vh; overflow-x:hidden; overflow-y:scroll;">
          <h6>
          <?php
              $searchstudent = mysqli_query($connect, "SELECT SID FROM user WHERE ID = '".$search."'") or die(mysqli_error($connect));
              $SID = mysqli_fetch_array($searchstudent);           
                                  if($period == "all"){
                                      if($isminus == "all"){
                                          $searchsql = mysqli_query($connect, "SELECT * FROM mileagehistory WHERE teachername = '".$realid[0]."'  AND  (description LIKE '%".$search."%' OR SID LIKE '%".$SID[0]."%') order by Checkdate desc") or die(mysqli_error($connect));
                                      }
                                      else{
                                          $searchsql = mysqli_query($connect, "SELECT * FROM mileagehistory WHERE teachername = '".$realid[0]."' AND Isminus = ".$isminus ." AND (description LIKE '%".$search."%' OR SID LIKE '%".$SID[0]."%') order by Checkdate desc") or die(mysqli_error($connect));
                                      }
                                  }
                                  if($period != "all"){
                                      if($isminus == "all" ){
                                          $searchsql = mysqli_query($connect, "SELECT * FROM mileagehistory WHERE teachername = '".$realid[0]."' AND period = ".$period ." AND (description LIKE '%".$search."%' OR SID LIKE '%".$SID[0]."%') order by Checkdate desc") or die(mysqli_error($connect));
                                      }
                                      else{
                                          $searchsql = mysqli_query($connect, "SELECT * FROM mileagehistory WHERE teachername = '".$realid[0]."' AND period = ".$period." AND Isminus = ".$isminus." AND (description LIKE '%".$search."%' OR SID LIKE '%".$SID[0]."%') order by Checkdate desc") or die(mysqli_error($connect));
                                      }
                                  }

                                  $k = 1;
                              
                                  echo "<table class='table table-striped table-bordered table-hover, display:inline-block'>
                                      <tr>
                                      <th style='text-align:center;'>번호</th>
                                      <th style='text-align:center;'>날짜</th>
                                      <th style='text-align:center;'>이름</th>
                                      <th style='text-align:center;'>유형</th>
                                      <th style='text-align:center;'>사유</th>
                                      <th style='text-align:center;'>상벌점</th>
                                      <th style='text-align:center;'>취소</th>
                                      </tr>";
                                  while ($row = mysqli_fetch_array($searchsql)){
                                          $searchstudent = mysqli_query($connect, "SELECT ID FROM user WHERE SID = ".$row[0]) or die(mysqli_error($connect));
                                          $studentname = mysqli_fetch_array($searchstudent);
                                          $description = str_replace($search, '<span style="background-color:#FFE400">'.$search."</span>", $row['description']);
                                          $teacher = str_replace($search, '<span style="background-color:#FFE400">'.$search."</span>", $row[0]);
                                          echo "<tr>";
                                              echo "<td style='text-align:center;'>" . $k . "</td>";
                                              echo "<td style='text-align:center;'>" . $row[1] . "</td>";
                                              echo "<td style='text-align:center;'>" . $studentname[0] . "</td>";
                                              echo "<td style='text-align:center;'>" . $row[4] . "</td>";
                                              echo "<td>" .$description. "</td>";
                                              if($row[7] == 0){
                                              echo "<td style='text-align:center; color:green; font-weight:700;'>". $row[6] . "</td>";
                                              }
                                              else {
                                              echo "<td style='text-align:center; color:red; font-weight:700;'>". $row[7] . "</td>";
                                              }
                                              echo '<td style="text-align:center;"><div class="btn btn-danger" style="font-size:10%;" onclick="location.href=\'mileage-cancel-apply.php?time='.$row[1].'&sid='.$row[0].'\';">취소</div></td>';
                                              echo "</tr>";
                                          $k = $k+1;
                                      }
                                  echo "</table>";

                                  mysqli_close($connect);

                              ?>
          </h6>
          </div>
        </div>
  </div>
</div>
</body>