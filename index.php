

<?php
//echo "count is (".count($_GET).")<br>";
//print_r($_GET);
$key1 = "xxx";
$key2 = "yyy";
$more_info = "<br>For more information call 8421";

$sms1_confirmation_code = "Your code is $key1 please enter it in the web site";
$sms2_confirmation_congra = "Congratulation you have been register on HelloGebeya you can now post your profile or adds on hellogebeya.com or call 8421.";
$sms3_confirmation_inform = " As a member of hellogebeya,com we would also like to inform you that you can fully benefit from our other helloservices like HelloDoctor Call 8896 in case you need Medical Advise.";
$sms4_point_transfer_sender = "You have sent $key1 points to $key2";
$sms5_point_transfer_reciever = "You have gotten $key1 points from $key2";
$sms6_publish_add = "Congratulation you have published the following Add item  $key1 Title : $key2";
$sms7_unpublish_add = "We confirm you that you have unpublished the following Add item $key1 Title : $key2";
$sms8_modify_add = "We confirm you that you have modified the following add item $key1 Title : $key2";
$sms9_disable_account = "We inform you that your account have been disabled for none conformity with the terms and conditions of HelloGabeya";
$sms10_enable_account = "We are happy to inform you that your account has been re-anabled";
$sms11_disable_add = "We inform you that your add item $key1, title $key2 have been deactivated for none conformity with our term and conditions";
$sms12_enable_add = "We inform you that your add item $key1, title $key2 have been reactivated";

$help = "Usage :  index.php?<b>p</b>=PHONE_NUMBER&<b>s</b>=CASE_NUMBER(1-12)<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;For case 1 :  Additional Parameter <b>a</b><br>For Cases 4-8 and 11-12 :  Additional Parameters <b>a</b> and <b>b</b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;For the rest Cases :  No Additional Parameters";

$arr  = array(
	'1' => $sms1_confirmation_code.$more_info,
	'2' => $sms2_confirmation_congra.$more_info,
	'3' => $sms3_confirmation_inform.$more_info,
	'4' => $sms4_point_transfer_sender.$more_info,
	'5' => $sms5_point_transfer_reciever.$more_info,
	'6' => $sms6_publish_add.$more_info,
	'7' => $sms7_unpublish_add.$more_info,
	'8' => $sms8_modify_add.$more_info,
	'9' => $sms9_disable_account.$more_info,
	'10' => $sms10_enable_account.$more_info,
	'11' => $sms11_disable_add.$more_info,
	'12' => $sms12_enable_add.$more_info,
	);

$p = $_GET['p'];
$s = $_GET['s'];

$a = $_GET['a'];
$b = $_GET['b'];

if( empty($p) || empty($s) ){
echo("Empty Parameter: <b>p</b>,<b>s</b><br><br><br>".$help);
//send_sms($a,$arr['10']);
}else{

	$sms_txt = "";

	if ($s=="2" || $s=="3" || $s=="9" || $s=="10") {
	        $sms_txt = $arr[$s];
	}elseif($s == "1" && !empty($a)){
			$sms_txt = str_replace($key1, $a, $arr[$s]);
	}elseif(  ($s == "4" ||$s == "5" ||$s == "6" ||$s == "7"||$s == "8"||$s == "11"||$s == "12") &&  !empty($a) && !empty($b)){
			$sms_txt_temp = str_replace($key1, $a, $arr[$s]);
			$sms_txt = str_replace($key2, $b, $sms_txt_temp);
	}else{
		echo($help);
		exit();
	}

		send_sms($p,$sms_txt);

}

//count($_GET)==2


function send_sms($phone_number,$sms_text)
{
$phone_number = rawurlencode($phone_number);
$sms_text = rawurlencode($sms_text);
$curl = curl_init();

curl_setopt_array($curl, array(
CURLOPT_RETURNTRANSFER => 1,
CURLOPT_URL => "http://192.168.1.21:13013/cgi-bin/sendsms?username=simple&password=elpmis&to=$phone_number&text=$sms_text",
CURLOPT_USERAGENT => 'Codular Sample cURL Request')
);

$resp = curl_exec($curl);
$curl_return_code  = curl_getinfo($curl, CURLINFO_HTTP_CODE);

curl_close($curl);
//echo "Code is ".$curl_return_code."<br>";

if($curl_return_code == "202"){

if($resp == "0: Accepted for delivery"){
echo("200 (SMS Requested)<br>");
http_response_code(200);
}else{
echo("202 (ETC is Unreachable)<br>");
http_response_code(202);
}

}elseif($curl_return_code == "0"){
echo("503 (SMS Gateway is Unavailable)<br>");
http_response_code(503);
}else {
echo($curl_return_code." ".$resp." (new case)<br>");
}
#echo $p." ".$c." ".$resp;
}


?>
