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

    $ApplyID = mysqli_query($connect, 'SELECT ApplyID FROM applystudents where SID = '.$SID) or die(mysqli_error($connect));

    $connect->query('delete from apply where ApplyID = any(select ApplyID from applystudents where SID = '.$SID.');'); 

    $row = mysqli_fetch_array($ApplyID);

    $date = date("Y-m-d");

    $dap = $date.'-'.$row[0];


    $update = mysqli_query($connect, 'UPDATE applyhistory SET cancel = 1 WHERE Dap = "'.$dap.'"');

    echo "<meta http-equiv='refresh' content='0;url=student.php'>";
?>