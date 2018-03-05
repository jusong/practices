/*****************************************************************
 * 文件名称：TestInner.java
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-01-02 15:51
 * 描    述：
 *****************************************************************/

public class TestInner {

	public static void main(String[] args){

		// 初始化Bean1
		TestInner.Bean1 bean1 = new TestInner().new Bean1();
		bean1.I++;

		// 初始化Bean2
		TestInner.Bean2 bean2 = new TestInner.Bean2();
		bean2.J++;

		//初始化Bean3
		Bean.Bean3 bean3 = new Bean().new Bean3();
		bean3.k++;

		TestInner.Bean1.Bean4 bean4 = new TestInner().new Bean1().new Bean4();
		bean4.L++;
	}

	class Bean1{
		public int I = 0;

		class Bean4 {
			public int L = 0;
		}
	}

	static class Bean2{
		public int J = 0;
	}
}

class Bean{
	class Bean3{
		public int k = 0;
	}
}
