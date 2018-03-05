#!/bin/bash

files=$(find /home/jus/workspace/server -name "*.php" -o -name "*.html" -o -name "*.htm"  -o -name "*.js" -o -name "*.css" | xargs egrep --color=auto "("kuaijvshou.com"|"kuaijushou.com"|"shenzhoujiajiao.com.cn"|"shenzhoujiajiao.com"|"shenzhoujiajiao.cn"|"shenzhoujiajiao.net")" | cut -d: -f1 | sort | uniq)

domains="kuaijushou\.com kuaijvshou\.com shenzhoujiajiao\.com\.cn shenzhoujiajiao\.com shenzhoujiajiao\.cn shenzhoujiajiao\.net"
for file in $files
do
	for domain in $domains
	do
		sed -i "s/\.$domain/\.edu-china\.com/g" $file
    	sed -i "s/\\\"$domain/\\\"edu-china\.com/g" $file
    	sed -i "s/'$domain/'edu-china\.com/g" $file
#echo "sed \"s/.$domain/.kuaijushou.com/g\" $file"
#echo "sed \"s/\"$domain/\"kuaijushou.com/g\" $file"
#echo "sed \"s/\'$domain/\'kuaijushou.com/g\" $file"
	done
done
