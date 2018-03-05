#include <iostream>
#include <cstring>
#define private public
#define PRINT(msg1,msg2) do { std::cout << msg1 << " " << msg2 << std::endl; } while(0)

// template <class _Tp> struct remove_reference		{typedef _Tp type;};
// template <class _Tp> struct remove_reference<_Tp&>  {typedef _Tp type;};
// template <class _Tp> struct remove_reference<_Tp&&> {typedef _Tp type;};

// template <class _Tp>
// inline typename remove_reference<_Tp>::type&& move(_Tp&& __t) {
// 	typedef typename remove_reference<_Tp>::type _Up;
// 	return static_cast<_Up&&>(__t);
// }
using namespace std;

class A {
public:
	A(const char *pstr) {
		PRINT("constructor:",pstr);
		m_data = (pstr != 0 ? strcpy(new char[strlen(pstr) + 1], pstr) : 0);
	}
	A(const A &a) {
		PRINT("copy constructor",a.m_data);
		m_data = (a.m_data != 0 ? strcpy(new char[strlen(a.m_data) + 1], a.m_data) : 0);
	}
	A &operator =(const A &a) {
		PRINT("copy assigment",a.m_data);
		if (this != &a) {
			delete [] m_data;
			m_data = (a.m_data != 0 ? strcpy(new char[strlen(a.m_data) + 1], a.m_data) : 0);
		}
		return *this;
	}
	A(A &&a) : m_data(a.m_data) {
		PRINT("move constructor",m_data);
		a.m_data = 0;
	}
	A & operator = (A &&a) {
		PRINT("move assigment",a.m_data);
		if (this != &a) {
			m_data = a.m_data;
			a.m_data = 0;
		}
        return *this;
	}
	~A() {
        cout << m_data << " destructor" << endl;
        delete [] m_data;
    }
private:
	char * m_data;
};

void swap(A &a, A &b) {
	// A tmp(move(a));
	// a = move(b);
	// b = move(tmp);
	A tmp(a);
	a = b;
	b = tmp;
}

int main(int argc, char **argv, char **env) {
	A a("123"), b("456");
	swap(a, b);
    cout << a.m_data << endl;
	return 0;
}
