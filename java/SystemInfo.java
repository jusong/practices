/*****************************************************************
 * 文件名称：SystemInfo.java
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2017-12-18 18:33
 * 描    述：
 *****************************************************************/

import java.util.*;

public class SystemInfo {

    public static void main(String[] args) {

//        Map<String, String> map = System.getenv();
//
//        for(Iterator<String> itr = map.keySet().iterator();itr.hasNext();){
//
//            String key = itr.next();
//            System.out.println(key + "=" + map.get(key));
//        }   

        //Properties pps = System.getProperties();
        //pps.list(System.out);
        System.out.println(System.getProperty("java.home"));
        System.out.println(System.getProperty("java.class.path"));
    }
} 
