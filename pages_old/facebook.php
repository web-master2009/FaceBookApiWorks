<?php

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

$a = cURL("https://login.facebook.com/login.php?login_attempt=1",true,null,"email=$EMAIL&pass=$PASSWORD");
preg_match('%Set-Cookie: ([^;]+);%',$a,$b);
$c = cURL("https://login.facebook.com/login.php?login_attempt=1",true,$b[1],"email=$EMAIL&pass=$PASSWORD");

preg_match_all('%Set-Cookie: ([^;]+);%',$c,$d);
$cookie = "";

for($i=0;$i<count($d[0]);$i++)
    $cookie.=$d[1][$i].";";

$homeurl = "https://www.facebook.com/hotelkennedy/";
$homepage = cURL($homeurl, null, $cookie, null);

preg_match("/input type=\"hidden\" name=\"fb_dtsg\" value=\"(.*?)\"/", $homepage, $fb_dtsg);
preg_match("/<div class=\"_4bl9\">(.*?)<\/div/", $homepage, $xxx);
preg_match("/>(.*?)people like this and (.*?) people follow this</", $xxx[1], $zzz);
// Print the entire match result
print($zzz[1].":::".$zzz[2]);
$url_ = "https://www.facebook.com/pages/admin/people_and_other_pages/entquery/?query_edge_key=PEOPLE_WHO_LIKE_THIS_PAGE&page_id=236251412178&offset=0&limit=100&dpr=1";
$page_html =  cURL($url_,null,$cookie,"__a=1&__af=iw&fb_dtsg=".$fb_dtsg[1]);

// $page_html =  cURL("https://www.facebook.com/hotelkennedy/settings/?tab=people_and_other_pages&ref=page_edit",null,$cookie,"__a=1&__af=iw&fb_dtsg=AQFrzpXzEF9-:AQGB8Rv9niE9");
// https://www.facebook.com/pages/admin/people_and_other_pages/entquery/?query_edge_key=PEOPLE_WHO_LIKE_THIS_PAGE&page_id=236251412178&offset=0&limit=100&dpr=1

$page_html = substr($page_html, 9);
$data = json_decode($page_html);
$n = count($data->payload->data);
$date = new DateTime();
for($i = 0; $i < $n; $i++){
    print $data->payload->data[$i]->profile->name;
    print '  :::   ';
    
    $date->setTimestamp($data->payload->data[$i]->timestamp);
    print $date->format('Y-m-d') . "   :::   ";
    print $date->format('H:i:s') . "<br/>";
}
?>