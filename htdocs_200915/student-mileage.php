<!DOCTYPE html>
<html>
    <head> 
    <?php
    require $_SERVER["DOCUMENT_ROOT"].'/scripts/cookies.php';
    require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';
    ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>내 상벌점 확인</title>

    <script src="lib/jquery/jquery-3.3.1.min.js"></script>
    <script src="lib/bootstrap/dist/js/bootstrap.min.js"></script>
    <link href="lib/bootstrap/dist/css/bootstrap.css" rel="stylesheet" />

    <link href="css/site.css" rel="stylesheet" />

    <link href="css/navbar.css" rel="stylesheet" />
    <link href="css/fonts.css" rel="stylesheet" />
    <link href="css/mileage.css" rel="stylesheet" />
    </head>
    <style>
        /* body{
            background: url("../img/jshs1.png") center no-repeat;
            background-position: center;
            background-size: cover;
        } */
    </style>
    <body>
    
    <?php
    include $_SERVER["DOCUMENT_ROOT"].'/scripts/navbar.php';
    EchoNavBar();

    $connect = DBConnect();
    if(IsCookieSet() && CookieLogin($connect))
    {
        $username = $_COOKIE['UserName'];
        $period = $_GET["sm"]; 
        $isminus = $_GET["pom"];
        $SID = $_COOKIE['UserSID'];
        $search = $_GET["searchbox"];
    }
    else
    {
      error(2);
      exit;
    }
    ?>
    <script>
        $(document).ready(function() { 
            $('.counter1').counterUp();
            $('.counter2').counterUp();
            $('.counter3').counterUp();

        // $("#search").click(function(){
        //     var sel_one = $("#sm option:selected").val();
        //     var sel_two = $("#pom option:selected").val();
        //     var sel_three = $("#unit option:selected").val();
        //     var sel_three = $("#unit option:selected").val();
        //     if(sel_one == '전체'){
        //         sel_one == "None"
        //     }
        //     if(sel_one == '1학기'){
        //         sel_one == "1"
        //     }
        //     if(sel_one == '2학기'){
        //         sel_one == "2"
        //     }
        //     if(sel_two == '전체'){
        //         sel_two == "None"
        //     }
        //     if(sel_two == '상점'){
        //         sel_two == "0"
        //     }
        //     if(sel_two == '벌점'){
        //         sel_two == "1"
        //     }
        //     if(sel_three == '전체'){
        //         sel_three == "None"
        //     }
        //     $.ajax({
        //         type: 'get',
        //         url:'../student-mileage-search.php',
        //         dataType: "json",
        //         data: {'sm': sel_one, 
        //                'pom': sel_two, 
        //                'unit': sel_three},
        //         success: function (data) {
        //             $("#tableresult").remove(); 
        //             $.each(data, function())
        //             $('#tableresult').append("<tr> <td>" + data + "</td> </tr>");
        //         },
        //         error: function () {
        //             console.log('error');
        //         }
        //     }); 
        // });
        });

    </script>
        <div class="container">
            <div class="row" style="margin-top:2%;">
                <div class="col-xs-2" style=" text-align:center;" id="move">
                    <img src="img/ui.png" style="height:55%; width:55%;" alt="" onclick="location.href='ranking.php';"><br>상점순위
                </div>
                <div class="col-xs-10 col-sm-6">
                    <h1><?php echo $_COOKIE['UserName']; ?>님의 상벌점 현황</h1>
                    <h4><?php
                    $sql = mysqli_query($connect, 'SELECT plus, minus FROM totalmileage WHERE ID = "'.$username.'"');
                    $result = mysqli_fetch_array($sql);
                    if ($result[0]-$result[1] <= -20){
                    echo "⚠벌점이 20점이 넘었습니다.⚠";
                    } 
                    ?>
                    </h4>
                </div>
                <div class="col-xs-4 col-sm-offset-1 col-sm-1" id="score">
                    <div id="plusscore">상점</div>
                    <div class="counter1" style="color:green">
                        <?php echo $result[0];?>
                    </div>
                </div>
                <div class="col-xs-4 col-sm-1" id="score">
                    <div id="minusscore">벌점</div>
                    <div class="counter2" style="color:red">
                        <?php echo $result[1];?>
                    </div>
                </div>
                <div class="col-xs-4 col-sm-1" id="score">
                    총합<br>
                    <div class="counter3">
                        <?php echo $result[0]-$result[1];?>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top:2%;">
                <div class="col-sm-offset-0 col-sm-4"  id="box1">
                    <h3>신청 가능한 상점 목록</h3>
                    <h6>
                        <div style='max-height:45vh; overflow-x:hidden;'>
                            <table class='table table-striped table-bordered table-hover, display:inline-block'>
                            <?php
                            $query = 'SELECT * FROM mileagenotice ORDER BY noticedate DESC;';
                            $result = mysqli_query($connect, $query);
    
                            echo "<tr>
                                    <th style='text-align:center;'>일자</th>
                                    <th style='text-align:center;'>제목</th>
                                    <th style='text-align:center;'>공고자</th>
                                    <th style='text-align:center;'>남은 인원</th>
                                    <th style='text-align:center;'>보상</th>
                                    <th style='text-align:center;'>마감여부</th>
                                    <th style='text-align:center;'>신청하기</th>
                                    </tr>";

                            while ($row = mysqli_fetch_array($result)){
                                $studentlist = explode( ', ', $row['students']);
                                $num = $row[2] - $row['studentnum'];
                                echo "<tr>";
                                echo "<td style='text-align:center;'>" . $row[0] . "</td>";
                                echo "<td style='text-align:center;'>" . $row[1] . "</td>";
                                echo "<td style='text-align:center;'>" . $row[6] . "</td>";
                                echo "<td style='text-align:center;'>" . $num . "명</td>";
                                echo "<td style='text-align:center;'>" . $row[3] . "점</td>";

                                if($row['isend'] == 2) {
                                    echo "<td style='text-align:center;'>" . "O" . "</td>";
                                    if(in_array($username, $studentlist)){
                                        echo "<td style='text-align:center; font-weight:600;'>상점 지급 완료</td>";
                                    }
                                    else{
                                        echo "<td style='text-align:center;'>종료</td>";
                                    }
                                }
                                else if($num==0 || $row['isend'] == 1 ){
                                    echo "<td style='text-align:center;'>" . "O" . "</td>";
                                    if(in_array($username, $studentlist)){
                                        echo "<td style='text-align:center; font-weight:600;'>미션 대기(모집완료)</td>";
                                    }
                                    else{
                                        echo "<td style='text-align:center;'>모집 마감</td>";
                                    }
                                }
                                else{
                                    echo "<td style='text-align:center;'>" . "X" . "</td>";
                                    if(in_array($username, $studentlist)){
                                        echo "<td style='text-align:center; font-weight:600;'>신청 완료(모집중)</td>";
                                    }
                                    else{
                                        echo '<td style="text-align:center;"><div class="btn btn-success" onclick="location.href=\'student_enroll.php?time='.$row[0].'&num='.$row['studentnum'].'\';">신청하기</div></td>';
                                    }
                                }
                                echo "</tr>";
                            }
                            echo "</table>";
                        ?>
                            </div>
                        </h6>
                </div>
                <div class="col-sm-offset-1 col-sm-7"  id="box2">
                    <h3>상벌점 조회</h3>
                        <form method="get" action="">
                        <div class="function" style="background-color:#EAEAEA;">
                        &nbsp;&nbsp;학기
                            <select class="custom-select" name="sm" id="sm" style="margin-right:0.5%;">
                                <option value="all" 
                                <?php if($period == "all"){
                                    echo "selected";
                                 } ?>>전체</option>
                                <option value="1"
                                <?php if($period == "1"){
                                    echo "selected";
                                 } ?>>1학기</option>
                                <option value="2"
                                <?php if($period == "2"){
                                    echo "selected";
                                 } ?>>2학기</option>
                            </select>
                            상/벌
                            <select class="custom-select" name="pom" id="pom" style="margin-right:24%;">
                                <option value="all"
                                <?php if($isminus == "all"){
                                    echo "selected";
                                 } ?>>전체</option>
                                <option value="1"
                                <?php if($isminus == "1"){
                                    echo "selected";
                                 } ?>>벌점</option>
                                <option value="0"
                                <?php if($isminus == "0"){
                                    echo "selected";
                                 } ?>>상점</option>
                            </select>
                            <!-- 유형
                            <select class="custom-select" name="unit" id="unit" style="margin-right:8%;">
                                <option value="all">전체</option>
                            </select> -->
                            <div class="search" style="display:inline-block;">
                                <input name="searchbox" type="text" placeholder="검색어를 입력해주세요." value="<?php
                                    echo $search;?>">
                            </div>
                            <button type="submit" id="search" class="btn btn-outline-secondary" >검색</button>
                        </div>
                    </form>
                    <div id="result">
                        <h6>
                            <?php
                            
                                if($period == "all"){
                                    if($isminus == "all"){
                                        $searchsql = mysqli_query($connect, "SELECT * FROM mileagehistory WHERE SID = ".$SID."  AND  (description LIKE '%".$search."%' OR teachername LIKE '%".$search."%')") or die(mysqli_error($connect));
                                    }
                                    else{
                                        $searchsql = mysqli_query($connect, "SELECT * FROM mileagehistory WHERE SID = ".$SID." AND Isminus = ".$isminus ." AND (description LIKE '%".$search."%' OR teachername LIKE '%".$search."%')") or die(mysqli_error($connect));
                                    }
                                }
                                if($period != "all"){
                                    if($isminus == "all" ){
                                        $searchsql = mysqli_query($connect, "SELECT * FROM mileagehistory WHERE SID = '".$SID."' AND period = ".$period ." AND (description LIKE '%".$search."%' OR teachername LIKE '%".$search."%')") or die(mysqli_error($connect));
                                    }
                                    else{
                                        $searchsql = mysqli_query($connect, "SELECT * FROM mileagehistory WHERE SID = ".$SID." AND period = ".$period." AND Isminus = ".$isminus." AND (description LIKE '%".$search."%' OR teachername LIKE '%".$search."%')") or die(mysqli_error($connect));
                                    }
                                }

                                $k = 1;
                            
                                echo "<table class='table table-striped table-bordered table-hover, display:inline-block'>
                                    <tr>
                                    <th style='text-align:center;'>번호</th>
                                    <th style='text-align:center;'>날짜</th>
                                    <th style='text-align:center;'>유형</th>
                                    <th style='text-align:center;'>사유</th>
                                    <th style='text-align:center;'>상벌점</th>
                                    <th style='text-align:center;'>담당 선생님</th>
                                    </tr>";
                                while ($row = mysqli_fetch_array($searchsql)){
                                        $description = str_replace($search, '<span style="background-color:#FFE400">'.$search."</span>", $row['description']);
                                        $teacher = str_replace($search, '<span style="background-color:#FFE400">'.$search."</span>", $row[8]);
                                        echo "<tr>";
                                            echo "<td style='text-align:center;'>" . $k . "</td>";
                                            echo "<td style='text-align:center;'>" . $row[1] . "</td>";
                                            echo "<td style='text-align:center;'>" . $row[4] . "</td>";
                                            echo "<td>" .$description. "</td>";
                                            if($row[7] == 0){
                                            echo "<td style='text-align:center; color:green; font-weight:700;'>". $row[6] . "</td>";
                                            }
                                            else {
                                            echo "<td style='text-align:center; color:red; font-weight:700;'>". $row[7] . "</td>";
                                            }
                                            echo "<td style='text-align:center;'>" . $teacher . " 선생님</td>";
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
        <!-- <script>
            function categoryChange() {
                var minus_category = ["전체", "일과 전반", "조조", "면학실", "생활관", "기타"];
                var plus_category = ["전체", "모범", "기타"];
                var target = document.getElementById("unit");

                if($("#pom").val() == "minus") var d = minus_category;
                else if($("#pom").val() == "plus") var d = plus_category;

                target.options.length = 0;

                for (x in d) {
                    var opt = document.createElement("option");
                    opt.value = d[x];
                    opt.innerHTML = d[x];
                    target.appendChild(opt);
                }	
            }
        </script> -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>
        <script src="lib/jquery.counterup.js"></script>
    </body>
</html>