#!/bin/bash

tms=$(date +%s)
begin=$(date -d @$((tms-2592000)) +%Y-%m)
end=$(date +%Y-%m)
sql="select id from fasthand_article where topic_code=180002 and status=1 and channel=2 and create_time > '$begin' and create_time < '$end'";
echo $sql;
mysql -hrdsi25j8h56r79zp2b6jipublic.mysql.rds.aliyuncs.com -ufasthand -pfastHand_db_12345665 -A fastHand -e "$sql" > /tmp/article_ids_${begin}.txt
echo Ids File: /tmp/article_ids_${begin}.txt
