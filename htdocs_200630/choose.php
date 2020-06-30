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
            var images = ['상_예준1.png', '상_연주1.png', '상_교현1.png', '상_교현2.png', '상_현승1.jpg', '상_3학년1.jpg', '상_3학년2.jpg', '상_3학년3.jpg', '상_3학년4.jpg', '상_3학년5.jpg', '상_3학년6.jpg', '상_3학년7.jpg', '상_범석1.png', '상_동율1.jpg', '상_민석1.jpg', '상_3학년8.jpg', '상_축구1.jpg', '상_성민1.png', '상_3학년9.jpg', '상_3학년10.jpg', '상_3학년11.jpg', '상_3학년12.jpg', '상_3학년13.jpg', '상_3학년14.jpg', '상_3학년15.jpg', '상_3학년16.jpg', '상_3학년17.jpg', '상_3학년18.jpg', '상_3학년19.jpg', '상_3학년20.jpg', '상_2학년1.jpg', '상_2학년3.jpg', '상_2학년4.jpg', '상_2학년5.jpg', '상_2학년6.jpg', '상_2학년2.jpg', '상_2학년7.jpg', '상_3학년21.jpg', '상_3학년22.jpg', '상_3학년23.jpg', '상_3학년24.jpg', '상_3학년25.jpg', '상_3학년26.jpg', '상_3학년27.jpg', '상_3학년28.jpg', '상_3학년29.jpg', '상_3학년30.jpg', '상_3학년31.jpg', '상_3학년32.jpg', '상_3학년33.jpg'];
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