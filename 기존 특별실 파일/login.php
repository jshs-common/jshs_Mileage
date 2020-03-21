<!DOCTYPE html>
<html>
<?php
  require $_SERVER["DOCUMENT_ROOT"].'/scripts/cookies.php';
  require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';

  $CurrentDate = date("Y-m-d",strtotime ("+9 hours"));
  $connect = DBConnect();
  $DBDate = $connect->query("select Dates from today;")->fetch_array()['Dates'];
  if($CurrentDate != $DBDate){
    $connect->query("update today set Dates = '".$CurrentDate."';");
    $connect->query("delete from apply;");
    $connect->query("alter table apply auto_increment=1;");
    $connect->query("update teachers set Attendance = 0;");
  }


  if(IsCookieSet()) // 미리 로그인 된 쿠키 정보가 있으면 usercheck.php로 이동
  {
    echo "<meta http-equiv='refresh' content='0;url=usercheck.php'>";
	  exit;
  }
?>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>로그인</title>

  <script src="lib/jquery/jquery-3.3.1.min.js"></script>
  </script>
  <script src="lib/bootstrap/dist/js/bootstrap.min.js"></script>

  <link href="lib/bootstrap/dist/css/bootstrap.css" rel="stylesheet" />
  <link href="css/site.css" rel="stylesheet" />

  <link href="css/navbar.css" rel="stylesheet" />
  <link href="css/fonts.css" rel="stylesheet" />

  <link href="css/loginform.css" rel="stylesheet" />

</head>

<body>

  <?php
  include $_SERVER["DOCUMENT_ROOT"].'/scripts/navbar.php';
  EchoNavBar();
  ?>
  <div class="container body-content">
    <div class="row">
      <center>
      <div class="login-card">
          <div class="arrow-back">
              <input type="image" src="img/arrow-left.svg" alt="뒤로가기" onclick="history.back()" />
          </div>

          <div class="logo-capsule">
              <p>로그인</p>
          </div>

          <form action="usercheck.php" method="post" enctype="multipart/form-data">
              <div class="group">
                  <input type="text" name="id" required />
                  <span class="bar"></span>
                  <label><span class="input-label">ID</span></label>
              </div>

              <div class="group">
                  <input type="password" name="pw" required />
                  <span class="bar"></span>
                  <label><span class="input-label">비밀번호</span></label>
              </div>

              <div class="submit-button">
                  <input type="submit" value="로그인" />
              </div>
          </form>
          <!--
          <div class="register">
              <a asp-controller="Account" asp-action="Register">
                  <label>아직 회원이 아니신가요?</label>
              </a>
          </div>
          -->
      </div>
      </center>
      <div class=""></div>
    </div>
    <div class="alert alert-info" role="alert" style="margin-top: 40px">
      <span style="font-size: 24px; font-weight: bold;">PatchNote 12.17</span>
      <div style="font-size: 18px; margin-left: 14px; margin-top: 6px;">
        <div style="font-size: 24px; margin-left: -10px; margin-bottom:-14px;"><em >"크리스마스 업데이트 + 웹 페이지 취약점 문제 해결"</em></div><br>
        <div style="height: 7px;"></div>
        <strong>크리스마스를 맞이하여 눈 업데이트가 추가되었습니다! </strong><br>
        <strong>웹 페이지 취약점을 찾아 보고서를 작성하였습니다. (취약점은 해결중에 있습니다) </strong><br>
        → 후에 취약점 보고서를 패치노트에 첨부할 예정입니다.
        <div style="height: 7px;"></div>
        <strong>건의 사항이 있거나 알 수 없는 오류 발생시 2학년 김채린 학생에게 전달해주세요.</strong><br>
      </div>
    </div>
    <div class="alert alert-info" role="alert" style="margin-top: 40px">
      <span style="font-size: 24px; font-weight: bold;">PatchNote 10.31</span>
      <div style="font-size: 18px; margin-left: 14px; margin-top: 6px;">
        <div style="font-size: 24px; margin-left: -10px; margin-bottom:-14px;"><em >"신청 전에도, 신청 후에도 실시간 확인 기능이 추가되었습니다!"</em></div><br>
        <div style="height: 7px;"></div>
        <strong>실시간으로 어떤 학생이 어느 장소에 신청했는지를 알 수 있습니다. </strong><br>
        → 디자인 개선, 신청 후에도 확인할 수 있는 기능을 추가할 예정입니다. (추가 완료)<br>
        <div style="height: 7px;"></div>
      </div>
    </div>
    </div>

    <hr/>
    <div style="font-size: 18px; margin-bottom: 20px;">&nbsp;&nbsp;
      <a href="사용가이드(학생용)_1.0.pdf" style="margin-right: 10px;">학생용 가이드 보기</a>&nbsp;&nbsp;
      <a href="사용가이드(선생님용)_1.0.pdf" style="margin-right: 10px;">선생님용 가이드 보기</a>
    </div>
  </div>

  <?php require $_SERVER["DOCUMENT_ROOT"].'/scripts/ad.php'; EchoAd(); ?>
</body>

</html>
