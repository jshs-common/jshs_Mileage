<?php
    function EchoAd()
    {
        echo '
        <div class="hidden-xs" style="text-align: center; height: 0px; margin-top: 20px;" id="ad">
        <iframe width="728" height="90" allowtransparency="true" src="https://tab2.clickmon.co.kr/pop/wp_ad_728.php?PopAd=CM_M_1003067%7C%5E%7CCM_A_1056281%7C%5E%7CAdver_M_1046207&mon_rf=REFERRER_URL" frameborder="0" scrolling="no"></iframe>
        </div>
        ';
        echo "
        <script>
          var ad = document.getElementById('ad');
          var bodyheight = document.getElementsByTagName('body')[0].offsetHeight;
          var windowheight = window.innerHeight;
          if(bodyheight < windowheight)
          {
            ad.style.marginTop = (windowheight - bodyheight + 20 - 90).toString() + 'px';
          }
        </script>
        ";
    }
?>