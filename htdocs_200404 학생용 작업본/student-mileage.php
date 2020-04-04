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
    <body>
    <?php
    include $_SERVER["DOCUMENT_ROOT"].'/scripts/navbar.php';
    EchoNavBar();

    $connect = DBConnect();
    if(IsCookieSet() && CookieLogin($connect))
    {
        $username = $_COOKIE['UserName'];
    }
    else
    {
      error(2);
      exit;
    }
    ?>
    <script>
        $(document).ready(function(){
	        $('.counter1').counterUp();
            $('.counter2').counterUp();
            $('.counter3').counterUp();
        });

    </script>
        <div class="container">
            <div class="row" style="margin-top:2%;">
                <div class="col-xs-2" style=" text-align:center;" id="move">
                    <img src="img/ui.png" style="height:55%; width:55%;" alt="" onclick="location.href='choose.php';"><br>상점순위
                </div>
                <div class="col-xs-10 col-sm-6">
                    <h1><?php echo $_COOKIE['UserName']; ?>님의 상벌점 현황</h1>
                    <h4><?php
                    $sql = mysqli_query($connect, 'SELECT plus, minus FROM totalmileage WHERE ID = "'.$username.'"');
                    $result = mysqli_fetch_array($sql);
                    if ($result[1]-$result[0] >= 20){
                    echo "⚠벌점이 20점이 넘었습니다.⚠";
                    } 
                    ?>
                    </h4>
                </div>
                <div class="col-xs-4 col-sm-offset-1 col-sm-1" id="score">
                    <div id="plussscore">상점</div>
                    <div class="counter1">
                        <?php echo $result[0];?>
                    </div>
                </div>
                <div class="col-xs-4 col-sm-1" id="score">
                    <div id="minusscore">벌점</div>
                    <div class="counter2">
                        <?php echo $result[1];?>
                    </div>
                </div>
                <div class="col-xs-4 col-sm-1" id="score">
                    총합<br>
                    <div class="counter3">
                        <?php echo $result[1]-$result[0];?>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top:2%;">
                <div class="col-sm-offset-0 col-sm-4"  id="box1">
                    <h3>신청 가능한 상점 목록</h3>
                </div>
                <div class="col-sm-offset-1 col-sm-7"  id="box2">
                    <h3>상벌점 조회</h3>
                        <form action="">
                        <div class="function" style="background-color:#EAEAEA;">
                        &nbsp;&nbsp;학기
                            <select class="custom-select" name="sm" id="sm" style="margin-right:0.5%;">
                                <option value="all">전체</option>
                                <option value="1sm">1학기</option>
                                <option value="2sm">2학기</option>
                            </select>
                            상/벌
                            <select class="custom-select" name="pom" id="pom" style="margin-right:0.5%;" onchange="categoryChange()">
                                <option value="all">전체</option>
                                <option value="minus">벌점</option>
                                <option value="plus">상점</option>
                            </select>
                            유형
                            <select class="custom-select" name="unit" id="unit" style="margin-right:8%;">
                            </select>
                            <div class="search" style="display:inline-block;">
                                <input type="text" placeholder="검색어를 입력해주세요.">
                            </div>
                            <button type="button" class="btn btn-outline-secondary">검색</button>
                        </div>
                    </form>
                    <div id="result">
                        <h6>
                            <?php
                                $sql2 = mysqli_query($connect, 'SELECT * FROM mileagehistory WHERE SID="'.$_COOKIE['UserSID'].'"') or die(mysqli_error($connect));
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
                                while ($row = mysqli_fetch_array($sql2)){
                                    echo "<tr>";
                                        echo "<td style='text-align:center;'>" . $k . "</td>";
                                        echo "<td style='text-align:center;'>" . $row[1] . "</td>";
                                        echo "<td style='text-align:center;'>" . $row[3] . "</td>";
                                        echo "<td>" . $row[4] . "</td>";
                                        if($row[6] == 0){
                                        echo "<td style='text-align:center; color:green; font-weight:700;'>". $row[5] . "</td>";
                                        }
                                        else {
                                        echo "<td style='text-align:center; color:red; font-weight:700;'>". $row[6] . "</td>";
                                        }
                                        echo "<td style='text-align:center;'>" . $row[7] . " 선생님</td>";
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
        <script>
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
        </script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>
        <script src="lib/jquery.counterup.js"></script>
    </body>
</html>