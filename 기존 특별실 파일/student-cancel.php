<?php
    require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';
    require $_SERVER["DOCUMENT_ROOT"].'/scripts/cookies.php';

    $connect = DBConnect();
    if(IsCookieSet() && CookieLogin($connect))
    {
        $SID = $_COOKIE['UserSID'];
    }
    else
    {
        error(2);
        exit;
    }

    $connect->query('delete from apply where ApplyID = (select ApplyID from applystudents where SID = '.$SID.');');
    echo "<meta http-equiv='refresh' content='0;url=student.php'>";
?>