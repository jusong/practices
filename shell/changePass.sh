#!/bin/bash

files=$(grep -r '\&\$' /data/server/inc/dao/ | cut -d: -f1 | sort | uniq)
sed -i '/function/ ! {s/&\$/\$/g}' $files 
