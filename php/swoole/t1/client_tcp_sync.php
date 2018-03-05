<?php
$client = new swoole_client(SWOOLE_SOCK_TCP);

if (!$client->connect("0.0.0.0", 9511, 0.5)) {
    die("connect failed.");
}

$a = array("name" => "jia");
if (!$client->send(json_encode($a))) {
    die("send failed.");
}

$data = $client->recv();
if (!$data) {
    die("recv failed.");
}
echo $data."\n";

$client->close();
?>