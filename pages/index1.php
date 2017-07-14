<?php
$like = '';
$follows = "";
$EMAIL = "dragon5347381@yandex.com";
$PASSWORD = "dragon5347381!123";
ini_set('max_execution_time', 0);
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
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_2) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.63 Safari/535.7');

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

// if(isset($_GET['id'])){
    // $id = "hotelkennedy_236251412178";//$_GET['id'];AubergeMichelDoyon_1536406206627708
    $id = "AubergeMichelDoyon_1536406206627708";//$_GET['id'];
    $idarr = split("\_", $id);
    $facebook_page_name = $idarr[0];
    $facebook_page_id = $idarr[1];
    $a = cURL("https://login.facebook.com/login.php?login_attempt=1",true,null,"email=$EMAIL&pass=$PASSWORD");
    preg_match('%Set-Cookie: ([^;]+);%',$a,$b);
    $c = cURL("https://login.facebook.com/login.php?login_attempt=1",true,$b[1],"email=$EMAIL&pass=$PASSWORD");

    preg_match_all('%Set-Cookie: ([^;]+);%',$c,$d);
    $cookie = "";

    for($i=0;$i<count($d[0]);$i++)
        $cookie.=$d[1][$i].";";

    $homeurl = "https://www.facebook.com/".$facebook_page_name."/";
    $dtsgurl = "https://www.facebook.com/pg/".$facebook_page_name."/posts/?ref=page_internal";
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
    // echo $like;
    $follows = $follow_count_[1];
    $follows = str_replace(",","", trim($follows));
    $url_ = "https://www.facebook.com/pages/admin/people_and_other_pages/entquery/?query_edge_key=PEOPLE_WHO_LIKE_THIS_PAGE&page_id=".$facebook_page_id."&offset=0&limit=10000&dpr=1";
    // $url_ = "https://www.facebook.com/pages/admin/people_and_other_pages/entquery/?query_edge_key=PEOPLE_WHO_LIKE_THIS_PAGE&page_id=236251412178&offset=0&limit=100&dpr=1";
    //// $url_follows = "https://www.facebook.com/pages/admin/people_and_other_pages/entquery/?query_edge_key=PEOPLE_WHO_FOLLOW_THIS_PAGE&page_id=236251412178&offset=0&limit=100&dpr=1";
    $page_html =  cURL($url_,null,$cookie,"__a=1&__af=iw&fb_dtsg=".$fb_dtsg[1]);
    //// $page_html_follows =  cURL($url_follows,null,$cookie,"__a=1&__af=iw&fb_dtsg=".$fb_dtsg[1]);

    // $page_html =  cURL("https://www.facebook.com/hotelkennedy/settings/?tab=people_and_other_pages&ref=page_edit",null,$cookie,"__a=1&__af=iw&fb_dtsg=AQFrzpXzEF9-:AQGB8Rv9niE9");
    // https://www.facebook.com/pages/admin/people_and_other_pages/entquery/?query_edge_key=PEOPLE_WHO_LIKE_THIS_PAGE&page_id=236251412178&offset=0&limit=100&dpr=1

    $page_html = substr($page_html, 9);
    ////$page_html_follows = substr($page_html_follows, 9);
    $data = json_decode($page_html);
    ////$data_follows = json_decode($page_html_follows);
    $n = count($data->payload->data);
    ////$n1 = count($data_follows->payload->data);
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>

<body>




    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="refresh" content="600">

    <title>FaceBook Statistics</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="../vendor/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="shortcut icon" href="like.png" type="image/png">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="../vendor/raphael/raphael.min.js"></script>
    <script src="../vendor/morrisjs/morris.min.js"></script>
    <script src="../data/morris-data.js"></script>
    <!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
    <style>

        .splitflap {
            margin: 0 auto;
            margin-right: 0;
            -webkit-perspective-origin: top center;
            -moz-perspective-origin: top center;
            -ms-perspective-origin: top center;
            perspective-origin: top center;

            -webkit-perspective: 900px;
            -moz-perspective: 900px;
            -ms-perspective: 900px;
            perspective: 900px;
        }
    </style>
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->


        <div id="page-wrapper" style="margin:30px auto 0 auto;">            

