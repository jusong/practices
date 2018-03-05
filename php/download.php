<?php
$str = "http://prvstatic.edu-china.com/upload/pati/read/5501473676702.mp3?e=1474272079&token=hBNwAUllvsZZzxDaNtua_BBmm5_CqJLDUANkQ5c8:PzNUtltVlZDykhrW7D2H_9jGpXA=";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $str);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_REFERER, "http://pati.shenzhoujiajiao.com/");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$res = curl_exec($ch);
echo strlen($res) . "\n";
if (strlen($res)) {
	file_put_contents("2914.mp3", $res);
}
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo $code . "\n";
?>
