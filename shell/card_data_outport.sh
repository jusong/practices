#!/bin/bash

begin_time=$1
end_time=$2

begin_time=${begin_time:-$(date +%Y-%m-01)};
end_time=${end_time:-$(date -d "+1 month" +%Y-%m-01)};

function parse_res() {
	if [ "$?" = 0 ]; then
    	sed -i '1d' $1 
    	echo "$1 Ok"
	else
    	echo "$1 Failed" 
    	exit
	fi
}

#一次未消费的用户
outfile=/tmp/unconsume_$(date -d $begin_time +%Y-%m).txt
mysql -hrdsi25j8h56r79zp2b6jipublic.mysql.rds.aliyuncs.com -ufasthand -pfastHand_db_12345665 -A fastHand -e "select a.valid_date,b.username from fasthand_my_card a join fasthand_user b on a.user_id = b.id where a.id not in (select card_id from fasthand_my_card_item) and a.valid_date > '${begin_time}' and a.card_id=26 and a.valid_date < '${end_time}' order by a.valid_date" > $outfile
parse_res $outfile

#未消费过海洋馆和比如世界的用户
outfile=/tmp/no_br_hy_$(date -d $begin_time +%Y-%m).txt
mysql -hrdsi25j8h56r79zp2b6jipublic.mysql.rds.aliyuncs.com -ufasthand -pfastHand_db_12345665 -A fastHand -e "select a.valid_date,b.username from fasthand_my_card a join fasthand_user b on a.user_id = b.id where a.id not in (select card_id from fasthand_my_card_item where item_id in (8477,8225)) and a.valid_date > '${begin_time}' and a.card_id=26 and a.valid_date < '${end_time}' order by a.valid_date" > $outfile 
parse_res $outfile

#消费过海洋馆和比如世界的用户
outfile=/tmp/br_hy_$(date -d $begin_time +%Y-%m).txt
mysql -hrdsi25j8h56r79zp2b6jipublic.mysql.rds.aliyuncs.com -ufasthand -pfastHand_db_12345665 -A fastHand -e "select a.valid_date,b.username from fasthand_my_card a join fasthand_user b on a.user_id = b.id where a.id in (select card_id from fasthand_my_card_item where item_id in (8477,8225)) and a.valid_date > '${begin_time}' and a.card_id=26 and a.valid_date < '${end_time}' order by a.valid_date" > $outfile 
parse_res $outfile


#消费过比如世界,未消费过海洋馆的用户
outfile=/tmp/only_br_$(date -d $begin_time +%Y-%m).txt
mysql -hrdsi25j8h56r79zp2b6jipublic.mysql.rds.aliyuncs.com -ufasthand -pfastHand_db_12345665 -A fastHand -e "select a.valid_date,b.username from fasthand_my_card a join fasthand_user b on a.user_id = b.id where a.id in (select card_id from fasthand_my_card_item where item_id = 8225) and a.id not in (select card_id from fasthand_my_card_item where item_id = 8477) and a.valid_date > '${begin_time}' and a.card_id=26 and a.valid_date < '${end_time}' order by a.valid_date" > $outfile 
parse_res $outfile

#消费过海洋馆,未消费过比如世界的用户
outfile=/tmp/only_hy_$(date -d $begin_time +%Y-%m).txt
mysql -hrdsi25j8h56r79zp2b6jipublic.mysql.rds.aliyuncs.com -ufasthand -pfastHand_db_12345665 -A fastHand -e "select a.valid_date,b.username from fasthand_my_card a join fasthand_user b on a.user_id = b.id where a.id in (select card_id from fasthand_my_card_item where item_id = 8477) and a.id not in (select card_id from fasthand_my_card_item where item_id = 8225) and a.valid_date > '${begin_time}' and a.card_id=26 and a.valid_date < '${end_time}' order by a.valid_date" > $outfile 
parse_res $outfile
