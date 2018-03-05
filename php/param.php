<?php
if (!$argv[1]) exit;
$search = array("clientSource", "apiVersion", "userId", "cityId", "cardId", "myCardId", "my_card_id", "itemId", "item_id", "aid", "actId");
preg_match_all("/(\w+=[\w\.-]+)/", $argv[1], $match);
$search = array_merge($search, array_slice($argv, 2));
echo "\n++++++++++++++++++++++++++++++\n";
foreach($match[1] as $mt) {
//	echo $mt."\n";exit;
	if (in_array(substr($mt, 0, strpos($mt, "=")), $search)) {
		echo $mt."\n";
	}
}
echo "++++++++++++++++++++++++++++++\n";
?>
