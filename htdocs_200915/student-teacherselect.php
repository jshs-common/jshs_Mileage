<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>선생님 선택</title>

  <script src="lib/jquery/jquery-3.3.1.min.js"></script>
  <script src="lib/bootstrap/dist/js/bootstrap.min.js"></script>
  <link href="lib/bootstrap/dist/css/bootstrap.css" rel="stylesheet" />

  <link href="css/site.css" rel="stylesheet" />
  <link href="css/navbar.css" rel="stylesheet" />
  <link href="css/fonts.css" rel="stylesheet" />

  <link href="css/teacherselect.css" rel="stylesheet" />
</head>
<?php
  require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';
  require $_SERVER["DOCUMENT_ROOT"].'/scripts/cookies.php';

  $connect = DBConnect();
  if(!CookieLogin($connect) || !isset(
      $_POST['starttime'], $_POST['endtime'], $_POST['location'], $_POST['purpose'], $_POST['plusstudentlist']))
  {
    error(2);
    exit;
  }

  $postvalue = array();
  $postname = array();
  array_push($postvalue, $_POST['starttime'], $_POST['endtime'], $_POST['location'], $_POST['purpose'], $_POST['plusstudentlist']);
  array_push($postname, 'starttime', 'endtime', 'location', 'purpose' ,'plusstudentlist');

  if(!($postvalue[4])){
    echo "
    <script> 
        alert('학생이 선택되지 않았습니다.'); document.location.href='student.php';
    </script>
    ";
    exit;
  }

  $result = $connect->query('select SID, Attendance from teachers') or die($this->_connect->error);
  $name = array();
  $SID = array();
  $attendance = array();

  while($row = $result->fetch_array())
  {
    $tmp = $connect->query('select UserName from user where SID='.$row['SID']);
    array_push($name, $tmp->fetch_array()['UserName']);
    array_push($SID, $row['SID']);
    array_push($attendance, $row['Attendance']);
  }

?>
<body>
  <?php
  include $_SERVER["DOCUMENT_ROOT"].'/scripts/navbar.php';
  EchoNavBar(3);
  ?>

  <div class="container body-content">
    <div class="row">
      <div class="col-lg-3 col-md-3 col-xs-1"></div>
      <div class="col-lg-6 col-md-6 col-xs-10 login-card">
        <div class="logo-capsule">
          <p>선생님 선택</p>
        </div>
        <h5 style="text-align:center;">F5를 누르면 데이터 날라감 없이 목록이 새로고침됩니다.</h5>
        <div class="list-group" style="text-align: center;">
          <?php
          for($i = 0; $i < count($name); $i++)
          {
            $item = '<a href="javascript:;" class="list-group-item ';
            $display_name = $name[$i]." 선생님";
            if($attendance[$i] == 0)
            {
              $item = $item.'disabled';
              $display_name = $display_name.' - 부재중';
            }
            $item = $item.'" id="teacher-select-'.$i.'" onclick="teacher_select('.$i.');">'.$display_name.'</a>';
            echo $item;
          }
          ?>
        </div>
        <div class="submit-button">
          <div class="btn btn-success" onclick="goPost();">신청서 제출</div>
        </div>
      </div>
    </div>
  </div>

  <?php require $_SERVER["DOCUMENT_ROOT"].'/scripts/ad.php'; EchoAd(); ?>
</body>

<script>
  <?php
  echo "
  var _SID = ['".implode( "' , '", $SID )."']
  var _attendance = ['".implode( "' , '", $attendance )."']
  ";


  ?>

  var prev = 0;
  var selected = false;
  var sidsid = "<? echo $_COOKIE['UserSID']?>";
  function teacher_select(value){
    if(sidsid < 300){
      if(_attendance[value] == 0)return;
    }
    selected = true;
    var t;
    t = document.getElementById('teacher-select-'+ prev);
    t.classList.remove('active');
    prev = value;

    t = document.getElementById('teacher-select-'+ value);
    t.classList.add('active');
  }

  function goPost() {
    if(!selected)
    {
      alert('먼저 선생님을 선택해 주세요!');
      document.location.href = '#';
      return;
    }
    var form = document.createElement("form");

    form.setAttribute("charset", "UTF-8");
    form.setAttribute("method", "Post");
    form.setAttribute("action", "student-finish.php");

    <?php
    for($i = 0; $i < count($postname); $i++)
    {
      echo '
      var hiddenField = document.createElement("input");
      hiddenField.setAttribute("type", "hidden");
      hiddenField.setAttribute("name", "'.$postname[$i].'");
      hiddenField.setAttribute("value", "'.$postvalue[$i].'");
      form.appendChild(hiddenField);
      ';
    }
    ?>

    hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "TeacherSID");
    hiddenField.setAttribute("value", _SID[prev]);
    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();
  }
</script>

<?php
  if($_COOKIE['UserSID'] >= 300){
    $k = $_COOKIE['UserSID'] - 300;
    echo "
    <script> 
        alert('". $_COOKIE['UserName']."선생님이시군요. ★ 자동대로 신청 수락까지 완료되었습니다. ★ 현재 시스템이 온전하지 않아, 명단을 수정할 경우에는 학생 계정으로 직접 취소하거나 선생님이 거부를 누르고 다시 신청하셔야 합니다. ');
        teacher_select(".$k.");
        goPost();
    </script>
    ";
  }
?>

</html>
