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

  	<link href="css/student.css" rel="stylesheet" />
    <link href="css/choose.css" rel="stylesheet" />
    </head>

	<style>
    button {border:0 none; background-color:transparent; cursor:pointer;}
    #addplus{
        width: 2em !important;
        height: 2em !important;
        background: url("img/addplus.png") center/2em no-repeat;
    }
    #addplus:hover{
        width: 2em !important;
        height: 2em !important;
        background: url("img/addplus-hover.png") center/2em no-repeat;
    }
    #delplus{
        width: 0.6em !important;
        height: 0.6em !important;
        background: url("img/delplus.png") center/0.6em no-repeat;
        margin-left:10%;
    }
    #delplus:hover{
        width: 0.6em !important;
        height: 0.6em !important;
        background: url("img/delplus-hover.png") center/0.6em no-repeat;
        margin-left:10%;
    }
    .pluslist{
       display:inline-block;
       width:40%;
       padding-left:2%;
       padding-right:2%;
       margin-bottom:2%;
       margin-left:2%;
       margin-right:2%;
       background-color: #D2E5A8;
   }
	</style>


    <body>
    <?php
    include $_SERVER["DOCUMENT_ROOT"].'/scripts/navbar.php';
    EchoNavBar();

    $connect = DBConnect();
    if($_COOKIE['UserSID'] >= 300){
        if(IsCookieSet() && CookieLogin($connect)){
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
        <div class="container">
            <h1 id="bigtitle" style="text-align:center; margin-top:3%;">안녕하세요! <?php echo $_COOKIE['UserName']; ?>선생님</h1>
             <div class="container body-content">
             	<div class="col-lg-2 col-md-2"></div>
             	<?php

             	if($_COOKIE['UserSID'] == 319||$_COOKIE['UserSID'] == 310){
		        echo "<div class=\"row\" style=\"margin-top:2.5%; width:100%\">
	                <div class=\"col-sm-6\" id=\"box2\" style=\"background-color:#353535;\" onclick=\"location.href='teacher-laptop.php'\">
	                        <div id=\"title\" style=\"font-size: 3em;\">노트북 신청 확인<p style=\"font-size: 15px;\">노트북 담당 관리선생님만 이용가능합니다.</p></div>

		                </div>
		                <div class=\"col-sm-6\" id=\"box2\" style=\"background-color:#353535;\" onclick=\"location.href='teacher-laptop-return.php?subject=all&num=all';\">
		                        <div id=\"title\" style=\"font-size: 3em;\">노트북 반납 처리<p style=\"font-size: 15px;\">노트북 담당 관리선생님만 이용가능합니다.</p></div>
			                </div>
			            </div>
		        	</div>";
			    }else{
			      echo "<br>";
			    };
        ?>



    <div class="col-lg-8 col-md-8 col-xs-12" style="width: 100%;padding: 0px;">
      <form class="main-form">
          <ol class="form-ol">
            <label style="text-align:center; font-size: 30px; font-family:BM DoHyeon;" >노트북 대여 상황<label>
            <label><span style="font-size:20px; visibility:hidden;">노트북 신청 현황 페이지 이동하기</span><label>
            <h5>*지속적인 새로고침이 필요합니다. 이 페이지는 학생들의 신청여부를 확인하는 페이지 입니다. 오류 시 제보 부탁드립니다</h5>
              <h5>
                <div style='max-height:45vh; overflow-x:hidden; overflow-y:scroll;'>
                  <table class='table table-striped table-bordered table-hover, display:inline-block'>
                  <?php
                    $query = 'SELECT laptop_list.*,apply_laptop.BorDay,apply_laptop.RetDay,apply_laptop.due,user.UserName FROM apply_laptop LEFT JOIN laptop_list ON laptop_list.SubjNum=apply_laptop.SubjNum LEFT JOIN user ON apply_laptop.SID=user.SID;';
                    $result = mysqli_query($connect, $query);
                    echo "<tr>
                            <th style='text-align:center;'>과목</th>
                            <th style='text-align:center;'>번호</th>
                            <th style='text-align:center;'>대여자</th>
                            <th style='text-align:center;'>대여기간</th>
                            <th style='text-align:center;'>승인</th>
                            <th style='text-align:center;'>연체</th>
                          </tr>";

                    while ($row = mysqli_fetch_array($result)){
                        switch ($row['subj']) {
                            case 'physics':
                                $subj_kr="물리";
                                break;
                            case 'chemistry':
                                $subj_kr="화학";
                                break;
                            case 'life':
                                $subj_kr="생명과학";
                                break;
                            case 'earth':
                                $subj_kr="지구과학";
                                break;
                            case 'math':
                                $subj_kr="수학";
                                break;
                            case 'information':
                                $subj_kr="정보";
                                break;
                        }


                        $date = $row['BorDay']." ~ ".$row['RetDay'];

                        $filtered = mb_substr($row["UserName"],0,1,'utf-8').'*'.mb_substr($row['UserName'],2,2,'utf-8');
                        echo "<tr>";
                        echo "<td style='text-align:center;'>" . $subj_kr . "</td>";
                        echo "<td style='text-align:center;'>" . $row['num'] . "</td>";
                        echo "<td style='text-align:center;'>" . $filtered . "</td>";
                        echo "<td style='text-align:center;'>" . $date . "</td>";
                        if($row['borrow']==2) {
                          echo "<td style='text-align:center;'>" . "O" . "</td>";
                        }
                        else{
                          echo "<td style='text-align:center;'>" . "X" . "</td>";
                        }
                        $return = $row['due'];
                        if($return >= 0){
                          echo "<td style='text-align:center;'> 반납대기 </td>";
                        }
                        else{
                          echo "<td style='text-align:center;'>" . $return*-1 . "일 연체</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                    ?>
                </div>
            </table>
            </h5>
          </ol>
        </form>
		</div>
      </div>
    </body>
</html>
