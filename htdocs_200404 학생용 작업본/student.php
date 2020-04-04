<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>특별실 신청서</title>

  <script src="lib/jquery/jquery-3.3.1.min.js"></script>
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
?>
<body>
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
              <h5>*지속적인 새로고침이 필요합니다. 자신의 팀이 승인되었는지 꼭 확인하기 바랍니다. 오류 시 제보 (신청취소된 순번은 없어지니 번호가 연속적이지 않습니다.)</h5>
              <h6>
              <?php
              $result = mysqli_query($connect, 'SELECT * FROM (SELECT ApplyID, group_concat(Username) FROM (SELECT applystudents.ApplyID, user.Username FROM applystudents, user WHERE applystudents.SID = user.SID) AS A GROUP BY A.ApplyID) AS B, apply WHERE B.ApplyID = apply. ApplyID');

              echo "<table class='table table-striped table-bordered table-hover, display:inline-block'>
                  <tr>
                  <th>신청번호</th>
                  <th>장소</th>
                  <th>목적</th>
                  <th>신청인단</th>
                  <th>승인</th>
                  </tr>";
              while ($row = mysqli_fetch_array($result)){
                echo "<tr>";
                echo "<td>" . $row[0] . "</td>";
                echo "<td>" . $row[4] . "</td>";
                echo "<td>" . $row[5] . "</td>";
                echo "<td>" . $row[1] . "</td>";
                if($row[8] == 1){
                  echo "<td>O</td>";
                }
                else {
                  echo "<td>X</td>";
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
                  <button type="button" class="btn btn-default" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="st-button">7</button>
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                      <span class="caret" style="margin-right: -6; margin-left: -2;"></span>
                      <label></label>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a href="javascript:;" onclick="set_time(0, 7);">7</a></li>
                    <li><a href="javascript:;" onclick="set_time(0, 8);">8</a></li>
                    <li><a href="javascript:;" onclick="set_time(0, 9);">9</a></li>
                    <li><a href="javascript:;" onclick="set_time(0, 10);">10</a></li>
                    <li><a href="javascript:;" onclick="set_time(0, 11);">11</a></li>
                  </ul>
                </div>
                <input type="hidden" name="starthour" id="st-input" value="7">
                <span>시 : </span>

                <div class="btn-group">
                  <button type="button" class="btn btn-default" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="sm-button">00</button>
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                      <span class="caret" style="margin-right: -6; margin-left: -2;"></span>
                      <label></label>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a href="javascript:;" onclick="set_time(1, '00');">00</a></li>
                    <li><a href="javascript:;" onclick="set_time(1, 30);">30</a></li>
                  </ul>
                </div>
                <input type="hidden" name="startminute" id="sm-input" value="00">
                <span>분 ~ </span>

                <div class="btn-group">
                  <button type="button" class="btn btn-default" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="et-button">9</button>
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                      <span class="caret" style="margin-right: -6; margin-left: -2;"></span>
                      <label></label>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a href="javascript:;" onclick="set_time(2, 8);">8</a></li>
                    <li><a href="javascript:;" onclick="set_time(2, 9);">9</a></li>
                    <li><a href="javascript:;" onclick="set_time(2, 10);">10</a></li>
                    <li><a href="javascript:;" onclick="set_time(2, 11);">11</a></li>
                    <li><a href="javascript:;" onclick="set_time(2, 12);">12</a></li>
                  </ul>
                </div>
                <input type="hidden" name="endhour" id="et-input" value="9">
                <span>시 : </span>

                <div class="btn-group">
                  <button type="button" class="btn btn-default" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="em-button">00</button>
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                      <span class="caret" style="margin-right: -6; margin-left: -2;"></span>
                      <label></label>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a href="javascript:;" onclick="set_time(3, 00);">00</a></li>
                    <li><a href="javascript:;" onclick="set_time(3, 30);">30</a></li>
                  </ul>
                </div>
                <input type="hidden" name="endminute" id="em-input" value="00">
                <span>분</span>
              </div>
            </div>
          </li>
          <li>
            <label>사용 장소</label>
            <h5>신청 목적은 상관없지만 신청 장소는 바르게 기재해 주시기 바랍니다.</h5>
            <div class="li-inside">
              <div class="input-group input-group-location">
              <input type="text" class="form-control" name="location" placeholder="20자 이내로 작성하세요..." maxlength="20" onkeypress="nodotnspace();" required >
                <div class="input-group-btn">
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="Science-button">
                    본관<span class="caret" style="margin-left: 6px;"></span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="javascript:;" onclick="setScience(0);" >본관</a></li>
                    <li><a href="javascript:;" onclick="setScience(1);" >과학동</a></li>
                  </ul>
                </div>
              </div>
              <input type="hidden" name="isScience" value="0" id="Science-input" required>
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
            <label>사용 학생 명단</label>
            <div class="li-inside">
              <div class="form-group">
                <input type="text" name="student" placeholder="자신의 이름을 포함하여 띄어쓰기 없이 쉼표로 학생을 구분하여 100자 이내로 작성하세요..." class="form-control" maxlength="100" onkeypress="nodotnspace();" required>
              </div>
            </div>
          </li>
        </ol>
        <div class="submit-button">
          <button type="submit" class="btn btn-success">선생님 선택</button>
        </div>
      </form>
    </div>
  </div>

  <?php require $_SERVER["DOCUMENT_ROOT"].'/scripts/ad.php'; EchoAd(); ?>
</body>

<script>
  function setScience(value) {
    var areas = ["본관", "과학동"];
    document.getElementById("Science-button").innerHTML = areas[value] + '<span class="caret" style="margin-left: 6px;"></span>';
    document.getElementById("Science-input").value = value;
  }

  function set_time(target, value) {
    var buttons = ["st-button", "sm-button", "et-button", "em-button"];
    var inputs = ["st-input", "sm-input", "et-input", "em-input"];

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
