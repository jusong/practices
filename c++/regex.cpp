#include <iostream>
#include <regex>
#include <string>

using namespace std;

int
main(void) {
    regex pat(R"(\w{2}\s*\d{5}(-\d{4})?)");
    // cout << "pattern: " << pat << endl;

    int lineno = 0;
    for(string line; getline(cin, line); lineno++) {
        smatch match;
        if (regex_match(line, match, pat)) {
            cout << lineno << ": " << match[0] << endl;
        }
    }

    return 0;
}
