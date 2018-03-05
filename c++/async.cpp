#include <iostream>
#include <future>
#include <unistd.h>
#include <time.h>

using namespace std;

int count = 1;

void foo(const string name) {
  sleep(2);
  cout << name << endl << flush;
  count++;
}

int
main(void) {
  string name;
  
  auto f1 = async(launch::async, foo, "b1");
  auto f2 = async(launch::async, foo, "b2");
  
  cout << time(NULL) << endl;
  cout << "Count: " << count << endl;
  
  cout << "Input your name: ";
  cin >> name;
  for(int i = 0; i < count; i++) {
    cout << "Hello " << name << endl;
  }
  
  f1.get();
  f2.get();
  cout << time(NULL) << endl;
  
  return 0;
}
