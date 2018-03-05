#include <iostream>
#include <vector>
#include <map>
#include <string>

using namespace std;

template<class K, class V>
class Map {
 public:
    V& operator[](const K& k);
    pair<K, V>* begin() { return &elem[0]; }
    pair<K, V>* end() { return &elem[0] + elem.size(); }
 private:
    vector<pair<K, V>> elem;
};

template<class K, class V>
V& Map<K, V>::operator[](const K& k) {
    for(auto& i : elem) {
        if (k == i.first) {
            return i.second;
        }
    }
    elem.push_back({k, V{}});
    return elem.back().second;
}

int main(void) {
    Map<string, int> buf;
    for(string s; cin >> s;) {
        buf[s]++;
    }
    for(const auto& i : buf) {
        cout << i.first << ": " << i.second << endl;
    }
}
