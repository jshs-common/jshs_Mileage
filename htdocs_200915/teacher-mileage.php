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

    <script>
    document.addEventListener('keydown', function(event) {
        if (event.keyCode === 13 && event.srcElement.type != 'input') {
            event.preventDefault();
        };
        }, true);
    </script>
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
            #addminus{
                width: 2em !important;
                height: 2em !important;
                background: url("img/addminus.png") center/2em no-repeat;
            }
            #addminus:hover{
                width: 2em !important;
                height: 2em !important;
                background: url("img/addminus-hover.png") center/2em no-repeat;
            }
            #delminus{
                width: 0.6em !important;
                height: 0.6em !important;
                background: url("img/delminus.png") center/0.6em no-repeat;
                margin-left:10%;
            }
            #delminus:hover{
                width: 0.6em !important;
                height: 0.6em !important;
                background: url("img/delminus-hover.png") center/0.6em no-repeat;
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
            .minuslist{
                display:inline-block; 
                width:40%; 
                padding-left:2%; 
                padding-right:2%; 
                margin-bottom:2%; 
                margin-left:2%; 
                margin-right:2%; 
                background-color: #F15F5F;
            }
            </style>
        <script>
            window.onload  = function() {
                var agent = navigator.userAgent.toLowerCase();

                if((navigator.appName == 'Netscape' && navigator.userAgent.search('Trident') != -1) || (agent.indexOf("msie") != -1)){
                    alert("인터넷 익스플로러 브라우저는 상벌점 기능 사용이 어렵습니다. edge나 chrome으로 접속해주시면 감사하겠습니다. (인터넷 익스플로러 최적화는 진행중에 있습니다.)");
                }
                else if (agent.indexOf("msie") != -1) {
                    alert("인터넷 익스플로러 브라우저는 상벌점 기능 사용이 어렵습니다. edge나 chrome으로 접속해주시면 감사하겠습니다. (인터넷 익스플로러 최적화는 진행중에 있습니다.)");

                }
            }

        </script>
        <link href="css/sidebar.css" rel="stylesheet" />
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-2" style="padding: 0 0 0 0;">
                <?php EchoSideBar(); ?>
                </div>
                <div class="col-xs-10" style="padding-left: 2%;">
                    <h2>안녕하세요, <?php echo $username;?> 선생님!</h2>
                    <h4>왼쪽의 배너들을 눌러 학생들의 상벌점을 관리하세요!</h4>
                    <h4>점수는 체크박스의 합산으로 들어갑니다. (20점 부여할 때) 10 + 9 + 1 로 10, 9, 1을 누르면 됩니다.</h4><br><br>
                    <div class="row">

                        <!-- 상점 부여 탭 입니다.
                        버그가 발생할 경우, 벌점 부여 탭도 동일하게 봐주셔야 하며
                        div를 닫을 때 잘 확인해서 닫으시고 탭 작업도 잘 해주시기 바랍니다. -->


                        <div class="col-xs-6 col-sm-offset-1 col-sm-5 col-md-4" style="background-color:white; box-shadow: 0 8px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); min-height:60vh; border-radius: 30px; padding-bottom: 5%; float: center;"  >
                            <h2 style="text-align: center; font-family: 'BM DoHyeon'; margin-bottom:5%;">상점 부여</h2>
                            <form action="teacher-mileage-apply.php" method="POST">
                                <h4 style="text-align:center;">사유 선택</h4>
                                <h6 style="text-align:center;">기타를 제외하고 점수는 학칙대로 자동 선택됩니다. </h6>
                                <select class="form-control" id='minusstep3' name="type1" style= "z-index: 2;" onchange="categoryChange2()">
                                        <option style=" z-index: 2;" value='모범' selected>모범</option>
                                        <option style=" z-index: 2;"value='기타'>기타</option>
                                </select>
                                <input type="text" class="form-control" name="description1" placeholder="사유를 입력하세요" id="selboxDirect2" style="margin-top:2%"/>
                                <select class="form-control" style="margin-top:2%" id='minusstep4' name="description2">
                                        <option style=" z-index: 2;" value='수업태도 모범(3)'>수업태도 모범(3)</option>
                                        <option style=" z-index: 2;" value='생활실 및 면학실 청결 정리정돈 모범(3)'>생활실 및 면학실 청결 정리정돈 모범(3)</option>
                                        <option style=" z-index: 2;" value='봉사 수범(3)'>봉사 수범(3)</option>
                                        <option style=" z-index: 2;" value='생활태도 모범(5)'>생활태도 모범(5)</option>
                                </select>
                                <input type="text" class="form-control" name="descript1" id="descript1" placeholder="세부 사유를 입력하세요." style="margin-top:2%"/>
                                <br>
                                <div style="text-align:center;">
                                    <h4 style="text-align:center;">점수 선택</h4>
                                        <input type='checkbox' name="point[]" id="p0" value="0" hidden checked/>
                                        <input type='checkbox' name="point[]" id="p1" value="1"/>
                                        <input type='checkbox' name="point[]" id="p2" value="2"/>
                                        <input type='checkbox' name="point[]" id="p3" value="3" checked/>
                                        <input type='checkbox' name="point[]" id="p4" value="4"/>
                                        <input type='checkbox' name="point[]" id="p5" value="5"/><br>
                                        <input type='checkbox' name="point[]" id="p6" value="6"/>
                                        <input type='checkbox' name="point[]" id="p7" value="7"/>
                                        <input type='checkbox' name="point[]" id="p8" value="8"/>
                                        <input type='checkbox' name="point[]" id="p9" value="9"/>
                                        <input type='checkbox' name="point[]" id="p10" value="10"/>
                                </div>
                                <br>
                                <h4 style="text-align:center;">학생 선택</h4>
                                <h6 style="text-align:center;">학년반번호 입력 혹은 이름 입력 시 자동완성이 활성화됩니다.</h6>
                                <h6 style="text-align:center;">자동 완성한 결과를 추가하면 아래에 학생이 추가됩니다.</h6><br>
                                <div class="row" style="margin-bottom:3%;">
                                    <div class="col-md-offset-2 col-md-6" id="addstudent-plus">
                                        <input type="text" id="plusstudent" class="form-control" placeholder="ex) 1101 or 홍길동" maxlength="4">
                                        <!-- onKeyPress="if( event.keyCode==13 ){Add();}-->
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" style="margin-top:2%;" id="addplus"></button>
                                    </div>
                                </div>
                                <div id="list" style="margin-bottom: 5%; text-align:center;">
                                    <textarea style="display:none" id="plusstudentlist" name="plusstudentlist" value=""></textarea>
                                </div>
                                <div style="text-align: center;">
                                    <button class="btn btn-success" type="submit">완료</button>
                                </div>
                            </form>
                        </div>
                        <?php
                         $searchsql = mysqli_query($connect, "SELECT * FROM user WHERE IsTeacher = 0") or die(mysqli_error($connect));
                         $array = array();
                         $array2 = array();
                         while ($row = mysqli_fetch_array($searchsql)){
                            array_push($array, $row[1]." ".$row[2]);
                            array_push($array2, $row[2]);
                         }
                        ?>
                        <script>
                        $( "#minusstep3" ).click( function() {
                            let gitar = $("select[name=type1]").val();
                            if(gitar == "기타"){
                                $("input:checkbox[id='p"+1+"']").prop("checked", false);
                                $("input:checkbox[id='p"+2+"']").prop("checked", false);
                                $("input:checkbox[id='p"+3+"']").prop("checked", false);
                                $("input:checkbox[id='p"+4+"']").prop("checked", false);
                                $("input:checkbox[id='p"+5+"']").prop("checked", false);
                                $("input:checkbox[id='p"+6+"']").prop("checked", false);
                                $("input:checkbox[id='p"+7+"']").prop("checked", false);
                                $("input:checkbox[id='p"+8+"']").prop("checked", false);
                                $("input:checkbox[id='p"+9+"']").prop("checked", false);
                                $("input:checkbox[id='p"+10+"']").prop("checked", false);
                            }
                            else{ 
                                $("input:checkbox[id='p"+3+"']").prop("checked", true);

                            }
                            
                        });  
                        $( "#minusstep4" ).click( function() {
                            $("input:checkbox[id='p"+1+"']").prop("checked", false);
                            $("input:checkbox[id='p"+2+"']").prop("checked", false);
                            $("input:checkbox[id='p"+3+"']").prop("checked", false);
                            $("input:checkbox[id='p"+4+"']").prop("checked", false);
                            $("input:checkbox[id='p"+5+"']").prop("checked", false);
                            $("input:checkbox[id='p"+6+"']").prop("checked", false);
                            $("input:checkbox[id='p"+7+"']").prop("checked", false);
                            $("input:checkbox[id='p"+8+"']").prop("checked", false);
                            $("input:checkbox[id='p"+9+"']").prop("checked", false);
                            $("input:checkbox[id='p"+10+"']").prop("checked", false);
                            let gitar = $("select[name=type1]").val();
                            if(gitar != "기타"){
                                let str = $("select[name=description2]").val();
                                console.log(str);
                                star = str.split("(");
                                starwow = star[1].split(")");
                                console.log(starwow[0]);

                                $("input:checkbox[id='p"+starwow[0]+"']").prop("checked", true);
                            }
                        });  

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
                        var available2 = <?php echo json_encode($array2)?>; 
                        $( "#addplus" ).click( function() {
                            var value = $("#plusstudent").val();
                            if(value == "" || value == ' ' || value == '  ' || value == '   ' || value == '    '){
                                return false;
                            }
                            if(!available.includes(value)){
                                if(available2.includes(value)){
                                    return false;
                                }
                                else{
                                    alert( '학생이 존재하지 않습니다! 입력값이 제대로 되었는지 확인해주세요. 예) 3206 김채린' );
                                    return false;
                                }
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

                            $("#plusstudent").keydown(function (key) {
                                if (key.keyCode == 13) { // 엔터키면
                                    $("#addplus").trigger("click"); // 암호에 포커스
                                }
                            });

                        // function Add(){
                        //     var value = $("#plusstudent").val();
                        //     if(value == "" || value == ' ' || value == '  ' || value == '   ' || value == '    '){
                        //         return false;
                        //     }
                        //     if(!available.includes(value)){
                        //         alert( '학생이 존재하지 않습니다! 입력값이 제대로 되었는지 확인해주세요. 예) 3206 김채린' );
                        //         return false;
                        //     }
                        //     $('#list').append('<div class="pluslist" id="'+value+'">'+ value +"<button type='button' id='delplus' class='"+value+"'> </button>"+'</div>')
                        //     $('#plusstudent').val("");
                        // }
                        // $('input[type="text"]').keydown(function() {
                        //     if (event.keyCode === 13) {
                        //         event.preventDefault();
                        //     }
                        // });
                        </script>



                        <!-- 벌점 부여 탭 입니다.
                        버그가 발생할 경우, 상점 부여 탭도 동일하게 봐주셔야 하며
                        div를 닫을 때 잘 확인해서 닫으시고 탭 작업도 잘 해주시기 바랍니다. -->
                    


                        <div class="col-xs-6 col-sm-5 col-md-offset-2 col-md-4"" style="background-color:white; box-shadow: 0 8px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); min-height:60vh; border-radius: 30px; padding-bottom: 5%; float: center;""  >
                            <h2 style="text-align: center; font-family: 'BM DoHyeon'; margin-bottom:5%">벌점 부여</h2>
                            <form action="teacher-mileage-apply-minus.php" method="POST">
                                <h4 style="text-align:center;">사유 선택</h4>
                                <h6 style="text-align:center;">기타를 제외하고 점수는 학칙대로 자동 선택됩니다. </h6>
                                    <select class="form-control" id='minusstep1' style= "z-index: 2;" onchange="categoryChange()" name="type2">
                                        <option style=" z-index: 2;" value='일과 전반' selected>일과 전반</option>
                                        <option style=" z-index: 2;"value='조조'>조조</option>
                                        <option style=" z-index: 2;"value='면학실'>면학실</option>
                                        <option style=" z-index: 2;"value='기타'>기타</option>
                                    </select>
                                    <input type="text" class="form-control" placeholder="사유를 입력하세요" id="selboxDirect" style="margin-top:2%" name="description1"/ >
                                    <select class="form-control" style="margin-top:2%" id='minusstep2' name="descriptionmi">
                                        <option style=" z-index: 2;"value='식사 무단 불참(3)'>식사 무단 불참(3)</option>
                                        <option style=" z-index: 2;"value='지시사항 불이행(3)'>지시사항 불이행(3)</option>
                                        <option style=" z-index: 2;"value='생활태도 불량(3)'>생활태도 불량(3)</option>
                                        <option style=" z-index: 2;"value='생활실 정리정돈 불량(3)'>생활실 정리정돈 불량(3)</option>
                                        <option style=" z-index: 2;"value='휴게실 외 무단 식음료 취식(5)'>휴게실 외 무단 식음료 취식(5)</option>
                                        <option style=" z-index: 2;"value='각종 시설 사용 불량, 과실 파손 등(5)'>각종 시설 사용 불량, 과실 파손 등(5)</option>
                                        <option style=" z-index: 2;"value='금지된 물품의 반입 및 사용(10)'>금지된 물품의 반입 및 사용(10)
                                    </select>
                                    <input type="text" class="form-control" name="descript2" id="descript2" placeholder="세부 사유를 입력하세요." style="margin-top:2%"/>
                                    <br>
                                <div style="text-align:center;">
                                    <h4 style="text-align:center;">점수 선택</h4>
                                        <input type='checkbox' name="point[]" id="m0" value="0" hidden checked/>
                                        <input type='checkbox' name="point[]" id="m1" value="1"/>
                                        <input type='checkbox' name="point[]" id="m2" value="2"/>
                                        <input type='checkbox' name="point[]" id="m3" value="3" checked/>
                                        <input type='checkbox' name="point[]" id="m4" value="4"/>
                                        <input type='checkbox' name="point[]" id="m5" value="5"/><br>
                                        <input type='checkbox' name="point[]" id="m6" value="6"/>
                                        <input type='checkbox' name="point[]" id="m7" value="7"/>
                                        <input type='checkbox' name="point[]" id="m8" value="8"/>
                                        <input type='checkbox' name="point[]" id="m9" value="9"/>
                                        <input type='checkbox' name="point[]" id="m10" value="10"/>
                                </div>
                                <br>
                                <h4 style="text-align:center;">학생 선택</h4>
                                <h6 style="text-align:center;">학년반번호 입력 혹은 이름 입력 시 자동완성이 활성화됩니다.</h6>
                                <h6 style="text-align:center;">자동 완성한 결과를 추가하면 아래에 학생이 추가됩니다.</h6><br>
                                <div class="row" style="margin-bottom:3%;">
                                    <div class="col-md-offset-2 col-md-6" id="addstudent-minus">
                                        <input type="text" id="minusstudent" class="form-control" placeholder="ex) 1101 or 홍길동" maxlength="4" >
                                        <!-- onKeyPress="if( event.keyCode==13 ){Add();}-->
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" style="margin-top:2%;" id="addminus"></button>
                                    </div>
                                </div>
                                <div id="minuslist" style="margin-bottom: 5%; text-align:center;">
                                    <textarea style="display:none" id="minusstudentlist" name="minusstudentlist" value=""></textarea>
                                </div>
                                <div style="text-align: center;">
                                    <button class="btn btn-danger" type="submit">완료</button>
                                </div>
                            </form>
                        </div>
                        <script>
                        $( "#minusstep1" ).click( function() {
                            let gitar = $("select[name=type2]").val();
                            if(gitar == "기타"){
                                $("input:checkbox[id='m"+1+"']").prop("checked", false);
                                $("input:checkbox[id='m"+2+"']").prop("checked", false);
                                $("input:checkbox[id='m"+3+"']").prop("checked", false);
                                $("input:checkbox[id='m"+4+"']").prop("checked", false);
                                $("input:checkbox[id='m"+5+"']").prop("checked", false);
                                $("input:checkbox[id='m"+6+"']").prop("checked", false);
                                $("input:checkbox[id='m"+7+"']").prop("checked", false);
                                $("input:checkbox[id='m"+8+"']").prop("checked", false);
                                $("input:checkbox[id='m"+9+"']").prop("checked", false);
                                $("input:checkbox[id='m"+10+"']").prop("checked", false);
                            }
                            else if(gitar == "조조"){
                                $("input:checkbox[id='m"+1+"']").prop("checked", false);
                                $("input:checkbox[id='m"+2+"']").prop("checked", false);
                                $("input:checkbox[id='m"+3+"']").prop("checked", false);
                                $("input:checkbox[id='m"+4+"']").prop("checked", false);
                                $("input:checkbox[id='m"+5+"']").prop("checked", false);
                                $("input:checkbox[id='m"+6+"']").prop("checked", false);
                                $("input:checkbox[id='m"+7+"']").prop("checked", false);
                                $("input:checkbox[id='m"+8+"']").prop("checked", false);
                                $("input:checkbox[id='m"+9+"']").prop("checked", false);
                                $("input:checkbox[id='m"+10+"']").prop("checked", false); 
                                $("input:checkbox[id='m"+10+"']").prop("checked", true);

                            }
                            else if(gitar == "일과 전반"){ 
                                $("input:checkbox[id='m"+1+"']").prop("checked", false);
                                $("input:checkbox[id='m"+2+"']").prop("checked", false);
                                $("input:checkbox[id='m"+3+"']").prop("checked", false);
                                $("input:checkbox[id='m"+4+"']").prop("checked", false);
                                $("input:checkbox[id='m"+5+"']").prop("checked", false);
                                $("input:checkbox[id='m"+6+"']").prop("checked", false);
                                $("input:checkbox[id='m"+7+"']").prop("checked", false);
                                $("input:checkbox[id='m"+8+"']").prop("checked", false);
                                $("input:checkbox[id='m"+9+"']").prop("checked", false);
                                $("input:checkbox[id='m"+10+"']").prop("checked", false);
                                $("input:checkbox[id='m"+3+"']").prop("checked", true);

                            }
                            else if(gitar == "면학실"){
                                $("input:checkbox[id='m"+1+"']").prop("checked", false);
                                $("input:checkbox[id='m"+2+"']").prop("checked", false);
                                $("input:checkbox[id='m"+3+"']").prop("checked", false);
                                $("input:checkbox[id='m"+4+"']").prop("checked", false);
                                $("input:checkbox[id='m"+5+"']").prop("checked", false);
                                $("input:checkbox[id='m"+6+"']").prop("checked", false);
                                $("input:checkbox[id='m"+7+"']").prop("checked", false);
                                $("input:checkbox[id='m"+8+"']").prop("checked", false);
                                $("input:checkbox[id='m"+9+"']").prop("checked", false);
                                $("input:checkbox[id='m"+10+"']").prop("checked", false);
                                $("input:checkbox[id='m"+5+"']").prop("checked", true);

                            }                            
                        });  
                        $( "#minusstep2" ).click( function() {
                            $("input:checkbox[id='m"+1+"']").prop("checked", false);
                            $("input:checkbox[id='m"+2+"']").prop("checked", false);
                            $("input:checkbox[id='m"+3+"']").prop("checked", false);
                            $("input:checkbox[id='m"+4+"']").prop("checked", false);
                            $("input:checkbox[id='m"+5+"']").prop("checked", false);
                            $("input:checkbox[id='m"+6+"']").prop("checked", false);
                            $("input:checkbox[id='m"+7+"']").prop("checked", false);
                            $("input:checkbox[id='m"+8+"']").prop("checked", false);
                            $("input:checkbox[id='m"+9+"']").prop("checked", false);
                            $("input:checkbox[id='m"+10+"']").prop("checked", false);
                            let gitar = $("select[name=type2]").val();
                            if(gitar != "기타"){
                                let str = $("select[name=descriptionmi]").val();
                                console.log(str);
                                star = str.split("(");
                                starwow = star[1].split(")");
                                console.log(starwow[0]);

                                $("input:checkbox[id='m"+starwow[0]+"']").prop("checked", true);
                            }
                        });  
                        $(document).on("click","#delminus",function(){
                            var index = $(this).attr("class");
                            remove(index);
                            var listvalue = $('textarea[name=minusstudentlist]').text();
                            var splitedvalue = index.split(' ');
                            var splitedvalue2 = listvalue.replace(splitedvalue[1]+",", "");
                            $('textarea[name=minusstudentlist]').text(splitedvalue2);
                        });

                        var available = <?php echo json_encode($array)?>; 
                        $( "#addminus" ).click( function() {
                            var value = $("#minusstudent").val();
                            if(value == "" || value == ' ' || value == '  ' || value == '   ' || value == '    '){
                                return false;
                            }
                            if(!available.includes(value)){
                                if(available2.includes(value)){
                                    return false;
                                }
                                else{
                                    alert( '학생이 존재하지 않습니다! 입력값이 제대로 되었는지 확인해주세요. 예) 3206 김채린' );
                                    return false;
                                }
                            }
                            $('#minuslist').append($('<div class="minuslist" style="display:inline-block;" id="'+value+'">'+ value +"<button type='button' id='delplus' class='"+value+"'> </button>"+'</div>').fadeIn());
                            $('#minusstudent').val("");
                            var before = $('textarea[name=minusstudentlist]').text();
                            var splitedvalue = value.split(' ');
                            $('textarea[name=minusstudentlist]').text(before + splitedvalue[1] + ",");
                        });  
                        $(function() {
                            $("#minusstudent").autocomplete({
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
                        $("#minusstudent").keydown(function (key) {
                                if (key.keyCode == 13) { // 엔터키면
                                    $("#addminus").trigger("click"); // 암호에 포커스
                                }
                            });
                        // function Add(){
                        //     var value = $("#minusstudent").val();
                        //     if(value == "" || value == ' ' || value == '  ' || value == '   ' || value == '    '){
                        //         return false;
                        //     }
                        //     if(!available.includes(value)){
                        //         alert( '학생이 존재하지 않습니다! 입력값이 제대로 되었는지 확인해주세요. 예) 3206 김채린' );
                        //         return false;
                        //     }
                        //     $('#minuslist').append('<div class="minuslist" id="'+value+'">'+ value +"<button type='button' id='delminus' class='"+value+"'> </button>"+'</div>')
                        //     $('#minusstudent').val("");
                        // }
                        // $('input[type="text"]').keydown(function() {
                        //     if (event.keyCode === 13) {
                        //         event.preventDefault();
                        //     }
                        // });
                        </script>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function categoryChange2() {
                var good = ["수업태도 모범(3)", "생활실 및 면학실 청결 정리정돈 모범(3)", "봉사 수범(3)", "생활태도 모범(5)"];
                var target = document.getElementById("minusstep4");

                if($("#minusstep3").val() == "모범") {
                    var d = good; 
                    $('#selboxDirect2').val("");
                    $('#descript1').show();
                }
                else{
                    $('#descript1').hide();
                    $('#descript1').val("");
                }

                target.options.length = 0;

                for (x in d) {
                    var opt = document.createElement("option");
                    opt.value =  d[x];
                    opt.style = "z-index: 2;"
                    opt.innerHTML = d[x];
                    target.appendChild(opt);
                }
            }
            function categoryChange() {
                var daytime = ["식사 무단 불참(3)", "지시사항 불이행(3)", "생활태도 불량(3)", "생활실 정리정돈 불량(3)", "휴게실 외 무단 식음료 취식(5)", "각종 시설 사용 불량, 과실 파손 등(5)", "생활관 규정시간 외 무단 출입(5)", "금지된 물품의 반입 및 사용(10)"];
                var morning = ["조기운동 무단 불참(10)"];
                var studyroom = ["면학시간 중 무단 기타 장소 사용(5)","면학실 내 음식 음료 반입(5)"];
                var domit = ["생활관 소란 행위(5)","생활실 내 잡담 및 소란, 취침 방해(5)","생활실 내 독서 혹은 공부(10)","취침시간 위반, 배회, 장소 이동, 기타 행위(10)","취침장소 무단 이동(10)"];

                var target = document.getElementById("minusstep2");

                if($("#minusstep1").val() == "일과 전반") var d = daytime; $('#description').val(""); $('#descript2').show();
                if($("#minusstep1").val() == "조조") var d = morning; $('#description').val(""); $('#descript2').show();
                if($("#minusstep1").val() == "면학실") var d = studyroom; $('#description').val(""); $('#descript2').show();
                if($("#minusstep1").val() == "생활관") var d = domit; $('#description').val(""); $('#descript2').show();
                if($("#minusstep1").val() == "기타") {
                    $('#descript2').hide();
                    $('#descript2').val("");
                }

                target.options.length = 0;

                for (x in d) {
                    var opt = document.createElement("option");
                    opt.value =  d[x];
                    opt.style = "z-index: 2;"
                    opt.innerHTML = d[x];
                    target.appendChild(opt);
                }
            }
            $(function(){
                    //직접입력 인풋박스 기존에는 숨어있다가
                    $("#selboxDirect").hide();
                    $("#minusstep1").change(function() {
                        //직접입력을 누를 때 나타남
                    if($("#minusstep1").val() == "기타") {
                        $("#minusstep2").hide();
                        $("#selboxDirect").show();
                    }  else {
                        $("#selboxDirect").hide();
                        $("#minusstep2").show();
                    }
                    }) 
                    $("#selboxDirect2").hide();
                    $("#minusstep3").change(function() {
                        //직접입력을 누를 때 나타남
                    if($("#minusstep3").val() == "기타") {
                        $("#minusstep4").hide();
                        $("#selboxDirect2").show();
                    }  else {
                        $("#selboxDirect2").hide();
                        $("#minusstep4").show();
                    }
                    }) 
                }); 
        </script>
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>
        <script src="lib/jquery.counterup.js"></script>
    </body>
</html>