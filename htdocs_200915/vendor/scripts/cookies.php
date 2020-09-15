<?php
    function ClearCookie() {
        setcookie('UserSID', '', strtotime('-1months'), '/');
        setcookie('UserName', '', strtotime('-1months'), '/');
        setcookie('token', '', strtotime('-1months'), '/');
    }

    function IsCookieSet()
    {
        return isset($_COOKIE['UserSID'], $_COOKIE['UserName'], $_COOKIE['token']);
    }

    function CookieLogin($connect)
    {
        $sid = $_COOKIE['UserSID'];
        $token = $_COOKIE['token'];
        if(isset($connect) && IsCookieSet())
        {
            $row = $connect->query("select * from connection where SID=".$sid.";")->fetch_array();
            $cmptoken = $row['token'];
            $expire =  $row['expire'];
            if(isset($cmptoken) && !strcmp($cmptoken, $token) && time() < strtotime($expire))
            {
                return true;
            }
        }
        return false;
    }


    function error($ErrorCode = 0)
    {
        ClearCookie();
        $ErrorDescription = array('none', '아이디 또는 비밀번호가 잘못되었습니다', '비정상적인 접근입니다', '알 수 없는 오류가 발생하였습니다');
        $script = "<script>";
        if($ErrorCode > 0)
        {
            $script .= "alert('".$ErrorDescription[$ErrorCode]."');";
        }
        $script .= "document.location.href='login.php';</script>";
        echo $script;
    }
?>