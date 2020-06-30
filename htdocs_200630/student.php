<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>특별실 신청서</title>


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
  }
  else
  {
    error(2);
    exit;
  }

  if(IsCookieSet() && CookieLogin($connect))
  {
      $SID = $_COOKIE['UserSID'];

      $row = $connect->query("select * from applystudents where SID=".$SID.";")->fetch_array();
      if(isset($row))
      {
        // $prevPage = $_SERVER["HTTP_REFERER"];
        // if($prevPage == "https://xn--wb0btwh7t3yibkc86i9tc5x9b.com/student-waiting.php"){
        // }
        // else{
        //   echo "<meta http-equiv='refresh' content='0;url=student-waiting.php'>";
        //   exit;
        // }
        echo "<meta http-equiv='refresh' content='0;url=student-waiting.php'>";
        exit;
      }
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
<script>
    document.addEventListener('keydown', function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
        };
        }, true);
    </script>
  <?php
  include $_SERVER["DOCUMENT_ROOT"].'/scripts/navbar.php';
  EchoNavBar(3);
  ?>
  <div class="container body-content">
    <div class="col-lg-2 col-md-2"></div>
    <div class="col-lg-8 col-md-8 col-xs-12">
      <form class="main-form" action="student-teacherselect.php" method="POST" enctype="multipart/form-data" name="login_form">
          <ol class="form-ol">
            <label>실시간 신청 상황 알림 페이지<label>
            <label>👉 <a style="font-size:20px;" href="student-waiting.php">(신청 취소를 원할 경우) 신청 상황 확인 페이지 이동하기</a><label>
              <h5>*지속적인 새로고침이 필요합니다. 자신의 팀이 승인되었는지 꼭 확인하기 바랍니다. 오류 시 제보 (신청취소된 순번은 없어지니 번호가 연속적이지 않습니다.)</h5>
              <h6>
              <?php
              $result = mysqli_query($connect, 'SELECT * FROM (SELECT ApplyID, group_concat(Username) FROM (SELECT applystudents.ApplyID, user.Username FROM applystudents, user WHERE applystudents.SID = user.SID) AS A GROUP BY A.ApplyID) AS B, apply WHERE B.ApplyID = apply. ApplyID');

              echo "<table class='table table-striped table-bordered table-hover, display:inline-block'>
                  <tr>
                  
                  <th>장소</th>
                  <th>목적</th>
                  <th>신청인단</th>
                  <th>승인</th>
                  </tr>";
              while ($row = mysqli_fetch_array($result)){
                echo "<tr>";
                //echo "<td>" . $row[0] . "</td>";
                echo "<td>" . $row[4] . "</td>";
                echo "<td>" . $row[5] . "</td>";
                
                $names=explode(',', $row[1]);
                $tmp_row = [];
                for($i = 0 ; $i < count($names) ; $i++){
                  $str=$names[$i];
                  $tmp_row[$i]=$str;
                }

                $new_row=implode(", ", $tmp_row);
                echo "<td>" . $new_row . "</td>";

                if($row[8] == 1){
                  echo "<td>O</td>";
                }
                else {
                  echo "<td>X</td>";
                }
                echo "</tr>";
              }
              echo "</table>";

              //mysqli_close($connect);
