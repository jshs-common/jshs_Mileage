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
    $SubjNum = $connect->query("select SubjNum from apply_laptop where SID = ".$SID)->fetch_array()['SubjNum'];
    $connect->query('delete from apply_laptop where SID = '.$SID.';');
    $connect->query("update laptop_list set borrow=0 where subjnum=".$SubjNum.";");
    echo "<meta http-equiv='refresh' content='0;url=choose.php'>";
?>