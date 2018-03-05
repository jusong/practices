#include <iostream>
#include <queue>
#include <thread>
#include <condition_variable>
#include <mutex>
#include <unistd.h>

using namespace std;

class Message{
public:
  Message(int _count) {
    m_iCount = _count;
  }
  int m_iCount;
};

mutex mtx, outMtx;
condition_variable cnd;
queue<Message> myqueue;

void cum();
void pcd();

int
main(void) {
  thread c{cum};
  thread p{pcd};

  c.join();
  p.join();

  return 0;
}

void pcd() {
  for(int i = 0; true; i++) {
    unique_lock<mutex> outLck{outMtx};
    cout << "[==================]" << endl;
    cout << "[pcd: producing ..." << endl;
    outLck.unlock();

    sleep(20);
    Message msg(i);
    unique_lock<mutex> lck{mtx};
    myqueue.push(msg);

    outLck.lock();
    cout << "[pcd: produc ok ..." << endl;
    cout << "[__________________]" << endl << endl;
    outLck.unlock();
    
    cnd.notify_one();
  }
}

void cum() {
  while(true) {
    unique_lock<mutex> lck{mtx};
    
    unique_lock<mutex> outLck{outMtx};
    cout << "cum: waiting ..." << endl;
    outLck.unlock();
    
    cnd.wait(lck);
    
    auto m = myqueue.front();
    myqueue.pop();

    outLck.lock();
    cout << "cum: get " << m.m_iCount << endl;
    outLck.unlock();
    
    lck.unlock();
  }
}
