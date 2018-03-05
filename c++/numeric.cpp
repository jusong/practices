#include <iostream>
#include <limits>

using namespace std;

int
main(void) {
    static_assert(numeric_limits<char>::is_signed, "unsigned char");
    static_assert(10000 < numeric_limits<int>::max(), "small int");

//    cout << hex << numeric_limits<char>::min() << "~" << hex << numeric_limits<char>::max() << endl;
    char min = numeric_limits<char>::min();
    char max = numeric_limits<char>::max();
    printf("%x~%x\n", min, max);
    //cout << numeric_limits<int>::min() << "~" << numeric_limits<int>::max() << endl;
    
    return 0;
}
