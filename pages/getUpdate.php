<?php
$like = '';
$follows = "";
$EMAIL = "dragon5347381@yandex.com";
$PASSWORD = "dragon5347381!123";
function cURL($url, $header=NULL, $cookie=NULL, $p=NULL){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, $header);
    curl_setopt($ch, CURLOPT_NOBODY, $header);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    if ($p) {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $p);
    }
    $result = curl_exec($ch);

    if ($result) {
        return $result;
    } else {
        return curl_error($ch);
    }
    curl_close($ch);
}
$data = "";
$n = 0;
$n1 = 0;
$date = new DateTime();
$date1 = new DateTime();

if(isset($_POST['current']) && isset($_POST['current_follow'])){
    $pagename = $_POST['page_name']; 
    $current = $_POST['current'];
    $current_follow = $_POST['current_follow'];
    $a = cURL("https://login.facebook.com/login.php?login_attempt=1",true,null,"email=$EMAIL&pass=$PASSWORD");
    preg_match('%Set-Cookie: ([^;]+);%',$a,$b);
    $c = cURL("https://login.facebook.com/login.php?login_attempt=1",true,$b[1],"email=$EMAIL&pass=$PASSWORD");

    preg_match_all('%Set-Cookie: ([^;]+);%',$c,$d);
    $cookie = "";

    for($i=0;$i<count($d[0]);$i++)
        $cookie.=$d[1][$i].";";

    $homeurl = "https://www.facebook.com/".$pagename."/";
    $dtsgurl = "https://www.facebook.com/pg/".$pagename."/posts/?ref=page_internal";
    $homepage = cURL($homeurl, null, $cookie, null);
    $dtsgpage = cURL($dtsgurl, null, $cookie, null);
    preg_match("/input type=\"hidden\" name=\"fb_dtsg\" value=\"(.*?)\"/", $dtsgpage, $fb_dtsg);

    preg_match("/<div class=\"_4bl9\"><div>(.*?) people like this<\/div/", $homepage, $xxx);
    preg_match("/<\/div><div class=\"_4bl9\"><div>(.*?)people like this/", $xxx[0], $zzzz);


    preg_match("/<div class=\"_4bl9\"><div>(.*?) people follow this<\/div/", $homepage, $yyy);
    preg_match("/<\/div><div class=\"_4bl9\"><div>(.*?)people follow this/", $yyy[0], $follow_zzz);
    preg_match("/people like this(.*?)people follow this/", $follow_zzz[0], $follow_count);
    preg_match("/<\/div><div class=\"_4bl9\"><div>(.*?)people follow this/", $follow_count[0], $follow_count_);
    // preg_match("/>(.*?)people follow this</", $xxx[1], $follow_zzz);
    // Print the entire match result
    // print $xxx[0]."</div></div></div></div></div>";
    // print $follow_count_[1];1,234
    $like = $zzzz[1];
    $like = str_replace(",", "", trim($like));
    $follows = $follow_count_[1];
    $follows = str_replace(",","", trim($follows));

    if($current == $like && $current_follow == $follows){
        echo '0';
    }else{
        echo '1';
    }

    // echo $like;
    // $follows = $follow_count_[1];

}

?>