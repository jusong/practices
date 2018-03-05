#include <iostream>
#include <random>
#include <vector>

using namespace std;

class Rand_int {
public:
    Rand_int(int low, int high):dist{low, high} {}
    int operator()() { return dist(dre); }
private:
    default_random_engine dre;
    uniform_int_distribution<> dist;
};

int
main (void) {
    Rand_int rint(0, 5);

    vector<int> mn(6);
    for(int i = 0; i != 200; i++) {
        mn[rint()]++;
    }

    for(int i = 0; i !=mn.size(); i++) {
        cout << i << "\t";
        for(int j = 0; j != mn[i]; j++) {
            cout << "*";
        }
        cout << "\n";
    }

    return 0;
}
