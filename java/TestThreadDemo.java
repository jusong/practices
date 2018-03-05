/*****************************************************************
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2017-12-25 13:08
 * 描    述：
 *****************************************************************/

import java.util.*;
import java.util.concurrent.*;

class RunnableDemo implements Runnable {

    private Thread t;
    private String threadName;

    RunnableDemo(String name) {
        threadName = name;
        System.out.println("Creating " + threadName);
    }

    public void run() {

        System.out.println("Running " + threadName);
        try {

            for(int i = 0; i < 400; i++) {

                System.out.println("Thread: " + threadName + ", " + i);
                Thread.sleep(60);
            }
            Thread.dumpStack();
        } catch (InterruptedException e) {

            System.out.println("Thread " + threadName + " interrunpted.");
        }
        System.out.println("Thread " + threadName);
    }

    public void start() {

        System.out.println("Starting " + threadName);
        if (null == t) {
            t = new Thread(this, threadName);
            t.start();
        }
    }
}

class ThreadDemo extends Thread {

    private Thread t;
    private String threadName;
    private static int counter = 0;

    ThreadDemo(String name) {

        threadName = name;
        System.out.println("Create thread: " + threadName);
    }

    public void run() {

        System.out.println("Run thread: " + threadName);
        printInfo(threadName);
        System.out.println("End thread: " + threadName);
    }

    public void start() {

        System.out.println("Start thread: " + threadName);
        if (null == t) {
            t = new Thread(this, threadName);
        }
        t.start();
    }

    public static synchronized void printInfo(String threadName) {

        System.out.println("Thread " + threadName + " working.");
        for (int i = 0; i < 100; i++) {
            //System.out.print("Thread " + threadName + " " + counter);
            counter = counter + 1;
            //System.out.println(" => " + counter);
        }
        System.out.println("Thread " + threadName + " work completed.");
    }
}

class DisplayMessage implements Runnable {

    private String message;

    public DisplayMessage(String message) {
        this.message = message;
    }

    public void run() {

        try {
            while(true) {
                System.out.println(message);
                Thread.sleep(500);
            }
        } catch (InterruptedException e) {
            System.out.println("Thread interrupted.");
        }
    }
}

class GuessANumber extends Thread {

    private int number;
    
    public GuessANumber(int number) {
        this.number = number;
    }

    public void run() {

        int counter = 0;

        counter = guess(); 
        System.out.println("** Correct! " + this.getName() + " in " + counter + " guess. **");
    }

    public synchronized int guess() {

        int counter = 0;
        try {

            int guess = 0;
            do {
                guess = (int) (Math.random() * 100 + 1);
                System.out.println(this.getName() + " guess " + guess);
                counter++;
                Thread.sleep(100);
            } while (guess != number);
        } catch (InterruptedException e) {

            System.out.println("Thread " + this.getName() + " interrupted.");
        }
        return counter;
    }
}

class CallableThreadTest implements Callable<Integer> {

    @Override
    public Integer call() throws Exception {

        int i = 0;

        try {
            for (; i < 100; i++) {

                System.out.println(Thread.currentThread().getName() + " " + i);
                Thread.sleep(100);
            }
        } catch (InterruptedException e) {
            System.out.println("Thread interrupted.");
        }
        return i;
    }
}

class MyRunnable implements Runnable {

    private boolean active;

    public void run() {

        active = true;
        while (active) {
            //System.out.println(Thread.currentThread().getName());
            System.out.println("running.");
        }
    }

    public void stop() {
        active = false;
    }
}

public class TestThreadDemo {

    public static void main(String[] args) {
    
        //testThreadDemo();
        //testRunnableAndThread();
        //testCallableFutureDemo();
        testMyRunnable();
        System.out.println("main() is ending...");
    }

    public static void testMyRunnable() {

        MyRunnable mr = new MyRunnable();
        Thread t1 = new Thread(mr, "t1");
        t1.start();

        try {
            Thread.sleep(1000);
        } catch (InterruptedException e) {
        }
        //Thread t2 = new Thread(mr, "t2");
        //t2.start();
        mr.stop();
    }

    public static void testRunnableDemo() {

        RunnableDemo r1 = new RunnableDemo("Thread-1");
        r1.start();

        RunnableDemo r2 = new RunnableDemo("Thread-2");
        r2.start();
    }

    public static void testThreadDemo() {

            ThreadDemo t1 = new ThreadDemo("A");
            t1.start();
            ThreadDemo t4 = new ThreadDemo("D");
            t4.start();
    }

    public static void testRunnableAndThread() {

        Runnable hello = new DisplayMessage("hello");
        Thread thread1 = new Thread(hello);
        thread1.setDaemon(true);
        thread1.setName("hello");
        System.out.println("Starting hello thread...");
        thread1.start();

        Runnable bye = new DisplayMessage("Goodbye");
        Thread t2 = new Thread(bye);
        t2.setDaemon(true);
        t2.setPriority(Thread.MIN_PRIORITY);
        System.out.println("Starting goodbay thread...");
        t2.start();

        Thread t3 = new GuessANumber((int)(Math.random() * 100 + 1));
        System.out.println("Starting thread3...");
        t3.start(); 

        Thread t4 = new GuessANumber((int)(Math.random() * 100 + 1));
        System.out.println("Starting thread4...");
        t4.start();

        try {
            t3.join(500);
            t4.join(500);
        } catch (InterruptedException e) {
            System.out.println("Thread interrupted.");
        }
    }

    public static void testCallableFutureDemo() {

        CallableThreadTest ctt = new CallableThreadTest();
        FutureTask<Integer> ft = new FutureTask<>(ctt);
        for (int i = 0; i < 100; i++) {
            System.out.println(Thread.currentThread().getName() + " 的循环变量i的值: " + i);
            if (i % 20 == 0) {
                System.out.println("启动线程" + i);
                new Thread(ft, "有返回值的线程" + i).start();
            }
        }

//        try {
//
//            System.out.println("等待子线程的返回值");
//            System.out.println("子线程的返回值: " + ft.get());
//        } catch (ExecutionException e) {
//
//            e.printStackTrace();
//        } catch (InterruptedException e) {
//
//            e.printStackTrace();
//        }
    }
}
