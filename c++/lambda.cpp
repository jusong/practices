#include <algorithm>
#include <iostream>
#include <vector>
using namespace std;

int main(void) {
    vector<int> v;
    int i;
    
    while(cin >> i) {
        v.push_back(i);
    }

    cout << "=====================" << endl;
    auto j = find_if(v.begin(), v.end(), [](int &i){return i >= 10;});
    
    return 0;
}
