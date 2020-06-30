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
    </head>
    <body>
    <?php
    include $_SERVER["DOCUMENT_ROOT"].'/scripts/navbar.php';
    EchoNavBar();

    $connect = DBConnect();
    if($_COOKIE['UserSID'] >= 300){
        Header("Location:경로/파일명"); 
    }
    if(IsCookieSet() && CookieLogin($connect))
    {
        $username = $_COOKIE['UserName'];
        if($_COOKIE['UserSID'] >= 300){
            Header("Location:choose-teacher.php"); 
        }
    }
    else
    {
      error(2);
      exit;
    }
    ?>
    
    <style>
        @import url('https://fonts.googleapis.com/css?family=Jua&display=swap');
        .table1{
            font-size : 30px ; color : #000; font-family: 'Jua', sans-serif;
            display:inline-block; margin-right: 3%;
        }
    </style>

        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                <h1 class="table1">👑 총 학년 랭킹</h1>상위 10명 상점만 집계된 수치입니다.
                    <table class="table" >
                        <colgroup>
                            <col style="width: 40px">
                            <col style="width: 50px">
                            <col style="width: 300px">
                            <col style="width: 60px">
                        </colgroup>
                        <thead class="thead-dark">
                            <tr>
                                <th style="text-align:center;" scope="col">순위</th>
                                <th scope="col"></th>
                                <th scope="col">Student</th>
                                <th style="text-align:center;" scope="col">상점</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $rankingsql5 = mysqli_query($connect, "SELECT @rank:=@rank+1 AS rank, sub.ID, sub.plus FROM (SELECT @rank:=0) AS rank, (select ID, plus FROM totalmileage) AS sub order by sub.plus DESC,sub.ID ASC LIMIT 10");

                            $pict = array("img/gold.png", "img/silver.png", "img/bronze.png");
                            $rank = 1;
                            $beforescore = 0;
                            $beforerank = 0;


                            while ($row = mysqli_fetch_array($rankingsql5)){
                                if($beforescore == $row[2]){
                                    $rank = $beforerank;
                                }
                                else{
                                    $beforerank = $beforerank+1;
                                }
                                $beforescore = $row[2];
                                if($row[0] == $rank){
                                    if($rank == 1 || $rank == 2 || $rank == 3){
                                        echo "<tr>
                                        <th style='text-align:center;' scope='row'>".$rank."</th>
                                            <td style='text-align:center;'><img src=".$pict[$rank-1]." width= 20 height= 20 ></td>
                                            <td >".$row[1]."
                                            <div class = s><p>".$row[2]."/X/X</p></div>
                                            </td>
                                            <td style='text-align:center;'>$row[2]</td>
                                         </tr>";
                                    }
                                    else{
                                        echo "<tr>
                                        <th style='text-align:center;' scope='row'>".$rank."</th>
                                            <td style='text-align:center;'></td>
                                            <td >".$row[1]."
                                            <div class = s><p>".$row[2]."/X/X</p></div>
                                            </td>
                                            <td style='text-align:center;'>$row[2]</td>
                                         </tr>";
                                    }
                                }
                                else{
                                    if($beforerank == 1 || $beforerank == 2 || $beforerank == 3){
                                        echo "<tr>
                                        <th style='text-align:center;' scope='row'>".$beforerank."</th>
                                            <td style='text-align:center;'><img src=".$pict[$beforerank-1]." width= 20 height= 20 ></td>
                                            <td >".$row[1]."
                                            <div class = s><p>".$row[2]."/X/X</p></div>
                                            </td>
                                            <td style='text-align:center;'>$row[2]</td>
                                         </tr>";
                                    }
                                    else{
                                        echo "<tr>
                                        <th style='text-align:center;' scope='row'>".$beforerank."</th>
                                            <td style='text-align:center;'></td>
                                            <td >".$row[1]."
                                            <div class = s><p>".$row[2]."/X/X</p></div>
                                            </td>
                                            <td style='text-align:center;'>$row[2]</td>
                                         </tr>";
                                    }
                                }
                            }
                            ?>
                        <tbody>
                    </table>
                </div>
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-xs-12">
                            <h1 class="table1">☝ 1학년 랭킹</h1>상위 3명 상점만 집계된 수치입니다.
                            <table class="table" >
                                <colgroup>
                                    <col style="width: 40px">
                                    <col style="width: 50px">
                                    <col style="width: 300px">
                                    <col style="width: 60px">
                                </colgroup>
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="text-align:center;" scope="col">순위</th>
                                        <th scope="col"></th>
                                        <th scope="col">Student</th>
                                        <th style="text-align:center;" scope="col">상점</th>
                                    </tr>
                                </thead>
                            <tbody>
                        <?php 
                            $rankingsql = mysqli_query($connect, "SELECT @rank:=@rank+1 AS rank, sub.ID, sub.plus FROM (SELECT @rank:=0) AS rank, (select ID, plus FROM totalmileage WHERE Number >= 1000 && Number < 2000) AS sub order by sub.plus DESC,sub.ID ASC LIMIT 3");

                            $rank = 1;
                            $beforescore = 0;
                            $beforerank = 0;


                            while ($row = mysqli_fetch_array($rankingsql)){
                                if($beforescore == $row[2]){
                                    $rank = $beforerank;
                                }
                                else{
                                    $beforerank = $beforerank+1;
                                }
                                $beforescore = $row[2];
                                if($row[0] == $rank){
                                    if($rank == 1 || $rank == 2 || $rank == 3){
                                        echo "<tr>
                                        <th style='text-align:center;' scope='row'>".$rank."</th>
                                            <td style='text-align:center;'><img src=".$pict[$rank-1]." width= 20 height= 20 ></td>
                                            <td >".$row[1]."
                                            <div class = s><p>".$row[2]."/X/X</p></div>
                                            </td>
                                            <td style='text-align:center;'>$row[2]</td>
                                         </tr>";
                                    }
                                    else{
                                        echo "<tr>
                                        <th style='text-align:center;' scope='row'>".$rank."</th>
                                            <td style='text-align:center;'></td>
                                            <td >".$row[1]."
                                            <div class = s><p>".$row[2]."/X/X</p></div>
                                            </td>
                                            <td style='text-align:center;'>$row[2]</td>
                                         </tr>";
                                    }
                                }
                                else{
                                    if($beforerank == 1 || $beforerank == 2 || $beforerank == 3){
                                        echo "<tr>
                                        <th style='text-align:center;' scope='row'>".$beforerank."</th>
                                            <td style='text-align:center;'><img src=".$pict[$beforerank-1]." width= 20 height= 20 ></td>
                                            <td >".$row[1]."
                                            <div class = s><p>".$row[2]."/X/X</p></div>
                                            </td>
                                            <td style='text-align:center;'>$row[2]</td>
                                         </tr>";
                                    }
                                    else{
                                        echo "<tr>
                                        <th style='text-align:center;' scope='row'>".$beforerank."</th>
                                            <td style='text-align:center;'></td>
                                            <td >".$row[1]."
                                            <div class = s><p>".$row[2]."/X/X</p></div>
                                            </td>
                                            <td style='text-align:center;'>$row[2]</td>
                                         </tr>";
                                    }
                                }
                            }
                            ?>
                            <tbody>
                        </table>
                    </div>

