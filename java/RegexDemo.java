/*****************************************************************
 * 文件名称：RegexDemo.java
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-01-02 10:20
 * 描    述：
 *****************************************************************/

import java.util.regex.*;

public class RegexDemo {

    public static void test1() {

        String content = "i am noob" +
            " from runoob.com";
        String pattern = ".*runoob.*";
        boolean isMatch = Pattern.matches(pattern, content);
        System.out.println("字符串" + content + "包含" + pattern + "? " + isMatch);
    }

    public static void test2() {

        String line = "This		is A goooo\boooogle 2000 windows 3.1 windows p\nrder was pl\\nced (for) QT3000! OK?";
        //String pattern = "(\\D*)(\\d+)(.*)";
        //String pattern = "(.*)(\\\\n)(.*)";
        //String pattern = "(.*)(\\(.*\\))(.*)";
        //String pattern = "(?:.*?)(0+)(?:.*)";
        //String pattern = "windows (?!2000|NT|95)(\\d+)";
        //String pattern = "(?=2000|NT|95) windows";
        //String pattern = "(\11\\w)";
        String pattern = "(\uae34)";

        Pattern r = Pattern.compile(pattern);

        Matcher m = r.matcher(line);
        if (m.find()) {

            System.out.println("Found value: " + m.group(0));
            //System.out.println("Found value: " + m.group(1));
            //System.out.println("Found value: " + m.group(2));
            //System.out.println("Found value: " + m.group(3));
            System.out.println("Found value: " + m.group(1));
        } else {
            System.out.println("No match!");
        }
    }

	public static void test3() {

		String pattern = "\\bcat\\b";
		String input = "cat cat cat cat cat";

		Pattern p = Pattern.compile(pattern);
		Matcher m = p.matcher(input);
		int count = 0;

		System.out.println(input);
		while (m.find()) {
			count++;
			System.out.println("Matche: " + count);
			System.out.println("start: " + m.start());
			System.out.println("end: " + m.end());
		}
	}

	public static void test4() {

		String regex = "foo";
		String input1 = "foooooooofoo";
		String input2 = "oppppooofoooooooooo";

		Pattern p = Pattern.compile(regex);
		Matcher m1 = p.matcher(input1);
		Matcher m2 = p.matcher(input2);

		System.out.println("input1 lookAt: " + m1.lookingAt());
		System.out.println("input1 matches: " + m1.matches());
		System.out.println("input2 lookAt: " + m2.lookingAt());
		System.out.println("input2 matches: " + m2.matches());
		System.out.println("input1 replaceFirst: " + m1.replaceFirst("FOO"));
		System.out.println("input1 replaceAll: " + m1.replaceAll("FOO"));
	}

	public static void test5() {

		String regex = "a*b";
		String input = "aabfooabfooaaabfoob";
		String replace = "-";

		System.out.println("regex: " + regex);
		System.out.println("input: " + input);
		System.out.println("replace: " + replace);

		Pattern p = Pattern.compile(regex);
		Matcher m = p.matcher(input);
		StringBuffer sb = new StringBuffer();
		while (m.find()) {
			m.appendReplacement(sb, replace);
			System.out.println(sb);
		}
		//m.appendTail(sb);
		//System.out.println(sb.toString());
	}

	public static void main(int a) {
	}

    public static void main(String[] args) {

        test5();
    }
}
