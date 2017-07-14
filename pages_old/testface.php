<?php
  // $json = file_get_contents('https://graph.facebook.com/hotelkennedy/insights/page_fans/lifetime?access_token=215688928923178|95fdb6301e920f453745fb15d71ae35d');
  // $obj = json_decode($json);
  // $new_facebook_followers= $obj->data[0]->values[0]->value;
  // echo $new_facebook_followers;	
	
    $id = $_GET['id'];
    $url = "";
    if($id == 1){
        $url = "https://graph.facebook.com/hotelkennedy/?fields=fan_count&access_token=215688928923178|95fdb6301e920f453745fb15d71ae35d";
    }else if($id == 2){
        $url = "https://graph.facebook.com/golddragoncheng/?fields=fan_count&access_token=215688928923178|95fdb6301e920f453745fb15d71ae35d";
    }

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);   
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($curl);  
    curl_close($curl);
    $details = json_decode($result,true);
    $like = $details['fan_count'];
?>