/*****************************************************************
 *   文件名称：Override.java
 *   创 建 者：blacknc
 *   创建日期：2017-12-01 14:27
 *   描    述：测试类方法重写
 *****************************************************************/

public class Override {

    public static void main(String[] args) {

        Foo foo = new Foo("foo");
        foo.prtName();
    }
}

class Base {

    protected String name;

    public Base(String name) {

        this.name = name;
    }

    public void prtName() {

        System.out.println("Base类打印名字:" + name);
    }
}

class Foo extends Base {

    public Foo(String name) {

        super(name);
    }

    public void prtName() {

        System.out.println("Foo类打印名字:" + name);
    }
}
