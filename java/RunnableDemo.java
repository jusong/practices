/*****************************************************************
 * 文件名称：RunnableDemo.java
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2017-12-25 13:19
 * 描    述：
 *****************************************************************/


public class RunnableDemo implements Runnable {

    public static void main(String[] args) {

        RunnableDemo r = new RunnableDemo();
        r.run();
    }

    public void run() {
        
        System.out.println("run");
    }
}