<br>                <div class="col-xs-12">
                        <h1 class="table1">✌ 2학년 랭킹</h1>상위 3명 상점만 집계된 수치입니다.
                            <table class="table" >
                                <colgroup>
                                    <col style="width: 40px">
                                    <col style="width: 50px">
                                    <col style="width: 300px">
                                    <col style="width: 60px">
                                </colgroup>
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="text-align:center;" scope="col">순위</th>
                                        <th scope="col"></th>
                                        <th scope="col">Student</th>
                                        <th style="text-align:center;" scope="col">상점</th>
                                    </tr>
                                </thead>
                            <tbody>
                        <?php 
                            $rankingsql2 = mysqli_query($connect, "SELECT @rank:=@rank+1 AS rank, sub.ID, sub.plus FROM (SELECT @rank:=0) AS rank, (select ID, plus FROM totalmileage WHERE Number >= 2000 && Number < 3000) AS sub order by sub.plus DESC,sub.ID ASC LIMIT 3");

                            $rank = 1;
                            $beforescore = 0;
                            $beforerank = 0;


                            while ($row = mysqli_fetch_array($rankingsql2)){
                                if($beforescore == $row[2]){
                                    $rank = $beforerank;
                                }
                                else{
                                    $beforerank = $beforerank+1;
                                }
                                $beforescore = $row[2];
                                if($row[0] == $rank){
                                    if($rank == 1 || $rank == 2 || $rank == 3){
                                        echo "<tr>
                                        <th style='text-align:center;' scope='row'>".$rank."</th>
                                            <td style='text-align:center;'><img src=".$pict[$rank-1]." width= 20 height= 20 ></td>
                                            <td >".$row[1]."
                                            <div class = s><p>".$row[2]."/X/X</p></div>
                                            </td>
                                            <td style='text-align:center;'>$row[2]</td>
                                         </tr>";
                                    }
                                    else{
                                        echo "<tr>
                                        <th style='text-align:center;' scope='row'>".$rank."</th>
                                            <td style='text-align:center;'></td>
                                            <td >".$row[1]."
                                            <div class = s><p>".$row[2]."/X/X</p></div>
                                            </td>
                                            <td style='text-align:center;'>$row[2]</td>
                                         </tr>";
                                    }
                                }
                                else{
                                    if($beforerank == 1 || $beforerank == 2 || $beforerank == 3){
                                        echo "<tr>
                                        <th style='text-align:center;' scope='row'>".$beforerank."</th>
                                            <td style='text-align:center;'><img src=".$pict[$beforerank-1]." width= 20 height= 20 ></td>
                                            <td >".$row[1]."
                                            <div class = s><p>".$row[2]."/X/X</p></div>
                                            </td>
                                            <td style='text-align:center;'>$row[2]</td>
                                         </tr>";
                                    }
                                    else{
                                        echo "<tr>
                                        <th style='text-align:center;' scope='row'>".$beforerank."</th>
                                            <td style='text-align:center;'></td>
                                            <td >".$row[1]."
                                            <div class = s><p>".$row[2]."/X/X</p></div>
                                            </td>
                                            <td style='text-align:center;'>$row[2]</td>
                                         </tr>";
                                    }
                                }
                            }
                            ?>
                            <tbody>
                        </table>
                    </div>

