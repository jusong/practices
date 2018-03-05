/*****************************************************************
 * 文件名称：Test.java
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2017-12-26 16:44
 * 描    述：
 *****************************************************************/

import java.util.*;
import java.lang.*;
import java.math.*;
import java.lang.reflect.Field;
import java.text.*;

class TClass {

    private String name = "TClass";
    private final int value = 10;
    private static final String TITLE = "TEST";

    public static void print() {

        System.out.println("TITLE: " + TClass.TITLE);
        System.out.println("TITLE: " + TITLE);

        TClass t = new TClass();
        System.out.println("name: " + t.name);
        System.out.println("value: " + t.value);
    }
}

public class Test {

	public String name = "Test";

    public static void test1() {

        Integer x = 5;
        x = x + 10;
        System.out.println(x);
    }

    public static void test2() {

        System.out.println("π值: " + Math.PI);
        System.out.println("90度正弦值: " + Math.sin(Math.PI / 2));
        System.out.println("0度余弦值: " + Math.cos(0));
        System.out.println("60度正切值: " + Math.tan(Math.PI / 3));
        System.out.println("1的反正切值: " + Math.atan(1));
        System.out.println("π/2的角度值: " + Math.toDegrees(Math.PI / 2));
    }

    public static void test3() {

        Double x = 20.3;

        System.out.println("x: " + x);
        System.out.println("x byte value : " + x.byteValue());
        System.out.println("x compare to 10: " + x.compareTo(10.0));
        System.out.println("x equals 1: " + x.equals(1));
        System.out.println("x value of 23: " + x.valueOf(23));
        System.out.println("x to string: " + x.toString());

        Integer i1 = 100;
        Integer i2 = 100;
        if (i1 == i2) {
            System.out.println("i1 == i2");
        } else {
            System.out.println("i1 != i2");
        }
    }

