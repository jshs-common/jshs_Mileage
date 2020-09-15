<?php
    function EchoNavBar($status = 0)
    {
        include_once $_SERVER["DOCUMENT_ROOT"].'/scripts/cookies.php';

        $navbar = '
        <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="index.php" class="navbar-brand">제주과고 면학실 신청</a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
        ';
        
        if(IsCookieSet())
        {
            $navbar .= '<li><a href="excel.py"><span class="glyphicon glyphicon-download-alt" style="margin-right:8px;"></span>출석부 다운로드</a></li>';
            $navbar .= '<li><a href="changepw.php" class="btn-change-pw" ><span class="glyphicon glyphicon-user" style="margin-right:8px;"></span>비밀번호 변경</a></li>';
            $navbar .= '<li id="PushOn"><a href="javascript:;" onclick="SetPush(1);"><span class="glyphicon glyphicon-bell" style="margin-right:8px;"></span>알림 활성화</a></li>';
            $navbar .= '<li id="PushOff"><a href="javascript:;" onclick="SetPush(0);"><span class="glyphicon glyphicon-bell" style="margin-right:8px;"></span>알림 비활성화</a></li>';
            $navbar .= '<li><a href="logout.php"><span class="glyphicon glyphicon-log-out" style="margin-right:8px;"></span>로그아웃</a></li>';
        }
        else
        {
            $navbar .= '<li><a href="excel.py"><span class="glyphicon glyphicon-download-alt" style="margin-right:8px;"></span>출석부 다운로드</a></li>';
            $navbar .= '<li><a href="login.php"><span class="glyphicon glyphicon-log-in" style="margin-right:10px;"></span>로그인</a></li>';
        }
        $navbar .= '</ul></div></div></nav>';
        echo $navbar;
        echo '<script src="lib/serviceWorker/main.js"></script>'; //서비스 워커를 사용하기 위해 main.js불러옴
    }
?>