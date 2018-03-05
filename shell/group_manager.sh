#!/bin/bash
#
# Function: 团队用户组管理
#	1、共建一个工作组szjj
#	2、新添加用户默认属于工作组
#	3、老用户的默认组变更为工作组

# Author: jiafd
# Date: 2016/04/08

PATH="/sbin:/usr/sbin:/usr/local/sbin:/bin:/usr/bin:/usr/local/bin"

#工作组
GNAME="szjj"
#其他附加组列表，用空格分割
OTH_GROUPS=""

#需要root权限
if [ "`id -u`" != 0 ]; then
    echo "Need run as root"; exit
fi


#新建工作组
grep ^${GNAME}: /etc/group > /dev/null
if [ $? != 0 ]; then
    groupadd ${GNAME} 
fi

while [ -n $1 ]
do
    case $1 in
	"-n")
	    new=1
	    new_users=$2
	    shift
	    ;;
	"-o")
	    old=1
	    old_users=$2
	    shift
	    ;;
	"-r")
	    OTH_GROUPS="$OTH_GROUPS wheel"
	    ;;
	"-m")
	    mod=1
	    ;;
    esac
    shift
done

##新建用户，所属组为szjj
if [ "$new" = 1 ]; then
    $new_users=${new_users//,/ }
    for user in $new_users
    do
	#创建用户
	#去除开头和结尾的空格
	OTH_GROUPS=${OTH_GROUPS# *}
	OTH_GROUPS=${OTH_GROUPS% *}
	#将内部多个连续空格合并成一个空格
	OTH_GROUPS=$(echo $OTH_GROUPS | tr -s [:space:])
	#将空格转成逗号
	if [ -n "$OTH_GROUPS" ]; then
	    OTH_GROUPS=${OTH_GROUPS// /,}
	    ARGS="-a -G $OTH_GROUPS"
	fi
	useradd -m -g $GNAME $ARGS $user
	echo "Passowrd for $user"
	passwd $user
    done
fi
##修改现有用户的所属组为szjj
if [ "$old" = 1 ]; then
    $old_users=${old_users//,/ }
    for user in $old_users
    do
	#取出现有的组
	old_groups=$(groups $user)
	old_groups=${old_groups#*:}
	#过滤掉和用户名同名的组
	new_groups=${old_groups//$user/}
	#将现有组列表与指定的组列表合并去重
	OTH_GROUPS="$OTH_GROUOPS $new_groups"
	OTH_GROUPS=$(echo $OTH_GROUPS | awk '{gsub(/ /,"\n");print}' | sort | uniq)
	#重新设置用户的组列表
	#将空格转成逗号
	if [ -n "$OTH_GROUPS" ]; then
	    OTH_GROUPS=${OTH_GROUPS// /,}
	    ARGS="-a -G $OTH_GROUPS"
	fi
	usermod -g $GNAME $ARGS $user > /dev/null
	#删除用户同名的组
	grep ^${user}: /etc/group > /dev/null
	if [ "$?" = "0" ]; then
	    groupdel $user
	fi
    done
fi
##修改权限
if [ "$mod" = 1 ]; then
    chown -R apache:szjj /data/inc /data/www /data/rpc
    chmod -R 777 /data/inc /data/www /data/rpc
fi
