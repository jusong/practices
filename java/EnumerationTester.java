/*****************************************************************
 * 文件名称：EnumerationTester.java
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2017-12-18 18:59
 * 描    述：
 *****************************************************************/

import java.util.Vector;
import java.util.Enumeration;

public class EnumerationTester {

    public static void main(String[] args) {
        
        Enumeration<String> days;
        Vector<String> dayNames = new Vector<String>();
        dayNames.add("Sunday");
        dayNames.add("Monday");
        dayNames.add("Tuesday");

        days = dayNames.elements();
        while(days.hasMoreElements()) {
            System.out.println(days.nextElement());
        }
    }
}
