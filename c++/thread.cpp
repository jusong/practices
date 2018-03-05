#include <iostream>
#include <thread>
#include <unistd.h>

using namespace std;

void foo(const string &name, const int tm) {
  sleep(tm);
  cout << "Hi, i'm " << name << endl;
}

struct F {
  void operator()(const string &name, const int tm) {
    sleep(tm);
    cout << "Hi, i'm " << name << endl;
  }
};

void user() {
  thread t1{foo, "t1", 10};
  thread t2{foo, "t2", 3};
  thread t3{F(), "t3", 3};

  t1.join();
  t2.join();
  t3.join();
  cout << "user end" << endl;
}

int
main(void) {
  user();
  cout << "user back" << endl;
  return 0;
}
