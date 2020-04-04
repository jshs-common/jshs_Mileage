<!DOCTYPE html>
<html>
    <head> 
    <?php
    require $_SERVER["DOCUMENT_ROOT"].'/scripts/cookies.php';
    require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';
    ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>선택창</title>

    <script src="lib/jquery/jquery-3.3.1.min.js"></script>
    <script src="lib/bootstrap/dist/js/bootstrap.min.js"></script>
    <link href="lib/bootstrap/dist/css/bootstrap.css" rel="stylesheet" />

    <link href="css/site.css" rel="stylesheet" />

    <link href="css/navbar.css" rel="stylesheet" />
    <link href="css/fonts.css" rel="stylesheet" />
    <link href="css/choose.css" rel="stylesheet" />
    </head>
    <body>
    <?php
    include $_SERVER["DOCUMENT_ROOT"].'/scripts/navbar.php';
    EchoNavBar();

    $connect = DBConnect();
    if(IsCookieSet() && CookieLogin($connect))
    {
        $username = $_COOKIE['UserName'];
    }
    else
    {
      error(2);
      exit;
    }
    ?>
        <div class="container">
            <h1 style="text-align:center; margin-top:3%;">안녕하세요! <?php echo $_COOKIE['UserName']; ?>님</h1>
            <div class="row" style="margin-top:2%;">
                <div class="col-xs-4 col-sm-offset-1 col-sm-2" id="mileage" onclick="location.href='student-mileage.php';">
                        ⬅상벌점<br>확인하러<br>가기
                </div>
                <div class="col-xs-4 col-sm-offset-1" id="room" onclick="location.href='student.php';">
                        특별실<br>신청하러<br>가기
                </div>
                <div class="col-xs-4 col-sm-offset-1 col-sm-2" id="laptop">
                        노트북➡<br>대여상황<br>확인하러<br>가기
                </div>
            </div>
        </div>
    </body>
</html>