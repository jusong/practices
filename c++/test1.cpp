#include <iostream>
#include <string>
using namespace std;

class A {
public:
    A() {
        cout << "Construct: " << this << endl;
    }
    A(int) {
        cout << "Construct(int): " << this << endl;
    }
    A(const A &c) {
        cout << "Copy Construct: " << &c << " --> " << this << endl;
    }
    ~A() {
        cout << "Disstruct: " << this << endl;
    }
};

A get() {
    A a;
    return a;
}

int main() {
    A a = get();
    const int i = 10;
    cout << &i << endl;
    A& ra = a;
    const A& cra = a;
    A&& rra = get();
    int && rr = 12;
}
