public class Test {

    public static void main(String[] args) {
        show(new Cat());
        show(new Dog());

        Animal a = new Cat();
        a.eat();

        //Cat c = (Cat)a;
        //c.work();
        a.work();
    }

    public static void show(Animal a) {

        a.eat();

//        if (a instanceof Cat) {
//
//            Cat c = (Cat)a;
//            c.work();
//        } else if (a instanceof Dog) {
//
//            Dog d = (Dog)a;
//            d.work();
//        }
        a.work();
    }
}

interface AnimalIfc {
    void eat();
    void work();
}

abstract class Animal implements AnimalIfc {
}

class Cat extends Animal {

    public void eat() {

        System.out.println("猫爱吃鱼");
    }

    public void work() {

        System.out.println("猫可以爬树、抓老鼠");
    }
}

class Dog extends Animal {
    
    public void eat() {

        System.out.println("小狗爱吃骨头");
    }

    public void work() {

        System.out.println("小狗跑得快，可以看家");
    }
}
