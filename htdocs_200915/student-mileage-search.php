<?php   
    require $_SERVER["DOCUMENT_ROOT"].'/scripts/cookies.php';
    require $_SERVER["DOCUMENT_ROOT"].'/scripts/dbconnect.php';
    $period = $_GET['sm']; 
    $isminus = $_GET['pom'];
    $type = $_GET['unit'];

    if($period == "None"){
        $searchsql = mysqli_query($connect, 'SELECT * FROM mileagehistory WHERE SID="'.$_COOKIE['UserSID'].'"') or die(mysqli_error($connect));
    }
    else if($period == "1" or "2")
        if($type == "None"){
            $searchsql = mysqli_query($connect, "SELECT * FROM mileagehistory WHERE SID=".$_COOKIE['UserSID']." AND period=".$period) or die(mysqli_error($connect));
            
        }
        else{
            $searchsql = mysqli_query($connect, "SELECT * FROM mileagehistory WHERE SID=".$_COOKIE['UserSID']." AND period=".$period." AND isminus=".$type) or die(mysqli_error($connect));
        }
    }
    $k = 1;

    echo "<table class='table table-striped table-bordered table-hover, display:inline-block'>
        <tr>
        <th style='text-align:center;'>번호</th>
        <th style='text-align:center;'>날짜</th>
        <th style='text-align:center;'>유형</th>
        <th style='text-align:center;'>사유</th>
        <th style='text-align:center;'>상벌점</th>
        <th style='text-align:center;'>담당 선생님</th>
        </tr>";
    while ($row = mysqli_fetch_array($sql2)){
        echo "<tr>";
            echo "<td style='text-align:center;'>" . $k . "</td>";
            echo "<td style='text-align:center;'>" . $row[1] . "</td>";
            echo "<td style='text-align:center;'>" . $row[4] . "</td>";
            echo "<td>" . $row[5] . "</td>";
            if($row[7] == 0){
            echo "<td style='text-align:center; color:green; font-weight:700;'>". $row[6] . "</td>";
            }
            else {
            echo "<td style='text-align:center; color:red; font-weight:700;'>". $row[7] . "</td>";
            }
            echo "<td style='text-align:center;'>" . $row[8] . " 선생님</td>";
            echo "</tr>";
        $k = $k+1;
    }
    echo "</table>";

    mysqli_close($connect);
?>