/*****************************************************************
 * 文件名称：HashCode.java
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2017-12-22 14:01
 * 描    述：
 *****************************************************************/

import java.util.*;

public class HashCode {

    public static void main(String[] args) {

        Vector v = new Vector();
        for(int i = 0; i < 26; i++) {
            v.add(new Integer(i));
        }
        System.out.println(v.hashCode());
    }
}
