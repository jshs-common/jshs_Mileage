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

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />
    </head>
        <script type="text/javascript">

        </script>
    <body style="">
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
        <style>
            #totalscore{
                font-size:2em;
            }
            #rank{
                font-size:2em;
            }
        </style>
        <link href="css/sidebar.css" rel="stylesheet" />
        <div class="container-fluid">
            <div class="row" >
                <div class="col-xs-2" style="padding: 0 0 0 0;">
                <?php EchoSideBar(); ?>
                </div>
                <div class="col-sm-10" style="padding-left:2%; max-height:120vh; overflow-x:hidden; overflow-y:scroll;">
                <h2>상벌점 상위 학생 확인(전교생 상벌점 순위 현황)</h2> 파일이 필요하신 경우 상단의 '학생 상벌점 상황 출력' 버튼을 클릭해 다운받으세요!<br><br>
                <form method="get" action="">
                        <input name="SID" id="SID" type="hidden" value="<?php echo $SID?>">
                        <div class="function" style="background-color:#EAEAEA;">

                            <!-- 유형
                            <select class="custom-select" name="unit" id="unit" style="margin-right:8%;">
                                <option value="all">전체</option>
                            </select> -->
                        <div class="search" style="display:inline-block;">
                            <input name="searchbox" type="text" placeholder="검색어를 입력해주세요." value="">
                        </div>
                         <button type="submit" id="search" class="btn btn-outline-secondary" >검색</button>
                        </div>
                    </form>
                    <table class="table" >
                        <colgroup>
                            <col style="width: 20px">
                            <col style="width: 30px">
                            <col style="width: 100px">
                            <col style="width: 30px">
                        </colgroup>
                        <thead class="thead-dark">
                            <tr>
                                <th style="text-align:center;" scope="col">순위</th>
                                <th scope="col"></th>
                                <th scope="col">Student</th>
                                <th style="text-align:center;" scope="col">총점</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $rankingsql5 = mysqli_query($connect, "SELECT @rank:=@rank+1 AS rank, sub.ID, sub.plus, sub.minus, (sub.plus - sub.minus) AS total FROM (SELECT @rank:=0) AS rank, (select ID, plus, minus FROM totalmileage) AS sub order BY total DESC, sub.ID ASC");
                            $pict = array("img/gold.png", "img/silver.png", "img/bronze.png");
                            $rank = 1;
                            $beforescore = 0;
                            $beforerank = 0;
                            $first = 0;

                            while ($row = mysqli_fetch_array($rankingsql5)){
                                if($beforescore == $row[4]){
                                    $rank = $beforerank;
                                }
                                else{
                                    $beforerank = $beforerank+1;
                                }
                                $beforescore = $row[4];
                                if($rank == $beforerank){
                                    if($rank == 1 && $first == 0){
                                        echo "<tr>
                                        <th id='rank' style='text-align:center;' scope='row'>1</th>
                                            <td style='text-align:center;'><img src=".$pict[$rank-1]." width= 20 height= 20 ></td>
                                            <td >".$row[1]."
                                            <div class = s><p>".$row[2]."/".$row[3]."/".$row[4]."</p></div>
                                            </td>
                                            <td id='totalscore' style='text-align:center;'>$row[4]</td>
                                         </tr>";
                                         $first = 1;
                                    }
                                    else if($rank == 1 || $rank == 2 || $rank == 3){
                                        echo "<tr>
                                        <th id='rank' style='text-align:center;' scope='row'>-</th>
                                            <td style='text-align:center;'><img src=".$pict[$rank-1]." width= 20 height= 20 ></td>
                                            <td >".$row[1]."
                                            <div class = s><p>".$row[2]."/".$row[3]."/".$row[4]."</p></div>
                                            </td>
                                            <td id='totalscore' style='text-align:center;'>$row[4]</td>
                                         </tr>";
                                    }
                                    else{
                                        echo "<tr>
                                        <th id='rank' style='text-align:center;' scope='row'>-</th>
                                            <td style='text-align:center;'></td>
                                            <td >".$row[1]."
                                            <div class = s><p>".$row[2]."/".$row[3]."/".$row[4]."</p></div>
                                            </td>
                                            <td id='totalscore' style='text-align:center;'>$row[4]</td>
                                         </tr>";
                                    }
                                }
                                else{
                                    if($beforerank == 1 || $beforerank == 2 || $beforerank == 3){
                                        echo "<tr>
                                        <th id='rank' style='text-align:center;' scope='row'>".$beforerank."</th>
                                            <td style='text-align:center;'><img src=".$pict[$beforerank-1]." width= 20 height= 20 ></td>
                                            <td >".$row[1]."
                                            <div class = s><p>".$row[2]."/".$row[3]."/".$row[4]."</p></div>
                                            </td>
                                            <td id='totalscore' style='text-align:center;'>$row[4]</td>
                                         </tr>";
                                    }
                                    else{
                                        echo "<tr>
                                        <th id='rank' style='text-align:center;' scope='row'>".$beforerank."</th>
                                            <td style='text-align:center;'></td>
                                            <td >".$row[1]."
                                            <div class = s><p>".$row[2]."/".$row[3]."/".$row[4]."</p></div>
                                            </td>
                                            <td id='totalscore' style='text-align:center;'>$row[4]</td>
                                         </tr>";
                                    }
                                }
                            }
                            ?>
                                <tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>