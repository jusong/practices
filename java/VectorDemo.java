/*****************************************************************
 * 文件名称：VectorDemo.java
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2017-12-22 12:56
 * 描    述：
 *****************************************************************/

import java.util.*;

public class VectorDemo {

    public static void main(String[] args) {

        Vector v = new Vector();

        v.add(new Integer(1));
        v.add(new Integer(2));
        //System.out.println(v);

        Vector v1 = new Vector();
        v1.add(new Integer(3));
        v1.add(0, new Integer(4));
        v1.addAll(v);
        v1.addAll(2, v);
        v1.addElement(new Integer(5));
        v1.insertElementAt(new Integer(7), 2);
        System.out.println("capacity of v1: " + v1.capacity());
        System.out.println(v1);

        System.out.println("elements of v: " + v);
        v.clear();
        System.out.println("elements of v: " + v);

        v.addAll((Vector)v1.clone());
        System.out.println("elements of v: " + v);
        v.addAll((Vector)v1.clone());
        v.addAll((Vector)v1.clone());
        System.out.println("elements of v: " + v);
        System.out.println("capacity of v1: " + v.capacity());

        if (v.contains(new Integer(3))) {
            System.out.println("v contains integer 3");
        }
        if (v.contains(new Integer(30))) {
            System.out.println("v contains integer 30");
        } else {
            System.out.println("v not contains integer 30");
        }
        if (v.containsAll(v1)) {
            System.out.println("v contains v1");
        }
        if (v1.containsAll(v)) {
            System.out.println("v1 not contains v");
        }
        Integer[] intArr = new Integer[v.size()];
        v.copyInto(intArr);
        for (Integer i : intArr) {
            if (i == null) {
//                break;
            }
            System.out.println(" " + i);
        }

        System.out.println("v's 2nd element: " + v.elementAt(1));
        System.out.println("v's 2nd element: " + v.get(1));

        Enumeration e = v.elements();
        while (e.hasMoreElements()) {
            System.out.print(e.nextElement() + " ");
        }
        System.out.println("");

        System.out.println("capacity of v: " + v.capacity());
        v.ensureCapacity(45);
        System.out.println("capacity of v: " + v.capacity());
        v.ensureCapacity(10);
        System.out.println("capacity of v: " + v.capacity());

        if (!v.equals(v1)) {
            System.out.println("v not equals v1");
        }
        if (v.equals(v)) {
            System.out.println("v equals v");
        }

        System.out.println("first element: " + v.firstElement());
        System.out.println("last element: " + v.lastElement());

        System.out.println("v's elements: " + v);
        System.out.println("v's hash code: " + v.hashCode());
        v.add(new Integer(10));
        System.out.println("v's elements: " + v);
        System.out.println("v's hash code: " + v.hashCode());
        v.add(new Integer(100));
        System.out.println("v's elements: " + v);
        System.out.println("v's hash code: " + v.hashCode());
        Vector v3 = new Vector();
        for (int i = 0; i < v.size(); i++) {
            v3.add(new Integer(i));
        }
        System.out.println("v3's elements: " + v3);
        System.out.println("v3's hash code: " + v3.hashCode());
        v.add(new Integer(100));
        System.out.println("v's elements: " + v);
        System.out.println("v's hash code: " + v.hashCode());

        int idx = v.indexOf(new Integer(40), 2);
        if (-1 == idx) {
            System.out.println("v not contains 40");
        } else {
            System.out.println("element 40 at v's index: " + idx);
        }

        if (!v.isEmpty()) {
            System.out.println("v is not empty");
        }
    }
}
