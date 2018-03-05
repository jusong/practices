#!/bin/bash

logs=`ls /data/logs/fastHand/sql_all.log* -ltr`
for log in $logs
do
	lines=`grep -r " fasthand_my_card " $log | grep -E "(INSERT|UPDATE|DELETE)"`
	for line in lines
	do
		echo ${line##*##} >> /tmp/sql_logs
	done
done
echo "end";
