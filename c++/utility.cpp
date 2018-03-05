#include <iostream>
#include <string>
#include <utility>
#include <tuple>

using namespace std;

int
main(void) {
    auto np = make_pair("name", "jiafangdong");
    auto ap = make_pair("age", 25);

    cout << np.first << ": " << np.second << endl;
    cout << ap.first << ": " << ap.second << endl;

    tuple<int,double> tp(23,123.3);
    get<0>(tp) = 200;
    
    int myint;
    double mydouble;
    tie(myint, ignore) = tp;
    cout << myint << ", " << mydouble << endl;
    
    myint = 100;
    cout << get<0>(tp) << endl;

    get<0>(tp) = 200;
    cout << myint << endl;
    
    return 0;
}
