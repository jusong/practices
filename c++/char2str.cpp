#include <iostream>
#include <string>
#include <unistd.h>
using namespace std;

int main(void) {
    char a[4] = {0x99, 0x3D,0x78,0x99};
    for (int i = 0; i < 3; i++) {
        char c = a[i];
	cout << c << ':';
        while (c) {
            cout << (c & 0xF) << ' ';
            c = c >> 4;
            sleep(3);
        }
        cout << endl;
    }
    return 0;
}
