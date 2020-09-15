'use strict';

const applicationServerPublicKey = 'BAZekKy451TtT2Ubvs3NGnQt6IWOriAwE77334yziqK4cxcpSJrTF5WAFOaluYlbG-U9WE0YWQTHMXSEBzTGnkg';

let swRegistration = null;

var pushOn = document.getElementById('PushOn');
var pushOff = document.getElementById('PushOff');

function urlB64ToUint8Array(base64String) {
  const padding = '='.repeat((4 - base64String.length % 4) % 4);
  const base64 = (base64String + padding)
    .replace(/\-/g, '+')
    .replace(/_/g, '/');

  const rawData = window.atob(base64);
  const outputArray = new Uint8Array(rawData.length);

  for (let i = 0; i < rawData.length; ++i) {
    outputArray[i] = rawData.charCodeAt(i);
  }
  return outputArray;
}


if(getPushSupport()){
  navigator.serviceWorker.getRegistrations().then(
  function(swReg){
    if(swReg.length == 0 || swReg[0].active.state != 'activated') {
      registerServiceWorker(); // 서비스워터 비 등록시 서비스워커 등록
      pushOff.classList.add('hidden'); // 알림해제 버튼 비활성화
    }
    else {
      //console.log(swReg[0]);
      swRegistration = swReg[0];

      swRegistration.pushManager.getSubscription()
      .then(function(subscription) {
        var isSubscribed = !(subscription === null);
  
        if (isSubscribed) {
          pushOn.classList.add('hidden'); // 알람이 이미 등록됬을 시 알림설정 버튼 비활성화
        } else {
          pushOff.classList.add('hidden'); // 알람이 해제되어있을 시 알림해제 버튼 비활성화
        }
      });
    }
    
  });
}
else {  
  // 푸시 미 지원시 푸시 버튼 전체 숨김
  pushOn.classList.add('hidden');
  pushOff.classList.add('hidden'); 
}


function SetPush(SetStatus){
  if(SetStatus) {
    const applicationServerKey = urlB64ToUint8Array(applicationServerPublicKey);
    swRegistration.pushManager.subscribe({
      userVisibleOnly: true,
      applicationServerKey: applicationServerKey // Push 등록
    })
    .then(function(subscription) {
      //console.log('User is subscribed.');
        var json = JSON.stringify(subscription); // EndPoint Json 추출
        UpdateEndPoint(1, json);
    })
    .catch(function(err) {
      alert('정상적으로 처리되지 못했습니다');
      return;
    });
  }
  else {
    swRegistration.pushManager.getSubscription()
    .then(function(subscription) {
      if (subscription) {
        subscription.unsubscribe();
        UpdateEndPoint(0, 'blank');
      }
    })
    .catch(function(error) {
      alert('정상적으로 처리되지 못했습니다');
      return;
    })
  }
}

function UpdateEndPoint(status, json) {
  var form = document.createElement('form');
  form.setAttribute('charset', 'UTF-8');
  form.setAttribute('method', 'Post');
  form.setAttribute('action', 'apply-push.php');
  
  var hiddenField = document.createElement('input');
  hiddenField.setAttribute('type', 'hidden');
  hiddenField.setAttribute('name', 'SetStatus');
  hiddenField.setAttribute('value', status);
  form.appendChild(hiddenField);

  var hiddenField = document.createElement('input');
  hiddenField.setAttribute('type', 'hidden');
  hiddenField.setAttribute('name', 'Json');
  hiddenField.setAttribute('value', json);
  form.appendChild(hiddenField);

  document.body.appendChild(form);
  form.submit();
}


function getPushSupport()
{
  if ('serviceWorker' in navigator && 'PushManager' in window) {
    return true;
  }
  return false;
}

function registerServiceWorker()
{
  navigator.serviceWorker.register('lib/serviceWorker/sw.js')
  .then(function(swReg) {
    //console.log('Service Worker is registered', swReg);
    swRegistration = swReg;
    return true;
  })
  .catch(function(error) {
    //console.error('Service Worker Error', error);
    return false;
  });
}