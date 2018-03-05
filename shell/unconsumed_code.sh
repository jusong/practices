#!/bin/sh
#
#################################################################
# 文件名称：unconsumed_code.sh
# 创 建 者：blacknc <jusonlinux@163.com>
# 创建日期：2018-02-28 15:59
# 描    述：
#################################################################

codefile="/tmp/cc.txt"
codes=""
for code in `cat $codefile`
do
    codes=$codes",\""$code"\""
done
codes=${codes#*,}

mysql -hrdsi25j8h56r79zp2b6jipublic.mysql.rds.aliyuncs.com -ufasthand -pfastHand_db_12345665 -A fastHand -e "select consum_code from fasthand_my_card where status=2 and user_id=0 and consum_code in ($codes)" > /tmp/unconsumed_code.xls
#echo mysql -hrdsi25j8h56r79zp2b6jipublic.mysql.rds.aliyuncs.com -ufasthand -pfastHand_db_12345665 -A fastHand -e "select consum_code from fasthand_my_card where status=2 and user_id=0 and consum_code in ($codes)" > /tmp/log
