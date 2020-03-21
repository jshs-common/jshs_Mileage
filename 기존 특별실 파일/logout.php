<?php
  require $_SERVER["DOCUMENT_ROOT"].'/scripts/cookies.php';
  require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';
  require $_SERVER["DOCUMENT_ROOT"].'/scripts/push.php';

  $connect = DBConnect();
  $SID = $_COOKIE['UserSID'];
  $connect->query('update connection set token = null, expire = null where SID = "'.$SID.'"'); // 로그인 토큰 초기화
  DeRegisterPush($connect, $SID); // DB에 저장된 EndPoint 초기화
  ClearCookie();

  // 이하는 서비스 워커에 등록된 푸시 등록 해제
  echo "
  <script> 
    'use strict';
    
    let swRegistration = null;
    
    if('serviceWorker' in navigator && 'PushManager' in window){
      navigator.serviceWorker.getRegistrations().then(
      function(swReg){
        if(swReg.length > 0 && swReg[0].active.state == 'activated') {
          swRegistration = swReg[0];
    
          swRegistration.pushManager.getSubscription()
          .then(function(subscription) {
            if(!(subscription === null))subscription.unsubscribe();
          });
        }
        
      });
    }

    document.location.href='login.php';
  </script>
  ";
?>