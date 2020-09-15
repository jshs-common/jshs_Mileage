<?php
 require $_SERVER["DOCUMENT_ROOT"].'/scripts/cookies.php';
 require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';


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

 $title = $_GET['title'];
 $nop = $_GET['num'];
 $score = $_GET['score'];

 if($title == ""){
  echo "
  <script> 
      alert('제목을 입력하지 않았습니다.'); document.location.href='makenotice.php';
  </script>
  ";
  exit;
  }

  if($nop == 0){
    echo "
    <script> 
        alert('인원수가 선택되지 않았습니다.'); document.location.href='makenotice.php';
    </script>
    ";
    exit;
  }

  if($score == 0){
    echo "
    <script> 
        alert('점수가 선택되지 않았습니다.'); document.location.href='makenotice.php';
    </script>
    ";
    exit;
  }

  date_default_timezone_set("Asia/Seoul");
  $date = date("Y-m-d H:i:s");

  $insert = mysqli_query($connect, "INSERT INTO mileagenotice (noticedate, title, nop, score, students, studentnum, teachername, isend) VALUES ('".$date."', '".$title."', '".$nop."', '".$score."', '', 0, '".$username."', 0)") or die(mysqli_error($connect));


  echo "
  <script> 
      alert('성공적으로 공고가 완료되었습니다. 학생들이 선착순으로 신청한 뒤, [신청자 확인 및 상점 부여] 탭으로 접속하셔서 신청 학생을 확인해주세요.'); document.location.href='noticecheck.php';
  </script>
  ";



?>