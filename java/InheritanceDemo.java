/*****************************************************************
 * 文件名称：InheritanceDemo.java
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-01-04 16:05
 * 描    述：
 *****************************************************************/

import java.io.*;

public class InheritanceDemo {

	public static void main(String[] args) throws Exception {

		final C2 c2 = new C2();
		C2.testDefault();
		C2.testDefault("hello");
		c2.testProtected();
		c2.testProtected("hello");

		if ((Object)c2.name instanceof String) {
			System.out.println("c2.name type is String");
		} else if (c2.name instanceof char[]) {
			System.out.println("c2.name type is char[]");
		}

		C1 c1 = c2;
		c1.testDefault();
		c1.testProtected();

		if ((Object)c1.name instanceof String) {
			System.out.println("c1.name type is String");
		} else if ((Object)c1.name instanceof char[]) {
			System.out.println("c1.name type is char[]");
		}

		class AutoClass {

			AutoClass() {
				for (int i = 0; i < 1; i++) {
					//int c1 = 0;
					if ((Object)c1 instanceof Integer) {
						System.out.println("c1 type is int");
					} else if ((Object)c1 instanceof C1) {
						System.out.println("c1 type is C1");
					}
				}
			}
		}

		InheritanceDemo ID = new InheritanceDemo();
		AutoClass ac = new AutoClass();
		C1.InnerC1 innerC1 = c2.new InnerC1();
	}
}

class C1 {

	protected class InnerC1 {
	}

	public String name;

	C1() {
		name = "C1";
	}

	C1(String name) {
		this.name = name;
	}

	public void setName(String name) {
		this.name = name;
	}

	public String getName() {
		return name;
	}

	public void testPublic() {
		System.out.println("C1:testPublic");
	}

	protected void testProtected() throws Exception {
		System.out.println("C1:testProtected");
	}

	private void testPrivate() {
		System.out.println("C1:testPrivate");
	}

	public static void testDefault() {
		System.out.println("C1:testDefault");
	}
}

class C2 extends C1 {

	public char[] name;

	C2() {
		super();
		name = new char[]{'C', '2'};
		System.out.println("C2()");
	}

	public void setName(char[] name) {
		this.name = name;
	}

	private C2(char[] name) {
		super(new String(name));
		this.name = name;
		System.out.println("C2(String name)");
	}

	public void testProtected() throws IOException, Exception, RuntimeException {
		System.out.println("C2:testProtected");
		super.testProtected();
	}

	protected void testProtected(String str) {
		System.out.println("C2:testProtected " + str);
	}
	
	public static void testDefault() {
		System.out.println("C2:testDefault");
	}

	public static void testDefault(String str) {
		System.out.println("C2:testDefault " + str);
	}
}
