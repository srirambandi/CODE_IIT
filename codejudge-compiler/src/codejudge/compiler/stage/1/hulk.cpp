#include <iostream>
#include <string>

using namespace std;

int main() {
    int n;
    cin>>n;
    string str ="";
    string a[2];
    a[0] = "I hate ";
    a[1] = "I love ";
    string help = "that ",end = "it";
    for (int i = 1; i<=n; i++) {
        if (i%2 == 1) {
            str+=a[0];
        }
        else{
            str+=a[1];
        }
        str+=help;
    }
    int k = str.length();
    str = str.substr(0,k-5);
    str+=end;
    cout<<str;
    return 0;
}