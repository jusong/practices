#!/bin/bash
#Written by Gemmy.Rao
#Version 0.2
#CHANGES
#by www.jbxue.com
#Add -p option for checking other service's port
 
#Init
PORT=80
WARNING=5000
CRITICAL=20000
 
#get options
while getopts "w:c:p:hs" OPT;do
    case $OPT in
    w)
        WARNING=${OPTARG}
        ;;
    c)
        CRITICAL=${OPTARG}
        ;;
    p)
        PORT=${OPTARG}
        #转换各端口的十进制成十六进制
        PORT_16=`echo ${PORT}|awk -F, '{for(i=1;i<=NF;i++)printf "|%.4X",$i}'|sed 's/|//'`
        ;;
    h)
        echo "Usage: $0 -w 500 -c 2000 -p 80,8081 -s"
        exit 0
        ;;
    s)
        SILENT=1
        ;;
    *)
        echo "Usage: $0 -w 500 -c 2000 -p 80,8081"
        exit 0
        ;;
    esac
done
 
#经过time测试，取值速度netstat > awk '//{a++}END{print a}' > cat|grep|wc > cat|awk|wc，在2w连接下，netstat要20s，最快的方式不到5s（一般nagios到10s就该直接报timeout了）
PORT_CONN=`cat /proc/net/tcp*|awk '$2~/:('$PORT_16')$/'|wc -l`
 
if [[ "$SILENT" == 1 ]];then
    [[ -d /usr/local/nagios ]] || mkdir -p /usr/local/nagios
    echo "Silent log write OK | Port ${PORT}=${PORT_CONN};${WARNING};${CRITICAL};0;0"
    echo -en "`date`t$PORT_CONNn" >> /usr/local/nagios/conn.log
    exit 0
elif [[ "$PORT_CONN" -lt "$WARNING" ]];then
    echo "Port $PORT connection OK for $PORT_CONN. | Port ${PORT}=${PORT_CONN};${WARNING};${CRITICAL};0;0"
    exit 0
elif [[ "$PORT_CONN" -gt "$CRITICAL" ]];then
    echo "Port $PORT connection critical for $PORT_CONN!! | Port ${PORT}=${PORT_CONN};${WARNING};${CRITICAL};0;0"
    exit 2
else
    echo "Port $PORT connection warning for $PORT_CONN! | Port ${PORT}=${PORT_CONN};${WARNING};${CRITICAL};0;0"
    exit 1
fi
