<?php
require $_SERVER["DOCUMENT_ROOT"].'/vendor/autoload.php';
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

function SendPush($connect, $TargetSID, $Message)
{
    $query = 'select EndpointJSON from push_endpoint where UserSID = '.$TargetSID;
    $endpointJSON = $connect->query($query)->fetch_array()['EndpointJSON'];

    //echo $endpointJSON;
    if(empty($endpointJSON))return false;
    $notification = [
        'subscription' => Subscription::create(json_decode($endpointJSON, true)),
        'payload' => $Message,
    ];  

    $auth = array(
        'VAPID' => array(
            'subject' => 'https://제곽특별실.tk/',
            'publicKey' => file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/keys/public_key.txt'), // don't forget that your public key also lives in app.js
            'privateKey' => file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/keys/private_key.txt'), // in the real world, this would be in a secret file
        ),
    );

    $webPush = new WebPush($auth);
    
    $sent = $webPush->sendNotification(
        $notification['subscription'],
        $notification['payload'], // optional (defaults null)
    );
    foreach ($webPush->flush() as $report) {
        $endpoint = $report->getRequest()->getUri()->__toString();
    
        if ($report->isSuccess()) {
            //echo "[v] Message sent successfully for subscription {$endpoint}.";
            return true;
        } else {
            //echo "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
            return false;
        }
    }
}

function RegisterPush($connect, $SID, $endpointJSON)
{
    $query = "update push_endpoint set EndpointJSON = '".$endpointJSON."' where UserSID = ".$SID;
    $connect->query($query);
    return;
}

function DeRegisterPush($connect, $SID)
{
    $query = 'update push_endpoint set EndpointJSON = null where UserSID = '.$SID;
    $connect->query($query);
    return;
}
?>