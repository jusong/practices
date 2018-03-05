public class Salary extends Employee {

    private double salary;

    public Salary(String name, String address, int number, int salary) {

        super(name, address, number);
        System.out.println("Salary 构造函数");
        setSalary(salary);
    }

    public void mailCheck() {

        System.out.println("邮寄支票给:" + getName() + ", 工资为:" + getSalary());
    }

    public double getSalary() {

        return salary;
    }

    public void setSalary(double salary) {

        if (salary >= 0.0) {

            this.salary = salary;
        }
    }

    public double computePay() {

        System.out.println("计算工资，付给:" + getName());

        return salary / 52;
    }
}
