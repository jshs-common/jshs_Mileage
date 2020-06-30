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

    $type = $_POST['type1'];

    $description1 = $_POST['description1'];

    if($description1 == ""){
        $description = $_POST['description2'];
    }
    else{
        $description = $description1;
    }

    $point = $_REQUEST["point"];

    
    $pointsum = array_sum($point);

    if($pointsum == 0){
        echo "
        <script> 
            alert('점수가 선택되지 않았습니다.'); document.location.href='teacher-mileage.php' ;
        </script>
        ";
        exit;
    }

    $plusstudentlist = $_POST['plusstudentlist'];

    if($plusstudentlist == ""){
        echo "
        <script> 
            alert('학생이 선택되지 않았습니다.'); document.location.href='teacher-mileage.php';
        </script>
        ";
        exit;
    }

    $plusstudentarray = explode(",", $plusstudentlist); 
    $cnt = count($plusstudentarray);

    date_default_timezone_set("Asia/Seoul");
    $date = date("Y-m-d H:i:s");
    $usernamesplited = explode("(", $username); 

    for($i = 0 ; $i < $cnt ; $i++){
        if($plusstudentarray[$i] == ""){
            continue;
        }
        $data = mysqli_query($connect, "SELECT SID FROM user WHERE ID ='".$plusstudentarray[$i]."'") or die(mysqli_error($connect));
        $row = mysqli_fetch_array($data);
        $insert = mysqli_query($connect, "INSERT INTO mileagehistory (SID, Checkdate, period, Isminus, type, description, plus, minus, teachername) VALUES (".$row[0].", '".$date."', 1, 0, '".$type."', '".$description."', ".$pointsum.", 0, '".$usernamesplited[0]."')") or die(mysqli_error($connect));

        $road = mysqli_query($connect, "SELECT plus FROM totalmileage WHERE SID =".$row[0]) or die(mysqli_error($connect));
        $row2 = mysqli_fetch_array($road);
        $totalpoint = $row2[0]+$pointsum;
        $update = mysqli_query($connect, "UPDATE totalmileage SET plus = ".$totalpoint." WHERE SID =".$row[0]) or die(mysqli_error($connect));
    }

    echo "
    <script> 
        alert('성공적으로 상점 부여가 완료되었습니다. 상점 부여 후에도 상점 수치가 바뀌지 않는다면 3학년 김채린 학생을 불러주세요.'); document.location.href='teacher-mileage.php';
    </script>
    ";


?>
    





