#include <iostream>
#include <future>
#include <thread>
#include <numeric>
#include <vector>
#include <exception>
#include <unistd.h>
#include <stdlib.h>
#include <time.h>

using namespace std;

double accum(double *, double *, double);
double comp2(vector<double> &);
double comp4(vector<double> &);
double random(double, double);

int
main(void) {
  try {
    vector<double> vd;
    srand((unsigned)time(NULL));
    for(int i = 0; i < 1000; i++) {
      double t = random(0.0, 1000000.0);
      cout << t << "	";
      vd.push_back(t);
    }
    double sum = comp4(vd);
    cout << "=============" << endl;
    cout << "Sum: " << sum << endl;
  } catch(exception &e) {
    cout << e.what() << endl;
  }
  return 0;
}

double
random(double start, double end)
{
  return start + (end - start) * rand() / (RAND_MAX + 1.0);
}

double
accum(double *f, double *e, double init) {
  cout << "\nAccum: " << *f << "-" << *e << endl;
  return accumulate(f, e, init);
}

double
comp2(vector<double> &vd) {
  using Task_type = double(double*, double*, double);
  packaged_task<Task_type> pt0{accum};
  packaged_task<Task_type> pt1{accum};
  future<double> f0{pt0.get_future()};
  future<double> f1{pt1.get_future()};

  double *first = &vd[0];
  double *mid = &vd[0] + vd.size() / 2;
  double *end = &vd[0] + vd.size();

  thread t0{move(pt0), first, mid, 0};
  thread t1{move(pt1), mid, end, 0};

  t0.detach();
  t1.detach();
  
  return f0.get() + f1.get();
}

double
comp4(vector<double> &vd) {
  auto v0 = &vd[0];
  auto sz = vd.size();

  auto f0 = async(accum, v0, v0 + sz / 4, 0);
  auto f1 = async(accum, v0 + sz / 4, v0 + sz / 2, 0);
  auto f2 = async(accum, v0 + sz / 2, v0 + sz * 3 / 4, 0);
  auto f3 = async(accum, v0 + sz * 3 / 4, v0 + sz, 0);

  return f0.get() + f1.get() + f2.get() + f3.get();
}
