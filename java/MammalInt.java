package animals;
/*****************************************************************
 * 文件名称：MammalInt.java
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2017-12-18 18:16
 * 描    述：
 *****************************************************************/

public class MammalInt implements Animal {

    public void eat() {

        System.out.println("Mammal eats");
    }

    public void travel() {

        System.out.println("Mammal travels");
    }

    public int noOfLegs() {
        return 0;
    }

    public static void main(String args[]) {

        MammalInt m = new MammalInt();
        m.eat();
        m.travel();
    }
}
