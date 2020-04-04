<!DOCTYPE html>
<?php
  require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';
  require $_SERVER["DOCUMENT_ROOT"].'/scripts/cookies.php';
  $connect = DBConnect();
  if(CookieLogin($connect))
  {
    if(isset($_POST['lastpw']) && isset($_POST['newpw']) && isset($_POST['newpwcheck']))
    {
      $id = $_COOKIE['UserName'];
      $pw = $connect->query("select Password from user where ID = '".$id."';")->fetch_array()['Password'];
      $lastpw = $_POST['lastpw'];
      $newpw = $_POST['newpw'];
      $newpwcheck = $_POST['newpwcheck'];
      if(strcmp($pw, $lastpw))
      {
        echo "
        <script> 
          alert('기존 비밀번호를 잘못 입력하였습니다')
          document.location.href='changepw.php';
        </script>
        ";
      }
      else if(strcmp($newpw, $newpwcheck)){
        echo "
        <script> 
          alert('비밀번호 확인이 일치하지 않습니다')
          document.location.href='changepw.php';
        </script>
        ";
      }
      else {
        $connect->query("update user set Password='".$newpw."' where ID='".$id."';");
        echo "
        <script>
          alert('비밀번호가 성공적으로 변경되었습니다') 
          document.location.href='index.php';
        </script>
        ";
        exit;
      }
    }
  }
  else
  {
    //error(2);
    exit;
  }
?>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>로그인</title>

  <script src="lib/jquery/jquery-3.3.1.min.js"></script>
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
  EchoNavBar(1);
  ?>
  <div class="container body-content">
    <div class="row">
      <div class="col-lg-3 col-md-3"></div>
      <div class="col-sm-offset-2 col-sm-8 col-md-offset-4 col-md-4 login-card">
          <div class="arrow-back">
              <input type="image" src="img/arrow-left.svg" alt="뒤로가기" onclick="history.back()" />
          </div>

          <div class="logo-capsule">
              <p>비밀번호 변경</p>
          </div>

          <form action="changepw.php" method="post" enctype="multipart/form-data">
              <div class="group">
                  <input type="password" name="lastpw" required />
                  <span class="bar"></span>
                  <label><span class="input-label">기존 비밀번호</span></label>
              </div>

              <div class="group">
                  <input type="password" name="newpw" required />
                  <span class="bar"></span>
                  <label><span class="input-label">새로운 비밀번호</span></label>
              </div>
          
              <div class="group">
                   <input type="password" name="newpwcheck" required />
                   <span class="bar"></span>
                   <label><span class="input-label">비밀번호 확인</span></label>
              </div> 

              <div class="submit-button">
                  <input type="submit" value="확인" />
              </div>
          </form>
      </div>
      <div class=""></div>
    </div>
  </div>
    
  <?php require $_SERVER["DOCUMENT_ROOT"].'/scripts/ad.php'; EchoAd(); ?>
</body>
</html>