<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>노트북 신청서</title>


  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- <script src="lib/jquery/jquery-3.3.1.min.js"></script> -->
  <script src="lib/bootstrap/dist/js/bootstrap.min.js"></script>
  <link href="lib/bootstrap/dist/css/bootstrap.css" rel="stylesheet" />
  <link href="css/site.css" rel="stylesheet" />

  <link href="css/navbar.css" rel="stylesheet" />
  <link href="css/fonts.css" rel="stylesheet" />

  <link href="css/student.css" rel="stylesheet" />
</head>
<?php
  require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';
  require $_SERVER["DOCUMENT_ROOT"].'/scripts/cookies.php';

  $connect = DBConnect();
  if(IsCookieSet() && CookieLogin($connect))
  {
      $username = $_COOKIE['UserName'];
      $SID = $_COOKIE['UserSID'];
      $subject = $_POST["nb-subj"];
      $num = $_POST["nb-num"];
  }
  else
  {
    error(2);
    exit;
  }
?>
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
  EchoNavBar(3);
  ?>
  <div class="container body-content">
    <div class="col-lg-2 col-md-2"></div>
    <div class="col-lg-8 col-md-8 col-xs-12">
      <form class="main-form">
          <ol class="form-ol">
            <label>노트북 대여 상황<label>
            <label><a style="font-size:20px;" href="laptop-waiting.php">노트북 신청 현황 페이지 이동하기</a><label>
              <h5>*지속적인 새로고침이 필요합니다. 자신의 신청이 승인되었는지 꼭 확인하기 바랍니다. 오류 시 제보 (노트북 연체 시 큰 불이익이 있습니다.)</h5>
              <h6>
              <?php
                $result = $connect->query('SELECT laptop_list.*,apply_laptop.period,user.UserName FROM apply_laptop LEFT JOIN laptop_list ON laptop_list.SubjNum=apply_laptop.SubjNum LEFT JOIN user ON apply_laptop.SID=user.SID;');

                echo "<table class='table table-striped table-bordered table-hover, display:inline-block'>
                <tr>
                  <th style='text-align: center;'>과목</th>
                  <th style='text-align: center;'>번호</th>
                  <th style='text-align: center;'>대여자</th>
                  <th style='text-align: center;'>대여기간</th>
                  <th style='text-align: center;'>승인</th>
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
                      default:
                          $subj_kr="정보";
                          break;
                  };
                  echo "<tr>";
                  echo "<td style='text-align:center;'>" . $subj_kr . "</td>";
                  echo "<td style='text-align:center;'>" . $row['num'] . "</td>";
                
                  $names=explode(',', $row['UserName']);
                  for($i = 0 ; $i < count($names) ; $i++){
                    $str=$names[$i];
                    $tmp_row[$i]=mb_substr($str, 0, 1, 'utf-8').'*'.mb_substr($str, 2, 2, 'utf-8');
                  }
                  $new_row=implode(",", $tmp_row);
                  echo "<td style='text-align:center;'>" . $new_row . "</td>";
                  echo "<td style='text-align:center;'>". $row['period'] ."일</td>";
                  if($row['borrow'] == 1){
                    echo "<td style='text-align:center;'>O</td>";
                  }
                  else {
                    echo "<td style='text-align:center;'>X</td>";
                  }
                  echo "</tr>";
                }
                echo "</table>";
              mysqli_close($connect);
              ?>
          </h6>
          </ol>
        </form>
        </div>
      </div>

  <div class="container body-content">
    <div class="col-lg-2 col-md-2"></div>
    <div class="col-lg-8 col-md-8 col-xs-12">
      <form class="main-form" action="laptop-teacherselect.php" method="POST" enctype="multipart/form-data" name="login_form">
        <div class="form-title">노트북 신청</div>
        <ol class="form-ol">
          <li>
            <lable> 노트북</lable>
            <div class="li-inside"><label style="font-size: 400; width:90%;">
                <?php
                  switch ($subject) {
                    case 'physics':
                      $subj_kr="물리";
                      $subnum=100+$num;
                      break;
                    case 'chemistry':
                      $subj_kr="화학";
                      $subnum=200+$num;
                      break;
                    case 'life':
                      $subj_kr="생명과학";
                      $subnum=300+$num;
                      break;
                    case 'earth':
                      $subj_kr="지구과학";
                      $subnum=400+$num;
                      break;
                    case 'math':
                      $subj_kr="수학";
                      $subnum=500+$num;
                      break;
                    default:
                      $subj_kr="정보";
                      $subnum=600+$num;
                      break;
                  }

                  $laptop = $subnum;
                  echo '<label style="font-size: 400; height: 800;">신청하실 노트북은 "'.$subj_kr.' '.$num.'번" 노트북 입니다.</label><br>'; //db 연결로 어떤 노트북인지 번호와 과목을 입력
                ?>
                  <input type="hidden" name="laptop" value=<?php echo $laptop; ?>>
                <lable><a href="student-laptop.php?subject=all&num=all.php" style="font size:15px; color:blue; text-align:center">(신청할 노트북 변경)</a></lable>
            </div>
          </li>
          <li>
            <label>대여 기간</label>
            <div class="li-inside">
              <?php
                $lend  =1;//빌리려고 하는 날짜
                $day = date("d") + $lend;//현재 날짜에 선택한 기간을 더하여 정함
                echo '<label style="font-size: 400;">'.date("20y년 m월 d일"),"~"."</label>";
                echo '<label style="font-size: 400;">'."$day","일까지 대여합니다."."</label>";
              ?>
              <div>
                <label>대여 기간 : </label>
                <div class="btn-group">
                  <button type="button" class="btn btn-default" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="ld-button">1</button>
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                      <span class="caret" style="margin-right: -6; margin-left: -2;"></span>
                      <label></label>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a href="javascript:;" onclick="lend_date(0, '1');">1</a></li>
                    <li><a href="javascript:;" onclick="lend_date(0, '2');">2</a></li>
                    <li><a href="javascript:;" onclick="lend_date(0, '3');">3</a></li>
                    <li><a href="javascript:;" onclick="lend_date(0, '4');">4</a></li>
                    <li><a href="javascript:;" onclick="lend_date(0, '5');">5</a></li>
                    <li><a href="javascript:;" onclick="lend_date(0, '6');">6</a></li>
                  </ul>
                </div>
                <input type="hidden" name="lenddate" id="ld-input" value='1'>
                <label>일</label>
              </div>
            </div>
          </li>
          <li>
            <label>대여 사유</label>
            <div class="li-inside">
              <div class="form-group">
              <input type="text" name="reason" placeholder="30자 이내로 작성하세요..." class="form-control" maxlength="30" onkeypress="nodotnspace();" required >
              </div>
            </div>
          </li>
          <li>
            <label>기타 요구사항</label>
            <div class="li-inside">
              <div class="form-group">
              <input type="text" name="addition" placeholder="50자 이내로 작성하세요..." class="form-control" maxlength="50" onkeypress="nodotnspace();" >
              </div>
            </div>
          </li>
        </ol>
        <div class="submit-button">
          <button type="submit" class="btn btn-success">노트북 신청</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>

  <?php require $_SERVER["DOCUMENT_ROOT"].'/scripts/ad.php'; EchoAd(); ?>
</body>

<script>
  function lend_date(target, value) {
    var buttons = ["ld-button"];
    var inputs = ["ld-input"];

    document.getElementById(buttons[target]).innerHTML = value;
    document.getElementById(inputs[target]).value = value;
  }

  function nodotnspace() {
   if((event.keyCode == 32) || (event.keyCode == 9) || (event.keyCode == 46)) {
    event.preventDefault();
   }
  }
</script>

</html>
