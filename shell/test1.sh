#!/bin/bash

su - lfs -c "exit" 
if [ "$?" = 0 ]; then
	echo "ok"
else 
	echo "bad"
fi

