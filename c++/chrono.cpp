#include <iostream>
#include <chrono>

using namespace std;
using namespace std::chrono;

int
main(void) {
  auto t0 = high_resolution_clock::now();
  for(int i = 0; i < 1000000000; i++);
  auto t1 = high_resolution_clock::now();
  cout << duration_cast<milliseconds>(t1 - t0).count() << "msec" << endl;
  cout << duration_cast<nanoseconds>(t1 - t0).count() << "msec" << endl;
  return 0;
}
