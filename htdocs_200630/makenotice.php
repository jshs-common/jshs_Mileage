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

    <!-- <script>
    document.addEventListener('keydown', function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
        };
        }, true);
    </script> -->
    </head>
    <body>
        <?php
            include $_SERVER["DOCUMENT_ROOT"].'/scripts/navbar.php';
            include $_SERVER["DOCUMENT_ROOT"].'/scripts/sidebar.php';
            EchoNavBar();

            $connect = DBConnect();
            if($_COOKIE['UserSID'] >= 300){
                if(IsCookieSet() && CookieLogin($connect)){
                  $SID = $_COOKIE['UserSID'];
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
        <link href="css/sidebar.css" rel="stylesheet" />
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-2" style="padding: 0 0 0 0;">
                <?php EchoSideBar(); ?>
                </div>
                <div class="col-xs-10" style="padding-left: 2%;">
                    <h2>상점 받을 학생 모집하기(BETA)</h2>
                    <h4>아래 양식들을 채워 상점 받을 학생들을 모집하세요</h4><br><br>
                    <div class="row">
                    <div class="col-sm-offset-1 col-sm-10 " style="background-color:white; box-shadow: 0 8px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); min-height:60vh; border-radius: 30px; padding-bottom: 5%; float: center; margin-bottom:5%;" >
                            <br><h1 style="text-align: center; font-family: 'BM DoHyeon'; margin-bottom:2%;">전체 상점 공고 목록</h1>
                            <h5 style="text-align: center;">학생은 선착순으로 받아지며 공고는 최신순입니다. <br> 희망 학생이 다 모집되지 않아도 마감할 수 있습니다. 상점 공고 목록은 학기 단위로 초기화됩니다. :)</h5>
                            <br>
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
                                            <th style='text-align:center;'>남은 인원 수</th>
                                            <th style='text-align:center;'>부여 상점</th>
                                            <th style='text-align:center;'>마감여부</th>
                                            </tr>";

                                    while ($row = mysqli_fetch_array($result)){
                                        $num = $row[2] - $row['studentnum'];
                                        echo "<tr>";
                                        echo "<td style='text-align:center;'>" . $row[0] . "</td>";
                                        echo "<td style='text-align:center;'>" . $row[1] . "</td>";
                                        echo "<td style='text-align:center;'>" . $row[6] . "</td>";
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
                        <div class="col-sm-offset-1 col-sm-10 " style="background-color:white; box-shadow: 0 8px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); min-height:60vh; border-radius: 30px; padding-bottom: 5%; float: center;" >
                        <form method="get" action="makenotice_apply.php">
                        <br>
                            <h1 style="text-align: center; font-family: 'BM DoHyeon'; margin-bottom:5%;">상점 공고</h1>
                            <h3>1. 제목</h3>
                            <input class="form-control" name="title" type="text" placeholder="ex) 강당 청소할 2학년 학생 모집합니다.">
                            <h3>2. 인원수</h3>
                            <select class="form-control" name="num" id="num">
                                <option value="0">::선택::</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                            <h3>3. 부여할 상점</h3>
                            <select class="form-control" name="score" id="score">
                                <option value="0">::선택::</option>
                                <option value="3">3</option>
                            </select>
                            <h3>4. 공고자 : <?php echo $username?></h3>
                            <div style="text-align: center;">
                                <button class="btn btn-success" type="submit">완료</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
                
