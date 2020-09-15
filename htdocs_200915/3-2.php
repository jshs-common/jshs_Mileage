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

    <script src="lib/jquery/jquery-3.3.1.min.js"></script>
    <script src="lib/bootstrap/dist/js/bootstrap.min.js"></script>
    <link href="lib/bootstrap/dist/css/bootstrap.css" rel="stylesheet" />

    <link href="css/site.css" rel="stylesheet" />
    <link href="css/navbar.css" rel="stylesheet" />
    <link href="css/fonts.css" rel="stylesheet" />
    </head>
    <body>
        <?php
            include $_SERVER["DOCUMENT_ROOT"].'/scripts/navbar.php';
            include $_SERVER["DOCUMENT_ROOT"].'/scripts/sidebar.php';
            EchoNavBar();

            $connect = DBConnect();
            if($_COOKIE['UserSID'] >= 300){
                if(IsCookieSet() && CookieLogin($connect)){
                    $username = $_COOKIE['UserName'];
                    $period = $_GET["sm"]; 
                    $isminus = $_GET["pom"];
                    $SID = $_COOKIE['UserSID'];
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
        <script>
            $(document).ready(function() { 
                $('.counter1').counterUp();
                $('.counter2').counterUp();
                $('.counter3').counterUp();
            });
        </script>
        <style>
            #plusscore{
                color:green;
            }
            #minusscore{
                color:red;
            }
            #score{
                color:black;
            }
            .counter1{
                text-align: center;
                font-size: 3em;
            }
            .counter2{
                text-align: center;
                font-size: 3em;
            }
            .counter3{
                text-align: center;
                font-size: 3em;
            }
        </style>
        <link href="css/sidebar.css" rel="stylesheet" />
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-2" style="padding: 0 0 0 0;">
                <?php EchoSideBar(); ?>
                </div>
                <div class="col-xs-2" style="padding: 0 0 0 0;">
                <?php
                echo '<div class="list-group" style="overflow-x:hidden; overflow-y:scroll; max-height: 90vh; width: 100%;"  id="sidebar2">';
                for($i=193; $i<205; $i++){
                    $searchsql = mysqli_query($connect, "SELECT * FROM totalmileage WHERE SID = ".$i) or die(mysqli_error($connect));
                    $row = mysqli_fetch_array($searchsql);
                    $num = substr((string)($row[1]), 2, 2);
                    echo '<a href="?SID='.$row[0].'&sm=all&pom=all&searchbox=" class="list-group-item">
                            <i class="fa fa-dashboard"></i> 
                        <span class="hidden-sm-down">'.$num.'번 '.$row[2].': <strong>'.($row[3]-$row[4]).'</strong></span></a>'; 
                }
                echo '</div>';
                ?>
                </div>
                <div class="col-xs-8">
                    <?php 
                     $SID = $_GET['SID'];
                     $searchsql2 = mysqli_query($connect, "SELECT * FROM totalmileage WHERE SID = ".$SID) or die(mysqli_error($connect));
                     $result = mysqli_fetch_array($searchsql2);
                    ?>
                    <div class="row" style = "margin-top:2%;">
                        <div class="col-xs-6" style = "padding-left:3%;">
                            <h2> <?php echo $result[2];?> 학생의 상벌점 현황 </h2>
                        </div>
                        <div class="col-xs-2">
                            <div id="plusscore">상점</div>
                            <div class="counter1">
                                <?php echo $result[3];?>
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <div id="minusscore">벌점</div>
                            <div class="counter2">
                                <?php echo $result[4];?>
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <div id="score">총합</div>
                            <div class="counter3">
                                <?php echo $result[3]-$result[4];?>
                            </div>
                        </div>
                    </div>
                    <h3>상벌점 조회</h3>
                        <form method="get" action="">
                        <input name="SID" id="SID" type="hidden" value="<?php echo $SID?>">
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
                                        $searchsql3 = mysqli_query($connect, "SELECT * FROM mileagehistory WHERE SID = ".$SID."  AND  (description LIKE '%".$search."%' OR teachername LIKE '%".$search."%') order by Checkdate asc") or die(mysqli_error($connect));
                                    }
                                    else{
                                        $searchsql3 = mysqli_query($connect, "SELECT * FROM mileagehistory WHERE SID = ".$SID." AND Isminus = ".$isminus ." AND (description LIKE '%".$search."%' OR teachername LIKE '%".$search."%') order by Checkdate asc") or die(mysqli_error($connect));
                                    }
                                }
                                if($period != "all"){
                                    if($isminus == "all" ){
                                        $searchsql3 = mysqli_query($connect, "SELECT * FROM mileagehistory WHERE SID = '".$SID."' AND period = ".$period ." AND (description LIKE '%".$search."%' OR teachername LIKE '%".$search."%') order by Checkdate asc") or die(mysqli_error($connect));
                                    }
                                    else{
                                        $searchsql3 = mysqli_query($connect, "SELECT * FROM mileagehistory WHERE SID = ".$SID." AND period = ".$period." AND Isminus = ".$isminus." AND (description LIKE '%".$search."%' OR teachername LIKE '%".$search."%') order by Checkdate asc") or die(mysqli_error($connect));
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
                                    while ($row = mysqli_fetch_array($searchsql3)){
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>
        <script src="lib/jquery.counterup.js"></script>
    </body>
</html>