?>
          </h6>
          </ol>
        </form>
        </div>
      </div>

  <div class="container body-content">
    <div class="col-lg-2 col-md-2"></div>
    <div class="col-lg-8 col-md-8 col-xs-12">
      <form class="main-form" action="student-teacherselect.php" method="POST" enctype="multipart/form-data" name="login_form">
        <div class="form-title">특별실 신청</div>
        <ol class="form-ol">
          <li>
            <label>사용 시간</label>
            <div class="li-inside">
              <?php
                echo '<label style="font-size: 400;">'.date("20y년 m월 d일")."</label>";
              ?>
              <div>
                <div class="btn-group">
                  <button type="button" class="btn btn-default" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="sh-button">1</button>
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                      <span class="caret" style="margin-right: -6; margin-left: -2;"></span>
                      <label></label>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a href="javascript:;" onclick="set_time(0, '1');">1</a></li>
                    <li><a href="javascript:;" onclick="set_time(0, '2');">2</a></li>
                  </ul>
                </div>
                <input type="hidden" name="starttime" id="sh-input" value="1">
                <span>차 면학 ~</span>

                <div class="btn-group">
                  <button type="button" class="btn btn-default" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="eh-button">1</button>
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                      <span class="caret" style="margin-right: -6; margin-left: -2;"></span>
                      <label></label>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a href="javascript:;" onclick="set_time(1, '1');">1</a></li>
                    <li><a href="javascript:;" onclick="set_time(1, '2');">2</a></li>
                  </ul>
                </div>
                <input type="hidden" name="endtime" id="eh-input" value="1">
                <span>차 면학</span>
              </div>
            </div>
          </li>
          <li>
            <label>사용 장소</label>
            <h5>신청 목적은 상관없지만 신청 장소는 바르게 기재해 주시기 바랍니다.</h5>
            <div class="li-inside">
              <div class="form-group">
              <input type="text" name="location" placeholder="20자 이내로 작성하세요..." class="form-control" maxlength="20" onkeypress="nodotnspace();" required >
              </div>
            </div>
          </li>
          <li>
            <label>사용 목적</label>
            <div class="li-inside">
              <div class="form-group">
                <input type="text" name="purpose" placeholder="50자 이내로 작성하세요..." class="form-control" maxlength="50" onkeypress="nodotnspace();" required>
              </div>
            </div>
          </li>
          <li>
            <label>학생 선택</label>
            <div class="li-inside" style="margin-top:">
            <h5 style="text-align:center;">학년 반 번호 입력 혹은 이름 입력 시 자동완성이 활성화됩니다.</h5>
            <h5 style="text-align:center;">자동 완성한 결과를 추가하면 아래에 학생이 추가됩니다.</h5>
            <div class="row" style="margin-bottom:3%;">
                <div class="col-md-offset-2 col-md-6" id="addstudent-plus">
                    <input type="text" id="plusstudent" class="form-control" placeholder="ex) 1101 or 홍길동" maxlength="4" >
                    <!-- onKeyPress="if( event.keyCode==13 ){Add();}-->
                </div>
                <div class="col-md-2">
                    <button type="button" style="margin-top:2%;" id="addplus"></button>
                </div>
            </div>
            <div id="list" style="margin-bottom: 5%; text-align:center;">
                <textarea style="display:none" id="plusstudentlist" name="plusstudentlist" value="" require ></textarea>
            </div>




          <!--
            <label>사용 학생 명단</label>
            <div class="li-inside">
              <div class="form-group">
                <input type="text" name="student" placeholder="자신의 이름을 포함하여 띄어쓰기 없이 쉼표로 학생을 구분하여 100자 이내로 작성하세요..." class="form-control" maxlength="100" onkeypress="nodotnspace();" required>
              </div>
            </div>
          -->
          </li>
        </ol>
        <div class="submit-button">
          <button type="submit" class="btn btn-success">선생님 선택</button>
        </div>
      </form>
    </div>
  </div>
  <?php
   $searchsql = mysqli_query($connect, "SELECT * FROM user WHERE IsTeacher = 0") or die(mysqli_error($connect));
   $array = array();
   while ($row = mysqli_fetch_array($searchsql)){
      array_push($array, $row[1]." ".$row[2]);
   }
  mysqli_close($connect);
  ?>

  <script>
  function remove(id) {
      var elem = document.getElementById(id);
       return elem.parentNode.removeChild(elem);
   }
   $(document).on("click","#delplus",function(){
       var index = $(this).attr("class");
      remove(index);
       var listvalue = $('textarea[name=plusstudentlist]').text();
      var splitedvalue = index.split(' ');
      var splitedvalue2 = listvalue.replace(splitedvalue[1]+",", "");
      $('textarea[name=plusstudentlist]').text(splitedvalue2);
  });


  var available = <?php echo json_encode($array)?>; 
  $( "#addplus" ).click( function() {
      var value = $("#plusstudent").val();
      if(value == "" || value == ' ' || value == '  ' || value == '   ' || value == '    '){
          return false;
      }
      if(!available.includes(value)){
          alert( '학생이 존재하지 않습니다! 입력값이 제대로 되었는지 확인해주세요. 예) 3206 김채린' );
          return false;
      }
      $('#list').append($('<div class="pluslist" style="display:inline-block;" id="'+value+'">'+ value +"<button type='button' id='delplus' class='"+value+"'> </button>"+'</div>').fadeIn());
      $('#plusstudent').val("");
      var before = $('textarea[name=plusstudentlist]').text();
      var splitedvalue = value.split(' ');
      $('textarea[name=plusstudentlist]').text(before + splitedvalue[1] + ",");
  });
  $(function() {
      $("#plusstudent").autocomplete({
          source: available,
          minLength: 2,
          select: function(event, ui) {
              console.log(ui.item);
          },
          focus: function(event, ui) {
              return false;
              //event.preventDefault();
          }
      });
  });
 </script>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>

</body>

<script>
  function set_time(target, value) {
    var buttons = ["sh-button", "eh-button"];
    var inputs = ["sh-input", "eh-input"];

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