<!--             <div class="row">
                <div class="col-lg-3 col-md-3">
                    <form role="form">
                        <div class="form-group input-group">
                            <div class="form-group">
                                <select class="form-control" id="id_sel">
                                    <option value=''>Select FaceBook ID</option>    
                                    <option value="hotelkennedy_236251412178">hotelkennedy</option>
                                </select>
                                <script type="text/javascript">

                                    var element = document.getElementById('id_sel');
                                    element.value = '<?php if(isset($_GET["id"])){echo $_GET["id"];}else {echo "";} ?>';
                                </script>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-3 col-md-3">
                    <form role="form">
                        <div class="form-group">
                            <input id="selected_id" class="form-control" placeholder="Searched FaceBook ID" disabled>
                            <script type="text/javascript">
                                var selid = $("#id_sel option:selected").text();
                                $("#selected_id").val('https://www.facebook.com/' + selid);
                            </script>
                        </div>
                    </form>
                </div>
            </div> -->

            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="panel panel-default">
                        <p>
                            <img src="facebook.png" style="width:40%;"/>
                            <a href="http://www.facebook.com/hotelkennedy"><img src="hotelkennedy.png" style="width:59%;"/></a>
                        </p>
                        
                        <div class="panel-body panel-primary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-comments fa-5x"></i>
                                        <h3 style="margin-top:-8px; margin-bottom:0px;">Likes</h3>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">
                                            <div class="do-splitflap"><?php echo "00".$like; ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel-body panel-green">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-rss fa-5x"></i>                                        
                                        <h3 style="margin-top:-8px; margin-bottom:0px;">Fans</h3>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">
                                            <div class="do-splitflap"><?php echo "00".$follows; ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel-body panel-primary">
                            <div class="panel-heading">
                                <p style="text-align:center;"><img src="like_S.png" style="width: 100%;height: 130px;"/></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="panel ">
                        <p style="text-align:center;"><img src="like_S.png" style="width: 88%;height: 183px;"/></p>
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-default">
<!--                         <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> People Who Like This Page
                        </div> -->
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!--<div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="table-responsive">-->
                                        <table width="100%" class="table table-bordered table-hover table-striped" id="dataTables-example">
                                            <thead style="display:none;">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Name</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    for($i = 0; $i < $n; $i++){
                                                        $f_no = $i + 1;
                                                        $f_name = $data->payload->data[$i]->profile->name;
                                                        $date->setTimestamp($data->payload->data[$i]->timestamp);
                                                        $f_date = $date->format('Y-m-d');
                                                        $f_time = $date->format('H:i:s');
                                                        echo '<tr><td>'.$f_no.'</td><td>'.$f_name.'</td><td>'.$f_date.'</td><td>'.$f_time.'</td></tr>';
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    <!--</div>
                                </div>
                            </div>-->
                            <!-- /.row -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
            </div>
            <!-- /.row -->
            
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->


<script src="js/jquery/jquery.splitflap.js"></script>
<script>
    jQuery(document).ready(function() {

        $('#dataTables-example').DataTable({
            bFilter: false, bInfo: false, bLengthChange:false,
            responsive: true,
            oLanguage: {
                oPaginate: {
                    sNext: '<span class="pagination-default"></span><span class="pagination-fa"><i class="fa fa-chevron-right" ></i></span>',
                    sPrevious: '<span class="pagination-default"></span><span class="pagination-fa"><i class="fa fa-chevron-left" ></i></span>'
                }
            }
        });
        $('#dataTables-example2').DataTable({
            responsive: true
        });
        // $("#id_sel").change( function(){
        //     var val = $(this).val();
        //     if(val == ""){
        //         alert("Select an item");    
        //     }else{
        //         window.location = "index.php?id=" + val;
        //     }
        // });
        $('.do-splitflap').splitFlap({
                onComplete: function(){
                    var audio = new Audio('sound.mp3');
                    audio.play();
                }
        });
        $('#dataTables-example_wrapper div.row:last div.col-sm-6:first').remove();

    });
</script>
<script>
        var incCount = function(){
            $.ajax({
                url: "getUpdate.php",
                type: "post",
                data: {
                    current: '<?php echo $like; ?>',
                    current_follow: '<?php echo $follows; ?>',
                    page_name: '<?php echo $facebook_page_name; ?>'
                },
                dataType: 'text',
                success: function(data){
                    if(data != '0'){
                        window.location.href=window.location.href;
                    }else {
                        console.log("Nothing is the Updating data.");
                    }
                },
                failure: function(data){
                    alert(data);
                }
            });
        }
        setInterval(incCount, 60000);
</script>


</body>

</html>
