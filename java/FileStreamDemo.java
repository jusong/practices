/*****************************************************************
 * 文件名称：FileStreamDemo.java
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2017-12-26 19:09
 * 描    述：
 *****************************************************************/

import java.io.*;

public class FileStreamDemo {

    public static void main (String[] args) {

        FileInputStream is = null; 
        FileOutputStream os = null;
        try {

            is = new FileInputStream("/tmp/runoob.html");
            os = new FileOutputStream("/tmp/runoob_gbk.html");
            byte[] contentByte = new byte[is.available()];
            is.read(contentByte);
            String content = new String(contentByte);

            os.write(content.getBytes("GBK"));
        } catch (IOException e) {

            e.printStackTrace();
        } finally {

            try {

                if (null != is) {
                    is.close();
                }
                if (null != os) {
                    os.close();
                }
            } catch (IOException e) {

                e.printStackTrace();
            }
        }
    }
}
