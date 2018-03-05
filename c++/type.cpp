#include <iostream>
#include <iterator>
#include <vector>
#include <forward_list>
#include <algorithm>
#include <type_traits>

using namespace std;

void sort_ts(vector<int> &, forward_list<int> &);

template<typename C>
void sort(C &c);

template<typename Ran>
void sort_helper(Ran begin, Ran end, random_access_iterator_tag);

template<typename For>
void sort_helper(For begin, For end, forward_iterator_tag);

template<typename C>
using iterator_type = typename C::iterator;

template<typename lter>
using iterator_category = typename iterator_traits<lter>::iterator_category;

int
main(void) {
  vector<int> vi{1,345,34,3,25,78,88967,5,345,667,56,356,30};
  forward_list<int> fi{1,345,34,3,25,78,88967,5,345,667,56,356,30};

  sort_ts(vi, fi);

  cout << "vi: ";
  for(auto i: vi) {
    cout << i << " ";
  }
  cout << endl;
  
  cout << "fi: ";
  for(auto i: fi) {
    cout << i << " ";
  }
  cout << endl;
  
  return 0;
}

void
sort_ts(vector<int> &vi, forward_list<int> &fi) {
  sort(vi);
  sort(fi);
}

template<typename C>
void sort(C &c) {
  using iter = iterator_type<C>;
  //sort_helper(c.begin(), c.end(), iterator_category<iter>{});
  sort_helper(c.begin(), c.end(), typename iterator_traits<iter>::iterator_category{});
}

template<typename Ran>
void sort_helper(Ran begin, Ran end, random_access_iterator_tag) {
  sort(begin, end);
}

template<typename For>
void sort_helper(For begin, For end, forward_iterator_tag) {
  //vector<typename For::value_type> v{begin, end};
  vector<typename remove_reference<decltype(*begin)>::type> v{begin, end};
  sort(v.begin(), v.end());
  copy(v.begin(), v.end(), begin);
}
