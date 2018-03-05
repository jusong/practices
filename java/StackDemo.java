/*****************************************************************
 * 文件名称：StackDemo.java
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2017-12-19 11:36
 * 描    述：
 *****************************************************************/

import java.util.Stack;
import java.util.EmptyStackException;

public class StackDemo {

    static void showpush(Stack<Integer> st, int a) {

        st.push(new Integer(a));
        System.out.println("push(" + a + ")");
        System.out.println("stack: " + st);
    }

    static void showpop(Stack<Integer> st) {

        System.out.print("pop -> ");
        Integer a = (Integer) st.pop();
        System.out.println(a);
        System.out.println("statck: " + st);
    }

    public static void main(String[] args) {

        Stack<Integer> st = new Stack<Integer>();
        System.out.println("stack: " + st);
        showpush(st, 45);
        showpush(st, 26);
        showpush(st, 73);
        showpop(st);
        showpop(st);
        showpop(st);
        try {

            showpop(st);
        } catch (EmptyStackException e) {

            System.out.println("empty stack");
        }
    }
}
