<!DOCTYPE html>
<html>
<?php
  require $_SERVER["DOCUMENT_ROOT"].'/scripts/cookies.php';
  require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';

  $CurrentDate = date("Y-m-d",strtotime ("+2 hours"));
  $connect = DBConnect();
  $DBDate = $connect->query("select Dates from today;")->fetch_array()['Dates'];
  if($CurrentDate != $DBDate){
    $connect->query("update today set Dates = '".$CurrentDate."';");
    $connect->query("delete from apply;");
    $connect->query("alter table apply auto_increment=1;");
    $connect->query("update teachers set Attendance = 0;");
  }


  if(IsCookieSet()) // 미리 로그인 된 쿠키 정보가 있으면 choose.php로 이동
  {
    echo "<meta http-equiv='refresh' content='0;url=choose.php'>";
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
  <style>
    h2{
      display:inline;
    }
  #re {
    -webkit-animation: rotation 5s infinite linear;
    width:2%;
    height:2%;
    margin-bottom:1%;
  }

  @-webkit-keyframes rotation {
      from {-webkit-transform: rotate(0deg);}
      to   {-webkit-transform: rotate(359deg);}
  }

  #icon{
    width:70%;
    height:70%;
    margin-top:10%;
    margin-bottom:10%;
  }
  @media (max-width: 576px){
            #icon{
              padding:10%;

            }
            #login_card{
              padding-bottom: 3%;
              margin-top: 10%;
            }
            h2{
              margin-top:10%;
            }
        }
  </style>
  <script type="text/javascript">
    window.onload  = function() {
      $('#info').load('information.php')
    }
    var auto_refresh = setInterval(
    function ()
    {
    $('#info').load('information.php');
    }, 10000); // 새로고침 시간 1000은 1초를 의미합니다.
    /*
    window.onload  = function() {
      $('#info2').load('information-laptop.php')
    }
    var auto_refresh = setInterval(
    function ()
    {
    $('#info2').load('information-laptop.php');
    }, 10000);*/
  </script>

  <?php
  include $_SERVER["DOCUMENT_ROOT"].'/scripts/navbar.php';
  EchoNavBar();
  ?>
  <div class="container-fluid body-content">
    <div class="row" style = "margin-bottom:20%;">
      <div id="pan" class="col-sm-9" style="padding-right:6%; padding-left:2%; padding-top:2%;">
        <h2 style="font-weight:700;">실시간 안내상황판(<?php echo $CurrentDate?>) </h2>
        <img id="re" src="img/re.png" alt="">
        10초마다 업데이트
        <div id="info">
        </div>
        <!--<div id="info2">
        </div>-->

      </div>
      <div class="col-sm-3" id="login_card">
        <center>
          <img id="icon" src="img/제곽마크.png" alt="" style="width:100%;">
          <div class="logo-capsule">
              <p>로그인</p>
          </div>
          칸에 입력이 안 될 경우 새로고침
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

            <div class="submit-button" id="loginbutton">
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
        </center>
      </div>
    </div>
  </div>

    <div class="logo-capsule">
      <p>[패치노트 톺아보기]</p>
    </div>
    <div class="alert alert-info" role="alert" style="margin-top: 40px">
      <span style="font-size: 24px; font-weight: bold;">PatchNote 6.26</span>
      <div style="font-size: 18px; margin-left: 14px; margin-top: 6px;">
        <div style="font-size: 24px; margin-left: -10px; margin-bottom:-14px;"><em>"선생님 페이지, 이제 더 쉽게!"</em></div><br>
        <div style="font-size: 24px; margin-left: -10px; margin-bottom:-14px;"><em>"노트북 페이지, 이제 더 쉽게!"</em></div><br>
        <div style="height: 7px;"></div>
        <strong>선생님 페이지를 주로 업데이트하였습니다. </strong><br>
        <strong>노트북 페이지는 꾸준히 업데이트되고 있습니다. </strong><br>
        <strong>설문조사 결과는 곧 업데이트됩니다. </strong><br>
        → 현재 건의사항은 기말고사 후에 패치될 가능성이 높습니다.
        <div style="height: 7px;"></div>
        <strong>건의 사항이 있거나 알 수 없는 오류 발생시 3학년 김채린 학생에게 전달해주세요.</strong><br>
      </div>
    </div>
    <div class="alert alert-info" role="alert" style="margin-top: 40px">
      <span style="font-size: 24px; font-weight: bold;">Big PatchNote 새학기 </span>
      <div style="font-size: 18px; margin-left: 14px; margin-top: 6px;">
        <div style="font-size: 24px; margin-left: -10px; margin-bottom:-14px;"><em >"제곽특별실이 제주과고 통합관리시스템으로 업그레이드 되었습니다!"</em></div><br>
        <div style="height: 7px;"></div>
        <strong>페이지를 접속만 해도 특별실 결과, 노트북 상황 등을 간단히 확인할 수 있습니다 </strong><br>
        <strong>상벌점 시스템, 노트북 이용 대장 기능이 추가되었습니다.</strong><br>
        → (6.26)거의 업데이트 완료되었습니다.<br>
        <strong>웹 페이지 취약점을 찾아 보고서를 작성하였습니다. (취약점은 해결중에 있습니다) </strong><br>
        → 후에 취약점 보고서를 패치노트에 첨부할 예정입니다.
        <div style="height: 7px;"></div>
      </div>
    </div>

    <hr/>
    <div style="font-size: 18px; margin-bottom: 20px;">&nbsp;&nbsp;
      <a href="슬기로운제곽생활.com 사용가이드(학생용)_1.0.pdf" target="_blank" style="margin-right: 10px;">학생용 가이드 보기</a>&nbsp;&nbsp;
      <a href="슬기로운제곽생활.com 사용 가이드(선생님용).pdf" target="_blank" style="margin-right: 10px;">선생님용 가이드 보기</a>
    </div>
  </div>

  <?php require $_SERVER["DOCUMENT_ROOT"].'/scripts/ad.php'; EchoAd(); ?>
</body>


</html>