<br>                <div class="col-xs-12">
                        <h1 class="table1">👌 3학년 랭킹</h1>상위 3명 상점만 집계된 수치입니다.
                            <table class="table" >
                                <colgroup>
                                    <col style="width: 40px">
                                    <col style="width: 50px">
                                    <col style="width: 300px">
                                    <col style="width: 60px">
                                </colgroup>
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="text-align:center;" scope="col">순위</th>
                                        <th scope="col"></th>
                                        <th scope="col">Student</th>
                                        <th style="text-align:center;" scope="col">상점</th>
                                    </tr>
                                </thead>
                            <tbody>
                        <?php 
                            $rankingsql3 = mysqli_query($connect, "SELECT @rank:=@rank+1 AS rank, sub.ID, sub.plus FROM (SELECT @rank:=0) AS rank, (select ID, plus FROM totalmileage WHERE Number >= 3000 && Number < 4000) AS sub order by sub.plus DESC,sub.ID ASC LIMIT 3");

                            $rank = 1;
                            $beforescore = 0;
                            $beforerank = 0;


                            while ($row = mysqli_fetch_array($rankingsql3)){
                                if($beforescore == $row[2]){
                                    $rank = $beforerank;
                                }
                                else{
                                    $beforerank = $beforerank+1;
                                }
                                $beforescore = $row[2];
                                if($row[0] == $rank){
                                    if($rank == 1 || $rank == 2 || $rank == 3){
                                        echo "<tr>
                                        <th style='text-align:center;' scope='row'>".$rank."</th>
                                            <td style='text-align:center;'><img src=".$pict[$rank-1]." width= 20 height= 20 ></td>
                                            <td >".$row[1]."
                                            <div class = s><p>".$row[2]."/X/X</p></div>
                                            </td>
                                            <td style='text-align:center;'>$row[2]</td>
                                         </tr>";
                                    }
                                    else{
                                        echo "<tr>
                                        <th style='text-align:center;' scope='row'>".$rank."</th>
                                            <td style='text-align:center;'></td>
                                            <td >".$row[1]."
                                            <div class = s><p>".$row[2]."/X/X</p></div>
                                            </td>
                                            <td style='text-align:center;'>$row[2]</td>
                                         </tr>";
                                    }
                                }
                                else{
                                    if($beforerank == 1 || $beforerank == 2 || $beforerank == 3){
                                        echo "<tr>
                                        <th style='text-align:center;' scope='row'>".$beforerank."</th>
                                            <td style='text-align:center;'><img src=".$pict[$beforerank-1]." width= 20 height= 20 ></td>
                                            <td >".$row[1]."
                                            <div class = s><p>".$row[2]."/X/X</p></div>
                                            </td>
                                            <td style='text-align:center;'>$row[2]</td>
                                         </tr>";
                                    }
                                    else{
                                        echo "<tr>
                                        <th style='text-align:center;' scope='row'>".$beforerank."</th>
                                            <td style='text-align:center;'></td>
                                            <td >".$row[1]."
                                            <div class = s><p>".$row[2]."/X/X</p></div>
                                            </td>
                                            <td style='text-align:center;'>$row[2]</td>
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