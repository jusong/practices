#!/bin/bash

files=$(grep -r "\.html" /home/juson/Workspace/server/web/application/default/Home/View/default | grep -E "(/cl/|/tl/|/acl/|/arl/|/gl/|/sp/)" | cut -d: -f1 | sort | uniq)
for file in $files
do
	sed -i '/\/cl\// {s/\.html/\//g}' $file 
	sed -i '/\/tl\// {s/\.html/\//g}' $file 
	sed -i '/\/acl\// {s/\.html/\//g}' $file 
	sed -i '/\/arl\// {s/\.html/\//g}' $file 
	sed -i '/\/gl\// {s/\.html/\//g}' $file 
	sed -i '/\/sp\// {s/\.html/\//g}' $file 
done
