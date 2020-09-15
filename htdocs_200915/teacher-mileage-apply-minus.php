<?php
    require $_SERVER["DOCUMENT_ROOT"].'/scripts/cookies.php';
    require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';


    $connect = DBConnect();

    if($_COOKIE['UserSID'] >= 300){
        if(IsCookieSet() && CookieLogin($connect)){
          $SID = $_COOKIE['UserSID'];
          $username = $_COOKIE['UserName'];
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

    $type = $_POST['type2'];

    $description1 = $_POST['description1'];

    if($description1 == ""){
        $description = $_POST['descriptionmi']." (사유 :".$_POST['descript2'].")";
    }
    else{
        $description = $description1;
    }

    $point = $_REQUEST["point"];
    
    $point = array_sum($point);

    if($point == 0){
        echo "
        <script> 
            alert('점수가 선택되지 않았습니다.'); document.location.href='teacher-mileage.php';
        </script>
        ";
        exit;
    }

    $minusstudentlist = $_POST['minusstudentlist'];

    if($minusstudentlist == ""){
        echo "
        <script> 
            alert('학생이 선택되지 않았습니다.'); document.location.href='teacher-mileage.php';
        </script>
        ";
        exit;
    }

    $minusstudentarray = explode(",", $minusstudentlist); 
    $cnt = count($minusstudentarray);

    date_default_timezone_set("Asia/Seoul");
    $date = date("Y-m-d H:i:s");
    $usernamesplited = explode("(", $username); 

    for($i = 0 ; $i < $cnt ; $i++){
        if($minusstudentarray[$i] == ""){
            continue;
        }
        $data = mysqli_query($connect, "SELECT SID FROM user WHERE ID ='".$minusstudentarray[$i]."'") or die(mysqli_error($connect));
        $row = mysqli_fetch_array($data);
        $insert = mysqli_query($connect, "INSERT INTO mileagehistory (SID, Checkdate, period, Isminus, type, description, plus, minus, teachername) VALUES (".$row[0].", '".$date."', 2, 1, '".$type."', '".$description."', 0, ".$point.", '".$usernamesplited[0]."')") or die(mysqli_error($connect));
        $insert = mysqli_query($connect, "INSERT INTO mileagehistory_real (SID, Checkdate, period, Isminus, type, description, plus, minus, teachername) VALUES (".$row[0].", '".$date."', 2, 1, '".$type."', '".$description."', 0, ".$point.", '".$usernamesplited[0]."')") or die(mysqli_error($connect));

        $road = mysqli_query($connect, "SELECT minus FROM totalmileage WHERE SID =".$row[0]) or die(mysqli_error($connect));
        $row2 = mysqli_fetch_array($road);
        $totalpoint = $row2[0]+$point;
        $update = mysqli_query($connect, "UPDATE totalmileage SET minus = ".$totalpoint." WHERE SID =".$row[0]) or die(mysqli_error($connect));
    }

    echo "
    <script> 
        alert('성공적으로 벌점 부여가 완료되었습니다. 벌점 부여 후에도 벌점 수치가 바뀌지 않는다면 3학년 김채린 학생을 불러주세요.'); document.location.href='teacher-mileage.php';
    </script>
    ";


?>
    