<?php
  require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';
  require $_SERVER["DOCUMENT_ROOT"].'/scripts/cookies.php';
  require $_SERVER["DOCUMENT_ROOT"].'/scripts/push.php';

  $SID = $_COOKIE['UserSID'];
  $connect = DBConnect();
  if(CookieLogin($connect) && isset($_POST['SetStatus']))
  {
    //echo $_POST['SetStatus']; 
    if((int)$_POST['SetStatus'])
    {
      //echo $_POST['Json'];
      if(isset($_POST['Json']))
      {
        $endpointJSON = $_POST['Json'];
        RegisterPush($connect, $SID, $endpointJSON);
      }
    }
    else
    {
      DeRegisterPush($connect, $SID);
    }
  }
  echo "<script>document.location.href='login.php';</script>";
  exit;

?>