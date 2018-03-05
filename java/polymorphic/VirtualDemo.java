public class VirtualDemo {

    public static void main(String[] args) {

        Salary s = new Salary("员工A", "北京", 3, 3600);
        Employee e = new Salary("员工B", "上海", 4, 5000);

//        System.out.println("使用Salary的引用调用mailCheck -- ");
        s.mailCheck();
//
//        System.out.println("使用Employee的引用调用mailCheck -- ");
        e.mailCheck();
        e = new Employee("员工C", "深圳", 5);
        e.mailCheck();
//
//        Employee ee = new Employee("员工C", "深圳", 5);
//        System.out.println("使用Employee的引用调用mailCheck -- ");
//        ee.mailCheck();
    }
}
