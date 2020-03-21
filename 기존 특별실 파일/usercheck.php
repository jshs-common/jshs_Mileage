<?php
require $_SERVER["DOCUMENT_ROOT"].'/scripts/cookies.php';
require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';

if(isset($_POST['id']) && isset($_POST['pw'])){
    ClearCookie();
    $id = $_POST['id'];
    $pw = $_POST['pw'];

    $connect = DBConnect();
    $row = $connect->query("select * from user where ID='".$id."';")->fetch_array();
    $sid = $row['SID'];
    $username = $row['UserName'];
    if(isset($row) && !strcmp($row['Password'], $pw))
    {
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        while( isset( $connect->query("select * from connection where token='".$token."';")->fetch_array()['token'] ) )
        {
            $token =  bin2hex(openssl_random_pseudo_bytes(16));
        }
        $result = $connect->query(
            "update connection set token='".$token."', expire='".date("Y-m-d H:i:s", strtotime("+1months"))."' where SID=".$sid.";"
        );
        setcookie('UserSID', $sid, strtotime('+1months'), '/');
        setcookie('UserName', $username, strtotime('+1months'), '/');
        setcookie('token', $token, strtotime('+1months'), '/');
    }
    else
    {
        error(1); // 아이디 or 비밀번호 오류
        exit;
    }
}
else if(IsCookieSet())
{
    $connect = DBConnect();
    if(!CookieLogin($connect))
    {
        error(0); // 토큰 로그인 실패 시 경고없이 로그인 창으로 돌아감
        exit;
    }
    $sid = $_COOKIE['UserSID'];
}
else
{
    error(2); // 비정상적인 접근 차단
    exit;
}
$row = $connect->query("select IsTeacher from user where SID='".$sid."';")->fetch_array();
if(isset($row))
{
    if($row['IsTeacher'])
    {
        echo "
        <script> 
            document.location.href='teacher.php';
        </script>
        ";
    }
    
    else
    {
        $row = $connect->query("select * from applystudents where SID=".$sid.";")->fetch_array();
        if(!isset($row)){
            echo "
            <script>
                document.location.href='student.php';
            </script>
            ";
        }
        else
        {
            echo "
            <script>
                //alert('".$id."님 환영합니다');
                document.location.href='student-waiting.php';
            </script>";
        }
    }
    exit;
}
else
{
    exit;
    error(3); // 알 수 없는 오류 발생
    exit;
}
?>