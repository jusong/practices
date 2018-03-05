#!/bin/bash  
  
awk 'BEGIN{  
   for(i=1; i<=3; i++){  
       for(j=1; j<=3; j++){  
           array["int"i, "int"j] = i * j;  
           print i" * "j" = "array[i,j];  
       }  
   }  
  
   print  
  
   for(i in array){  
	   print i" "array[i]
split(i, array2, SUBSEP);  
#       print array2[1]" * "array2[2]" = " array[i];  
		   for(j in array2) {
			   print j" "array2[j]
		   }
   }  
}'  

