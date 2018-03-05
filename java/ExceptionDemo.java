/*****************************************************************
 * 文件名称：ExceptionDemo.java
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-01-04 14:47
 * 描    述：
 *****************************************************************/

import java.io.*;

public class ExceptionDemo {

	class MyException extends RuntimeException {

		private String name = "MyException";

		public String getName() {
			return name;
		}
	}

	public static void test1() {
		
		throw new ExceptionDemo().new MyException();
	}

	public static int test2() {

		try {
			C2 c2 = new C2();
			String name = c2.getName();
			System.out.println(name);

			C1 c1 = new C1();
			name = c1.getName();
			System.out.println(name);
		} catch (MyException1 e) {
			System.out.println("catch");
			System.out.println("catch1");
			System.out.println("catch2");
			System.exit(-1);
			return 1;
		} finally {
			System.out.println("finally");
			System.out.println("finally1");
			System.out.println("finally2");
			return 3;
		}
		//return 2;
	}

	public static void main(String[] args) {

		System.out.println(test2());
	}
}

class MyException1 extends Exception {
}
class MyException2 extends MyException1 {
}
class MyException3 extends MyException2 {
}

class C1 {

	public String getName() throws MyException2 {

		if (true) {
			throw new MyException2();
		}
		return "C1";
	}
}

class C2 extends C1 {

	public String getName() throws MyException2 {
		return "C2";
	}
}
