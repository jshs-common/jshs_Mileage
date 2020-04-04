<!DOCTYPE html>
<html>
    <head> 
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>로그인</title>

    <script src="lib/jquery/jquery-3.3.1.min.js"></script>
    <script src="lib/bootstrap/dist/js/bootstrap.min.js"></script>
    <link href="lib/bootstrap/dist/css/bootstrap.css" rel="stylesheet" />

    <link href="css/site.css" rel="stylesheet" />

    <link href="css/navbar.css" rel="stylesheet" />
    <link href="css/fonts.css" rel="stylesheet" />

    </head>
    <body>
        <h3>특별실 신청 현황</h3>
        <h6>
              <?php
              require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';
              $connect = DBConnect();
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
    </body>
</html>