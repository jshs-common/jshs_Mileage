<!DOCTYPE html>
<html>
    <head>
    <?php
    require $_SERVER["DOCUMENT_ROOT"].'/scripts/cookies.php';
    require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';
    ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>노트북 신청현황 및 반납</title>

    <script src="lib/jquery/jquery-3.3.1.min.js"></script>
    <script src="lib/bootstrap/dist/js/bootstrap.min.js"></script>
    <link href="lib/bootstrap/dist/css/bootstrap.css" rel="stylesheet" />

    <link href="css/site.css" rel="stylesheet" />

    <link href="css/navbar.css" rel="stylesheet" />
    <link href="css/fonts.css" rel="stylesheet" />
    <link href="css/mileage.css" rel="stylesheet" />
    <link href="css/student.css" rel="stylesheet" />

    </head>
    <style>
         button {border:0 none; background-color:transparent; cursor:pointer;}
        #addplus{
            width: 2em !important;
            height: 2em !important;
            background: url("img/addplus.png") center/2em no-repeat;
        }
        #addplus:hover{
            width: 2em !important;
            height: 2em !important;
            background: url("img/addplus-hover.png") center/2em no-repeat;
        }
        #delplus{
            width: 0.6em !important;
            height: 0.6em !important;
            background: url("img/delplus.png") center/0.6em no-repeat;
            margin-left:10%;
        }
        #delplus:hover{
            width: 0.6em !important;
            height: 0.6em !important;
            background: url("img/delplus-hover.png") center/0.6em no-repeat;
            margin-left:10%;
        }
        .pluslist{
            display:inline-block;
            width:40%;
            padding-left:2%;
            padding-right:2%;
            margin-bottom:2%;
            margin-left:2%;
            margin-right:2%;
            background-color: #D2E5A8;
        }
       .remark-column{
            width:110px;
        }
    </style>

    <body>
    <?php
    include $_SERVER["DOCUMENT_ROOT"].'/scripts/navbar.php';
    EchoNavBar();

    $connect = DBConnect();
    if(IsCookieSet() && CookieLogin($connect))
    {
        $username = $_COOKIE['UserName'];
        $SID = $_COOKIE['UserSID'];
        $subject = $_GET["subject"];
        $num = $_GET["num"];
    }
    else
    {
      error(2);
      exit;
    }

    if(IsCookieSet() && CookieLogin($connect))
    {
        $SID = $_COOKIE['UserSID'];

        $row = $connect->query("select * from apply_laptop where SID=".$SID.";")->fetch_array();
        if(isset($row))
        {
          echo "<meta http-equiv='refresh' content='0;url=laptop-waiting.php'>"; // 신청이 안되어 있을 시 choose.php로 전송
          exit;
        }
    }
    ?>
    <script>
        $(document).ready(function() {
            $('.counter1').counterUp();
            $('.counter2').counterUp();
            $('.counter3').counterUp();
        });

    </script>
        <div class="container">
            <div class="row" style="margin-top:2%;">
                <div class="col-xs-2" style=" text-align:center;" id="move">
                    <img src="img/notebook.png" style="height:55%; width:55%;" alt="">
                </div>
                <div class="col-xs-10 col-sm-6">
                    <h1>노트북 대여현황 및 반납</h1>
                </div>
            </div>
            <div class="row" style="margin-top:2%;">
                <div class="col-sm-offset-0 col-sm-4"  id="box1">
                    <h3>노트북 대여현황</h3>
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


                        $date = $row['BorDay']."<br>~".$row['RetDay'];

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
                </div>
                <div class="col-sm-offset-1 col-sm-7" style="max-height:60vh;" id="box2">
                    <h3>노트북 반납 확인</h3>
                        <form method="get" action="">
                        <div class="function" style="background-color:#EAEAEA;">
                        &nbsp;&nbsp;과목
                            <body>
                                <select class="custom-select" name="subject" id="subject" onchange="choice()" style="margin-right:0.5%;">
                                    <option value="all">:::전체:::</option>
                                    <option value="physics"
                                    <?php if($subject == "physics"){
                                        echo "selected";
                                    } ?>>물리</option>
                                    <option value="chemistry"
                                    <?php if($subject == "chemistry"){
                                        echo "selected";
                                    } ?>>화학</option>
                                    <option value="life"
                                    <?php if($subject == "life"){
                                        echo "selected";
                                    } ?>>생명과학</option>
                                    <option value="earth"
                                    <?php if($subject == "earth"){
                                        echo "selected";
                                    } ?>>지구과학</option>
                                    <option value="math"
                                    <?php if($subject == "math"){
                                        echo "selected";
                                    } ?>>수학</option>
                                    <option value="information"
                                    <?php if($subject == "information"){
                                        echo "selected";
                                    } ?>>정보</option>
                                </select>
                                노트북 번호
                                <select class="custom-select" name="num" id="num" style="margin-right:0.5%;">
                                    <option value="all">:::전체:::</option>
                                </select>
                            </body>

                            <?php
                            $subj = array('physics', 'chemistry', 'life', 'earth', 'math', 'information');
                            $phy = array();
                            $che = array();
                            $bio = array();
                            $geo = array();
                            $math = array();
                            $god = array();
                            for($i = 0; $i<6; $i++){
                                $search = mysqli_query($connect, "SELECT num FROM laptop_list WHERE subj = '".$subj[$i]."';") or die(mysqli_error($connect));
                                switch($i){
                                    case 0:
                                        while($r = mysqli_fetch_array($search))
                                            array_push($phy, $r[0]);
                                        break;
                                    case 1:
                                        while($r = mysqli_fetch_array($search))
                                            array_push($che, $r[0]);
                                        break;
                                    case 2:
                                        while($r = mysqli_fetch_array($search))
                                            array_push($bio, $r[0]);
                                        break;
                                    case 3:
                                        while($r = mysqli_fetch_array($search))
                                            array_push($geo, $r[0]);
                                        break;
                                    case 4:
                                        while($r = mysqli_fetch_array($search))
                                            array_push($math, $r[0]);
                                        break;
                                    case 5:
                                        while($r = mysqli_fetch_array($search))
                                            array_push($god, $r[0]);
                                        break;
                                }
                            }
                            ?>


                            <script>
                                function choice(){
                                    var subject = document.getElementById("subject");
                                    var num = document.getElementById("num");


                                    // 각 과목별 노트북 번호. 노트북에 변화가 있을 시 변경 및 db 확인해 주세요 db연동으로 값 바뀌게 해도 좋음.

                                    var phy = [2,3,4,5,6];
                                    var che = [1,2,3,4,5,6];
                                    var bio = [1,2,3,4];
                                    var geo = [1,2,3,4];
                                    var math = [1,2,3,4,5,6,7,8,9,10,11];
                                    var god = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22];


                                    //var phy = <?php //echo json_encode($phy);?>;
                                    /*var che = <?php //echo jsin_encode($che);?>;
                                    var bio = <?php //echo json_encode($bio);?>;
                                    var geo = <?php //echo jsin_encode($geo);?>;
                                    var math = <?php //echo json_encode($math);?>;
                                    var god = <?php //echo jsin_encode($god);?>;*/

                                    // 과목의 선택된 값이 바뀔 때마다 번호에 값이 쌓이지 않게 하기(오류 방지)
                                    while(num.length > 0){
                                        num.remove(0);
                                        num.length--;
                                    }//while

                                    // 선택된 과목에 따른 각 번호 옵션들을 동적으로 추가시키기
                                    switch(subject.value){
                                        case 'all' :
                                        var option = document.createElement("option");
                                        option.innerHTML = ":::전체:::";
                                        option.value = "all";
                                        num.appendChild(option);
                                    case 'physics' :
                                        var option = document.createElement("option");
                                        option.innerHTML = ":::전체:::";
                                        option.value = "all";
                                        num.appendChild(option);
                                        for(var i = 0; i < phy.length; i++){
                                            // body에 들어갈 <option>태그 새로 생성하기
                                            var option = document.createElement("option");
                                            // 생성된 옵션에 표시될 글자를 HTML로 해당 배열 값 넣기 <option> 220 </option>
                                            option.innerHTML = phy[i];
                                            // 해당 옵션의 value값 같이 생성하기 : <option value="220"> 220 </option>
                                            option.value = phy[i];
                                            // 옵션을 size라는 select태그 안에 넣기
                                            num.appendChild(option);
                                        }
                                        break;
                                    case 'chemistry' :
                                        var option = document.createElement("option");
                                        option.innerHTML = ":::전체:::";
                                        option.value = "all";
                                        num.appendChild(option);
                                        for(var i = 0; i < che.length; i++){
                                            var option = document.createElement("option");
                                            option.innerHTML = che[i];
                                            option.value = che[i];
                                            num.appendChild(option);
                                        }
                                        break;
                                    case 'life' :
                                        var option = document.createElement("option");
                                        option.innerHTML = ":::전체:::";
                                        option.value = "all";
                                        num.appendChild(option);
                                        for(var i = 0; i < bio.length; i++){
                                            var option = document.createElement("option");
                                            option.innerHTML = bio[i];
                                            option.value = bio[i];
                                            num.appendChild(option);
                                        }
                                        break;
                                    case 'earth' :
                                        var option = document.createElement("option");
                                        option.innerHTML = ":::전체:::";
                                        option.value = "all";
                                        num.appendChild(option);
                                        for(var i = 0; i < geo.length; i++){
                                            var option = document.createElement("option");
                                            option.innerHTML = geo[i];
                                            option.value = geo[i];
                                            num.appendChild(option);
                                        }
                                        break;
                                    case 'math' :
                                        var option = document.createElement("option");
                                        option.innerHTML = ":::전체:::";
                                        option.value = "all";
                                        num.appendChild(option);
                                        for(var i = 0; i < math.length; i++){
                                            var option = document.createElement("option");
                                            option.innerHTML = math[i];
                                            option.value = math[i];
                                            num.appendChild(option);
                                        }
                                        break;
                                    case 'information' :
                                        var option = document.createElement("option");
                                        option.innerHTML = ":::전체:::";
                                        option.value = "all";
                                        num.appendChild(option);
                                        for(var i = 0; i < god.length; i++){
                                            var option = document.createElement("option");
                                            option.innerHTML = god[i];
                                            option.value = god[i];
                                            num.appendChild(option);
                                        }
                                        break;
                                    }//switch
                                }
                                </script>

                            <button type="submit" id="search" class="btn btn-outline-secondary" style="background-color:gray; color:black float:right;">검색</button>
                        </div>
                    </form>
                    <div id="result" style="max-height:40vh; overflow-x:hidden; overflow-y:scroll;">
                        <h6>
                            <?php
                                if($subject == "all"){
                                    $query = "SELECT * FROM laptop_list";
                                }
                                elseif($num == "all"){
                                    $query = "SELECT * FROM laptop_list WHERE subj = '".$subject."'";
                                }
                                else{
                                    $query = "SELECT * FROM laptop_list WHERE subj = '".$subject."' AND num = ".$num;
                                }
                                $result = mysqli_query($connect, $query);

                                echo "<table class='table table-striped table-bordered table-hover, display:inline-block'>
                                    <tr>
                                        <th style='text-align:center;'>과목</th>
                                        <th style='text-align:center;'>노트북 번호</th>
                                        <th style='text-align:center;'>고장여부</th>
                                        <th style='text-align:center;'>반납</th>
                                        <th class='remark-column'; style='text-align:center;'>내용물 분실 여부</th>
                                    </tr>";

                                while($row = mysqli_fetch_array($result)){
                                    switch ($row[0]) {
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
                                        default:
                                            $subj_kr="정보";
                                            break;
                                    }
                                    echo "<tr>";
                                    echo "<td style='text-align:center;'>" . $subj_kr . "</td>";
                                    echo "<td style='text-align:center;'>" . $row[1] . "</td>";
                                    if($row[2] == 1){
                                        echo "<td style='text-align:center; color:red;'>고장</td>";
                                        echo "<td style='text-align:center; color:red;'>고장</td>";
                                    }
                                    else{
                                        echo "<td style='text-align:center; color:green;'>이상없음</td>";
                                        if($row[3] == 2){
                                            echo "<td style='text-align:center; color:green;'>
                                            <form action='teacher-laptop-finish.php' method='post'>
                                            <input type='hidden' name='SubjNum' value='".$row[4]."'>
                                            <input type='hidden' name='borrow' value='".$row[3]."'>
                                            <button type='submit' class='btn btn-success' style='font-size:14px; margin:0px; border:0px; padding:3px 15px;'>반납</button>
                                            </form>
                                            </td>";
                                        }
                                        elseif ($row[3] == 1) {
                                            echo "<td style='text-align:center; color:red;'>신청 승인 대기중</td>";
                                        }
                                        else{
                                            echo "<td style='text-align:center; color:gray;'>대여없음</td>";
                                        }
                                    }
                                    echo "<td style='text-align:center;'>-</td>";
                                    echo "</tr>";
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
