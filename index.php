

<?php
//echo "count is (".count($_GET).")<br>";
//print_r($_GET);
$key1 = "xxx";
$key2 = "yyy";

$keyA = "zzz";
$keyB = "fff";
$keyC = "jjj";

$more_info = "\nFor more information call 8421";

$sms1_confirmation_code = "Your code is $key1. Please enter it on the website";
$sms2_confirmation_congra = "Congratulations! You have been registered on HelloGebeya. You can now post ads on hellogebeya.com";
$sms3_confirmation_inform = "Please check out our other HelloService: 'HelloDoctor'. Call 8896 for 24/7 Medical Advice, Home Visit and Ambulance Transportation";
$sms4_point_transfer_sender = "You have sent $key1 points to $key2";
$sms5_point_transfer_reciever = "You have received $key1 points from $key2";
$sms6_publish_add = "Congratulations! You have published the following Ad item  $key1 Title: $key2";
$sms7_unpublish_add = "The following Ad item $key1 Title: $key2 has been UNPUBLISHED";
$sms8_modify_add = "The following Ad item $key1 Title: $key2 has been MODIFIED";
$sms9_disable_account = "Your Account has been DISABLED for non-conformity with the terms and conditions of HelloGebeya";
$sms10_enable_account = "We are happy to inform you that your account has been RE-ENABLED";
$sms11_disable_add = "Your Ad item $key1 Title: $key2 has been DEACTIVATED non-conformity with the terms and conditions of HelloGebeya";
$sms12_enable_add = "We are happy to inform you that your Ad item $key1 Title: $key2 has been RE-ENABLED";
$sms13_point_transfer_receiver_2 = "You have received $key1 points from $key2. To get your points, please create an account on HelloGebeya.com";
$sms14_account_created = "Your account has been created. Your password is $key1";
$sms15_password_changed = "Your password has been changed to $key1";
$sms17_please_call = "Please call me $key1 regarding your job ad $keyA, $keyB, $keyC";

$help = "Usage :  index.php?<b>p</b>=PHONE_NUMBER&<b>s</b>=CASE_NUMBER(1-14)<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;For case 1 and 14 :  Additional Parameter <b>a</b><br>For Cases 4-8 and 11-13 :  Additional Parameters <b>a</b> and <b>b</b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;For the rest Cases :  No Additional Parameters";

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
	'13' => $sms13_point_transfer_receiver_2,
	'14' => $sms14_account_created,
	'15' => $sms15_password_changed,
	'17' => $sms17_please_call
	);

$p = @$_GET['p'];
$s = @$_GET['s'];

$a = @$_GET['a'];
$b = @$_GET['b'];

logg("Start");

if( empty($p) || empty($s) ){
echo("Empty Parameter: <b>p</b>,<b>s</b><br><br><br>".$help);
//send_sms($a,$arr['10']);
logg("Empty paramets");
}else{
logg("Not empty Parameters");
	$sms_txt = "";

	if ($s=="2" || $s=="3" || $s=="9" || $s=="10") {
	        $sms_txt = $arr[$s];
	}elseif(($s == "1" || $s == "14" || $s == "15") && !empty($a)){
			$sms_txt = str_replace($key1, $a, $arr[$s]);
	}elseif(  ($s == "4" ||$s == "5" ||$s == "6" ||$s == "7"||$s == "8"||$s == "11"||$s == "12"||$s == "13") &&  !empty($a) && !empty($b)){
			$sms_txt = str_replace($key1, $a, $arr[$s]);
			$sms_txt = str_replace($key2, $b, $sms_txt);
	}elseif( ($s == "17") &&  !empty($a) && !empty($b) ){
		$sms_txt = str_replace($key1, $a, $arr[$s]);
		$arr_split = explode(",", $b);
		$sms_txt = str_replace($keyA, $arr_split[0], $sms_txt);
		$sms_txt = str_replace($keyB, $arr_split[1], $sms_txt);
		$sms_txt = str_replace($keyC, $arr_split[2], $sms_txt);
	}else{
		echo($help);
		exit();
	}

		send_sms($p,$sms_txt);

}

//count($_GET)==2
function logg($text)
{
	$d = date("d-m-y"); 
	$t = date("H:i:s"); 
	file_put_contents("/tmp/luke_log/$d.log","$text...$t\n",  FILE_APPEND | LOCK_EX);

}
function send_sms($phone_number,$sms_text)
{
$phone_number = rawurlencode($phone_number);
$sms_text = rawurlencode($sms_text);

$curl = curl_init();
logg("SMS TEXT: $sms_text");
curl_setopt_array($curl, array(
CURLOPT_RETURNTRANSFER => 1,
CURLOPT_URL => 
"http://192.168.1.21:13013/cgi-bin/sendsms?username=simple&password=elpmis&to=$phone_number&from=8192&text=$sms_text",
CURLOPT_USERAGENT => 'Codular Sample cURL Request')
);

$resp = curl_exec($curl);
$curl_return_code  = curl_getinfo($curl, CURLINFO_HTTP_CODE);

curl_close($curl);
//echo "Code is ".$curl_return_code."<br>";

if($curl_return_code == "202"){

if($resp == "0: Accepted for delivery"){
echo("200 (SMS Requested)<br>");
logg("200 ACCEPTED $phone_number");
header("HTTP/1.1 200 OK");
}else{
echo("202 (ETC is Unreachable)<br>");
logg("200 ETC_UNREACHABLE $phone_number");
header("HTTP/1.1 202 Unreachable");
}

}elseif($curl_return_code == "0"){
echo("503 (SMS Gateway is Unavailable)<br>");
logg("503 SMS_GATEWAY_UNAVAILABLE $phone_number");
header("HTTP/1.1 503 Unavailable");
}else {
echo($curl_return_code." ".$resp." (new case)<br>");
logg("NEW_CASE $resp $phone_number");
}
#echo $p." ".$c." ".$resp;
}


?>

