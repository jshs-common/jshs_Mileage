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
        if($_COOKIE['UserSID'] >= 300){
            Header("Location:choose-teacher.php");
        }
    }
    else
    {
      error(2);
      exit;
    }

    $SID = $_COOKIE['UserSID'];


    ?>

        <script type="text/javascript">
            var images = ['그림1.png', '그림2.png', '그림3.png', '그림4.png', '그림5.png', '그림6.png', '그림7.png', '그림8.png', '그림9.png', '그림10.png', '그림11.png', '그림12.png'];
            window.onload = function(){
            $('#box2').css({'background-image': 'url(img/' + images[Math.floor(Math.random() * images.length)] + ')'});
            }
        </script>

        <div class="container">
            <h1 id="bigtitle" style="text-align:center; margin-top:3%;">안녕하세요! <?php echo $_COOKIE['UserName']; ?>님</h1>
            <div class="row" style="margin-top:2.5%; width:100%">
                <div class="col-sm-12" id="box1" onclick="location.href='student.php';">
                        <div id="title" style="font-size: 3em;">특별실 신청</div>
                        <div id="description" style="font-size: 1em;">특별실 신청</div>
                        <div id="description" style="font-size: 1em;">신청 현황 확인</div>
                </div>
                <div class="col-sm-6" id="box2" onclick="location.href='student-mileage.php?sm=all&pom=all&searchbox=';">
                        <div id="title" style="font-size: 3em;"><span>상벌점 조회</span></div>
                        <div id="description" style="font-size: 1em;">상벌점 조회</div>
                        <div id="description" style="font-size: 1em;">상점 신청</div>
                        <div id="description" style="font-size: 1em;">상점 순위 조회</div>
                </div>
                <div class="col-sm-6" id="box3" onclick="location.href='student-laptop.php?subject=all&num=all';">
                        <div id="title" style="font-size: 3em; z-index:2; margin-bottom:3%;">노트북 대여</div>
                        <div id="description" style="font-size: 1em; z-index:2; margin-bottom:2%;">노트북 대여 신청</div>
                        <div id="description" style="font-size: 1em; z-index:2; margin-bottom:4%;">대여 현황 확인</div>
                </div>
            </div>
        </div>
    </body>
</html>
