<!DOCTYPE html>
<html>
    <head> 
    <?php
    require $_SERVER["DOCUMENT_ROOT"].'/scripts/cookies.php';
    require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';
    ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>학생 상벌점 관리</title>

    <!-- <script type="text/javascript" src="script/jquery.autocomplete.js>"></script>
    <link rel="stylesheet" type="text/css" href="script/jquery.autocomplete.css"/>  -->

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- <script src="lib/jquery/jquery-3.3.1.min.js"></script> -->
    <script src="lib/bootstrap/dist/js/bootstrap.min.js"></script>
    <link href="lib/bootstrap/dist/css/bootstrap.css" rel="stylesheet" />

    <link href="css/site.css" rel="stylesheet" />
    <link href="css/navbar.css" rel="stylesheet" />
    <link href="css/fonts.css" rel="stylesheet" />
    <link href="css/teacher-mileage.css" rel="stylesheet" />

    <!-- <script type="text/javascript" src="script/lib/jquery.js>"></script>
    <script type="text/javascript" src="script/lib/jquery.bgiframe.min.js>"></script>
    <script type="text/javascript" src="script/lib/jquery.ajaxQueue.js>"></script> -->

    <!-- <script>
    document.addEventListener('keydown', function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
        };
        }, true);
    </script> -->
    </head>
    <body>
        <?php
            include $_SERVER["DOCUMENT_ROOT"].'/scripts/navbar.php';
            include $_SERVER["DOCUMENT_ROOT"].'/scripts/sidebar.php';
            EchoNavBar();

            $connect = DBConnect();
            if($_COOKIE['UserSID'] >= 300){
                if(IsCookieSet() && CookieLogin($connect)){
                  $SID = $_COOKIE['UserSID'];
                  $username = $_COOKIE['UserName'];
                }
                else{
                    error(2);
                    exit;
                }
            }
            else
            {
              error(2);
              exit;
            }
        ?>
        <link href="css/sidebar.css" rel="stylesheet" />
        <style>
            .card {
            margin-top: 30px;
            padding: 40px 40px 30px 40px;
            border-radius: 5px;
            box-shadow: rgba(0, 0, 0, 0.15) 3px 4px 20px;
        }

            .logo-capsule {
                text-align: center;
                position: relative;
                /*margin-bottom: 40px;*/

                font-family: 'BM DoHyeon';
                font-size: 82px;
                color: #4583FE;
            }

            .panel {
                margin-bottom: 20px;
                font-size: 18px;
            }
                .panel .panel-heading {
                    font-size: 20px;
                    font-weight: 700;
                }

                .panel .panel-body {
                    padding-bottom: 10px;
                }
                
                    .panel .panel-body ul {
                        list-style-type: none;
                        /*padding-top: 15px;*/
                        padding-left: 0px;
                    }

                        .panel .panel-body li {
                            margin-bottom: 8px;
                        }

                .panel .panel-btn-group {
                    display: flex;
                    justify-content: flex-end;
                    margin-bottom: 10px;
                    padding: 0px 14px 0px 0px;
                }

                    .panel .panel-btn-group .btn {
                        margin-left: 8px;
                        font-size: 18px;
                    }

            .alert {
                font-size: 18px;
                text-align: center;
            }

        .material-switch > input[type="checkbox"] {
            display: none;   
        }

        .material-switch > label {
            cursor: pointer;
            height: 0px;
            position: relative; 
            width: 40px;  
        }

        .material-switch > label::before {
            background: rgb(0, 0, 0);
            box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
            border-radius: 8px;
            content: '';
            height: 16px;
            margin-top: -8px;
            position:absolute;
            opacity: 0.3;
            transition: all 0.4s ease-in-out;
            width: 40px;
        }
        .material-switch > label::after {
            background: rgb(255, 255, 255);
            border-radius: 16px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
            content: '';
            height: 24px;
            left: -4px;
            margin-top: -8px;
            position: absolute;
            top: -4px;
            transition: all 0.3s ease-in-out;
            width: 24px;
        }
        .material-switch > input[type="checkbox"]:checked + label::before {
            background: inherit;
                opacity: 0.5;
        }
        .material-switch > input[type="checkbox"]:checked + label::after {
            background: inherit;
            left: 20px;
        }
        </style>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-2" style="padding: 0 0 0 0;">
                <?php EchoSideBar(); ?>
                </div>
                <div class="col-xs-10" style="padding-left: 2%;">
                    <h2><?php echo $username ?>선생님의 신청자 확인 및 상점 부여(BETA)</h2>
                    <h4>게시된 공고를 확인하고, 상점을 부여하세요</h4><br><br>
                    <div class="row">
                        <div class="col-sm-offset-1 col-sm-10 " style="background-color:white; box-shadow: 0 8px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); min-height:100vh; border-radius: 30px; padding-bottom: 5%; float: center;" >
                        <br><h1 style="text-align: center; font-family: 'BM DoHyeon'; margin-bottom:2%;">내 공고 목록</h1>
                            <h5 style="text-align: center;">선생님이 올리신 공고들을 모아두었습니다. 학생은 선착순으로 받아지며 공고는 최신순입니다. <br> 희망 학생이 다 모집되지 않아도 마감할 수 있습니다. 상점 공고 목록은 학기 단위로 초기화됩니다. :)</h5>
                            <br>
                            <h6>
                                <div style='overflow-x:hidden;'>
                                    <table class='table table-striped table-bordered table-hover, display:inline-block'>
                                    <?php
                                    $count = 0;
                                    $query = 'SELECT * FROM mileagenotice WHERE teachername="'.$username.'" ORDER BY noticedate DESC;';
                                    $result = mysqli_query($connect, $query);
                                    while($row = $result->fetch_array())
                                    {
                                      $item = '';
                                      $count++;
                                      $title = $row['title'];
                                      switch($row['isend'])
                                      {
                                        case 0:
                                          $title .= ' - 아직 마감 안됨';
                                          $item = '<div class="panel panel-info">';
                                          break;
                                        case 1:
                                          $title .= ' - 마감됨';
                                          $item = '<div class="panel panel-danger">';
                                          break;
                                        case 2:
                                            $title .= ' - 상점 부여 완료';
                                            $item = '<div class="panel panel-success">';
                                            break;
                                      }
                                      $students = $row['students'];
                            
                                      $item .= '<div class="panel-heading">'.$title.'</div>';
                                      $item .= '<div class="panel-body"><ul>';
                                        $item .= '<li>공고 시간 : '.$row[0].'</li>';
                                        $item .= '<li>신청 학생 : '.$students.'</li>';
                                        $item .= '<li>인원 / 신청 인원 : '.$row[2].' / '.$row['studentnum'].'</li>';
                                        $item .= '<li>부여 상점 : '.$row[3].'</li>';
                                      $item .= '</ul></div>';
                            
                                      $item .= '
                                      <div class="panel-btn-group">
                                        <div class="btn btn-success" onclick="location.href=\'noticecheck_apply.php?isend=1&time='.$row[0].'\';">마감</div>';
                                        if($row['isend'] != 0){
                                            $item .= '<div class="btn btn-danger" onclick="location.href=\'noticecheck_apply.php?isend=2&time='.$row[0].'\';">상점 지급</div>';
                                        }
                                        $item .= '</div>
                                      ';
                                      $item .= '</div>';
                                      echo $item;
                                    }
                            
                                    if($count == 0)
                                    {
                                      echo '
                                      <div class="alert alert-warning" role="alert">
                                        <span class="glyphicon glyphicon-alert" style="margin-right: 8px;"></span>
                                        아직 낸 공고가 없어요
                                        <span class="glyphicon glyphicon-alert" style="margin-left: 6px;"></span>
                                      </div>';
                                    }
                                    ?>
                                </div>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>