    public static void test4() {

        try {
            Class cache = Integer.class.getDeclaredClasses()[0];
            Field myCache = cache.getDeclaredField("cache");
            myCache.setAccessible(true);
            Integer[] newCache = (Integer[]) myCache.get(cache);
            newCache[132] = newCache[143];

            System.out.println(4); //4
            System.out.println(Integer.valueOf(4)); //15
            System.out.printf("%d\n", 4); //15

            int a = 2;
            int b = a + a;
            System.out.printf("%d + %d = %d\n", a, a, b); //2 + 2 = 15
            System.out.println(b); //4
            System.out.println((Integer)b); //15
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    public static void test5() {
        
        char[] helloArray = {'r', 'u', 'n', 'o', 'o', 'b'};
        String helloString = new String(helloArray);
        System.out.println(helloString);
        System.out.println("length: " + helloString.length());
        System.out.println("concat: " + helloString.concat(" hello"));
        System.out.println("常量".concat("字符串拼接"));
        System.out.println("加号" + "字符串拼接");
        System.out.printf("浮点类型： " +
                "%s, 整型：" +
                "%s, 字符串类型" + 
                "%s\n", "float", "int", "String");
        System.out.format("浮点类型： " +
                "%s, 整型：" +
                "%s, 字符串类型" + 
                "%s\n", "float", "int", "String");
        System.out.println(
                "charAt 2: " + helloString.charAt(2) + "\n" +
                "compare to hello: " + helloString.compareTo("hello") + "\n" +
                "compare to hello ingore case: " + helloString.compareToIgnoreCase("hello") + "\n" +
                "content equals hello: " + helloString.contentEquals("hello") + "\n"

                );
        String s1 = String.copyValueOf(helloArray);
        System.out.println("s1: " + s1);
        String s2 = String.copyValueOf(helloArray);
        System.out.println("s2: " + s2);
        System.out.println("s2 end with ob: " + s2.endsWith("ob"));
        System.out.println("s1 == s2: " + s1.equals(s2));
        byte[] s1Bytes = s1.getBytes();
        for (byte b : s1Bytes) {
            System.out.printf("%c ", b);
        }
        System.out.println();

        String s3 = "菜鸟教程";
        char [] s3Chars = new char[3];
        s3.getChars(0, 3, s3Chars, 0);
        for (char c : s3Chars) {
            System.out.println(c);
        }
        System.out.println(s3Chars.length);

//        try {
//            byte[] s3Bytes1 = s3.getBytes("Gbk");
//            for (byte b : s3Bytes1) {
//                System.out.printf("%c ", b);
//            }
//            System.out.println();
//        } catch (Exception e) {
//            e.printStackTrace();
//        }

        System.out.println("s2: " + s2);
        System.out.println(s2.indexOf("noob"));
        System.out.println("s2 match /oo/: " + s2.matches("oo"));
        System.out.println("s2 match /[a-z]b$/: " + s2.matches("[a-z]b$"));

        System.out.println("s1 match s2: " + s1.regionMatches(1, s2, 1, 3));
        System.out.println("s2 o replaced O； " + s2.replace("o", "O"));
        System.out.println("s2 o replaced first O； " + s2.replaceFirst("o", "O"));
        System.out.println("s2 n replaced N； " + s2.replaceAll("/noob/", "N"));
        System.out.println("s2 subsequence: " + s2.subSequence(0, 3));
        System.out.println("s2 substring: " + s2.substring(3, 6));
        char[] s2CharArr = s2.toCharArray();
        char[] s3CharArr = s3.toCharArray();
        for (char c : s3CharArr) {
            System.out.print(c + " ");
        }
        System.out.println();
        String s5 = "   " + s3 + "  ";
        System.out.println("s5: " + s5 + "|");
        System.out.println("s5 trim: " + s5.trim() + "|");
        System.out.format("%1$9d\n", -34);
        System.out.format("%1$09d\n", -34);
        System.out.format("%1$-9d\n", -34);
        System.out.format("%1$-,9d\n", -34);
        System.out.format("%1$0+30G\n", 333444.2353343456);
        System.out.format("%1$0,9d\n", -34);
        System.out.format("%1$0#20x\n", -34);
        System.out.format("%1$(9d\n", -34);
    }

    public static void test6() {

        //StringBuffer sb = new StringBuffer();
        StringBuilder sb = new StringBuilder();
        sb.append("hello");
        sb.append(", ");
        sb.append("world");
        System.out.println(sb);
        System.out.println(sb.reverse());
        System.out.println(sb.delete(2, 4));
        System.out.println(sb.insert(2, 'l'));
        System.out.println(sb.insert(3, 'l'));
        System.out.println(sb.replace(2, 4, "LL"));
        System.out.println("capacity: " + sb.capacity());
        System.out.println("length: " + sb.length());
        sb.setCharAt(2, 'H');
        System.out.println(sb);
        sb.replace(2, 3, "L");
        System.out.println(sb);

        String s = sb.toString();
        System.out.println(s);
        System.out.println(s.charAt(2));
        System.out.println(s.toCharArray()[2]);
        System.out.println(s.substring(2, 3));
        System.out.println(s.intern());
    }

    public static void test7() {

        long start = System.currentTimeMillis();
        for (int i = 0; i < 5000000; i++) {
            StringBuffer sb = new StringBuffer();
            sb.append("hello");
            sb.append("hello");
            sb.append("hello");
            sb.append("hello");
            sb.append("hello");
            sb.append("hello");
            sb.append("hello");
            sb.append("hello");
        }
        long end = System.currentTimeMillis();
        System.out.println("StringBuffer cost time: " + (end - start) + "ms.");

        start = System.currentTimeMillis();
        for (int i = 0; i < 5000000; i++) {
            StringBuilder sb = new StringBuilder();
            sb.append("hello");
            sb.append("hello");
            sb.append("hello");
            sb.append("hello");
            sb.append("hello");
            sb.append("hello");
            sb.append("hello");
            sb.append("hello");
        }
        end = System.currentTimeMillis();
        System.out.println("StringBuilder cost time: " + (end - start) + "ms.");

        start = System.currentTimeMillis();
        for (int i = 0; i < 5000000; i++) {
            String sb = new String("hello");
        }
        end = System.currentTimeMillis();
        System.out.println("String cost time: " + (end - start) + "ms.");

        start = System.currentTimeMillis();
        for (int i = 0; i < 5000000; i++) {
            String sb = "hello";
        }
        end = System.currentTimeMillis();
        System.out.println("string cost time: " + (end - start) + "ms.");

    }

    public static void test8() {

        String[][] s = new String[2][];
        s[0] = new String[2];
        s[1] = new String[3];

        s[0][0] = "hello";
        s[0][1] = "world";
        s[1][0] = "Hello";
        s[1][1] = ", ";
        s[1][2] = "World";

        for (String[] _s : s) {
            for (String __s : _s) {
                System.out.print(__s);
            }
            System.out.println();
        }
    }

    public static void test9() {

        class MyArray {

            public void print(int[] iArr, String msg) {

                System.out.print(msg);
                for (int i : iArr) {
                    System.out.print(i + " ");
                }
                System.out.println();
            }

            public int[] reverse(int[] iArr) {

                int[] res = new int[iArr.length];;
                for (int i = 0, j = iArr.length - 1; i  < iArr.length; i++, j--) {
                    res[j] = iArr[i];
                }
                return res;
            }
        }

        MyArray ma = new MyArray();

        int[] iArr = {1, 3, 9, 2, 4};
        ma.print(iArr, "iArr: ");

        iArr = ma.reverse(iArr);
        ma.print(iArr, "iArr reverse: ");

        Arrays.sort(iArr);
        ma.print(iArr, "iArr sort: ");
        System.out.println("search 9 in iArr: " + Arrays.binarySearch(iArr, 9));

        int[] iArr1 = {1, 2, 3, 4, 9};
        System.out.println("iArr equals iArr1: " + Arrays.equals(iArr, iArr1));

        Arrays.fill(iArr, 0);
        ma.print(iArr, "iArr fill 0: ");
    }

    public static void test10() {

        String str = "helloworld";
        char[] data = str.toCharArray();// 将字符串转为数组
        for (int x = 0; x < data.length; x++) {
            System.out.print(data[x] + "  ");
            data[x] -= 32;
            System.out.println(data[x] + "  ");
        }
        System.out.println(new String(data));
    }

    public static void printArray(int[] iArr) {

        for (int i : iArr) {
            System.out.print(i + " ");
        }
        System.out.println();
    }

    public static void test11() {

        int[] iArr = {3, 45, 343,2, 43, 1, 344, 44656, 978, 57, 456, 67, 902, 348, 2389, 29};

        printArray(iArr);
        for (int i = iArr.length - 1; i > 0; i--) {
            for (int j = 0; j < i; j++) {

                if (iArr[j] > iArr[j+1]) {
                    int tmp = iArr[j];
                    iArr[j] = iArr[j+1];
                    iArr[j+1] = tmp;
                }
            }
            printArray(iArr);
        }
        printArray(iArr);
    }

    public static void test12() {

        Date d1 = new Date();
        System.out.println(d1 + " after now: " + d1.after(new Date()));
        System.out.println(d1 + " before now: " + d1.before(new Date()));
        Date d2 = (Date)d1.clone();
        System.out.println(d2);
        System.out.println(d1 + " compare to now: " + d1.compareTo(new Date()));
        System.out.println(d1 + " compare to " + d2 + ": " + d1.compareTo(d2));
        System.out.println(d1 + " equals to " + d2 + ": " + d1.equals(d2));
        System.out.println(d1 + " get time: " + d1.getTime() + "ms");
        System.out.println(d1 + " hashCode: " + d1.hashCode());
        System.out.println(d2 + " hashCode: " + d2.hashCode());

        try {
            Thread.sleep(1000);
        } catch (Exception e) {
            e.printStackTrace();
        }

        d2.setTime(new Date().getTime());
        System.out.println("now: " + d2);
        System.out.println(d1.toString() + "\n" + d2.toString());

        SimpleDateFormat ft = new SimpleDateFormat("G E yyyy-MM-dd HH:mm:ss a zzz");
        System.out.println("d1: " + ft.format(d1));
        System.out.println("d2: " + ft.format(d2));

        ft = new SimpleDateFormat("G E yyyy-MM-dd hh:mm:ss:SSSS a z");
        System.out.println("d1: " + ft.format(d1));
        System.out.println("d2: " + ft.format(d2));

        ft = new SimpleDateFormat("G E yyyy-MM-dd hh:mm:ss:SSSS a zzz \n" +
                "一年中的日子: D\n" +
                "一个月中第几周的周几: F\n" +
                "一年中第几周: w\n" +
                "一个月中第几周: W\n" +
                "一天中的小时: k\n" +
                "格式小时: K\n"
                );
        System.out.println("d1: " + ft.format(d1));
        System.out.println("d2: " + ft.format(d2));

        System.out.printf("日期: %1$tc\n" +
                "日期: %1$ta %1$tb %1$td %1$tH:%1$tM:%1$tS %1$tZ %1$tY\n" +
                "年-月-日: %1$tF\n" +
                "年-月-日: %1$tY-%1$tm-%1$td\n" +
                "月/日/年: %1$tD\n" +
                "月/日/年: %1$tm/%1$td/%1$ty\n" +
                "HH:MM:SS PM: %1$tr\n" +
                "HH:MM:SS PM: %1$tI:%1$tM:%1$tS %1$tp\n" +
                "HH:MM:SS: %1$tT%n" +
                "HH:MM:SS: %1$tH:%1$tM:%1$tS%n" +
                "HH:MM: %<tR\n" +
                "HH:MM: %<tH:%<tM\n",
                d1);

        String str = String.format(Locale.US, "英文月份简称(b): %tb", d1);
        System.out.println(str);
        System.out.printf("本地月份简称(b): %tb\n", d1);
        str = String.format(Locale.US, "英文月份全称(B): %tB", d1);
        System.out.println(str);
        System.out.printf("本地月份全称(B): %tB\n", d1);

        str = String.format(Locale.US, "英文星期简称(a): %ta", d1);
        System.out.println(str);
        System.out.printf("本地星期简称(a): %ta\n", d1);
        str = String.format(Locale.US, "英文星期全称(A): %tA", d1);
        System.out.println(str);
        System.out.printf("本地星期全称(A): %tA\n", d1);

        System.out.printf("年分(Y): %tY\n", d1);
        System.out.printf("年分前两位(C): %tC\n", d1);
        System.out.printf("年分后两位(Y): %ty\n", d1);
        System.out.printf("一年的第几天(j): %tj\n", d1);
        System.out.printf("月份(m): %tm\n", d1);
        System.out.printf("日期（2位）(d): %td\n", d1);
        System.out.printf("日期（1-2位）(e): %te\n", d1);

        ft = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        String input = "3818-09-24 12:32:12";
        Date t;
        try {
        //    ft.setLenient(false);
            t = ft.parse(input);
            System.out.println(input + " parse as: " + t);
        } catch (ParseException e) {
            e.printStackTrace();
        }
    }

    public static void test13() {

        Calendar c1 = Calendar.getInstance();
        c1.set(2017, 12 - 1, 29);
        System.out.println(c1);
        System.out.println("Year: " + c1.get(Calendar.YEAR));
        System.out.println("Month: " + (c1.get(Calendar.MONTH) + 1));
        System.out.println("Date: " + c1.get(Calendar.DATE));
        System.out.println("Day_of_month: " + c1.get(Calendar.DAY_OF_MONTH));
        System.out.println("HOUR: " + c1.get(Calendar.HOUR));
        System.out.println("HOUR_OF_DAY: " + c1.get(Calendar.HOUR_OF_DAY));
        System.out.println("MINUTE: " + c1.get(Calendar.MINUTE));
        System.out.println("SECOND: " + c1.get(Calendar.SECOND));
        System.out.println("DAY_OF_WEEK: " + (c1.get(Calendar.DAY_OF_WEEK) - 1));
    }

	class Inner {

		private int _name;

		public Inner(int _i) {
			_name = _i;
			System.out.println(name);
			System.out.println(_name);
		}

		public int getCount() {
			System.out.println("getCount");
			return _name;
		}
	}

	private Inner ic = null;

	public Inner getInnerInstance(int i) {

		if (null == ic) {
			ic = new Inner(i);
		}
		return ic;
	}

	public static void test14() {

		Test test = new Test();
		//Test.Inner ic = test.new Inner(10);
		Test.Inner ic = test.getInnerInstance(10);
		for (int i = 0; i < ic.getCount(); i++) {
			System.out.println(i);
		}
	}

	public static void test15(String[] args) {

		for (int i = 0; i < args.length; i++) {
			System.out.println("args[" + i + "] = " + args[i]);
		}
	}

    public static void main(String[] args) {

        test15(args);
    }
}
