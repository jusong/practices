#!/bin/bash

for j in `seq 1 $1`
do
for i in `seq 1 $2`
do
	echo "================< $j  --  $i >================"
	php client.php
done
	sleep $3
done

