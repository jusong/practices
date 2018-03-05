/*****************************************************************
 * 文件名称：InnerClass.java
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-01-05 11:06
 * 描    述：
 *****************************************************************/

public class InnerClass {

	public static void main(String[] args) {

		Circle.Draw draw = new Circle().new Draw();
		draw.doDraw();

		Circle circle = new Circle();
		circle.doDraw();
	}
}

class Circle {

	private double d = 1;
	public static int i = 10;

	void doDraw() {
		System.out.println("====== Outter BEGIN =======");
		System.out.println("outter d: " + d);
		System.out.println("outter i: " + i);
		Draw draw = new Draw();
		System.out.println("inner d: " + draw.d);
		System.out.println("inner i: " + draw.i);
		System.out.println("====== Outter END =======");

		class C1 {
		}

	}

	void doDestory() {
		class C1 {
		}

		class C2 {
		}
	}

	class Draw {

		private double d = 1;
		public int i = 10;

		protected void doDraw() {
			System.out.println("====== Inner BEGIN =======");
			System.out.println("inner d: " + d);
			System.out.println("inner i: " + i);
			System.out.println("outter d: " + Circle.this.d);
			System.out.println("outter i: " + Circle.this.i);
			Circle.this.doDraw();
			System.out.println("====== Inner END =======");
		}
	}
}
