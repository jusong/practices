// This autogenerated skeleton file illustrates how to build a server.
// You should copy it to another filename to avoid overwriting it.

#include "common.h"

using namespace ::apache::thrift;
using namespace ::apache::thrift::protocol;
using namespace ::apache::thrift::transport;
using namespace ::apache::thrift::server;
using namespace ::apache::thrift::concurrency;

using boost::shared_ptr;

using namespace  ::Fasthand;
using namespace  ::tutorial;
using namespace  ::shared;

int main(int argc, char **argv) {
  int port = 9090;
  //shared_ptr<FasthandServiceHandler> handler(new FasthandServiceHandler());
  //shared_ptr<TProcessor> processor(new FasthandServiceProcessor(handler));
  //shared_ptr<TProcessor> processor(new FasthandServiceProcessor(new FasthandServiceHandler()));

  //单服务处理器
  shared_ptr<TProcessor> fasthandServiceProcessor(new FasthandServiceProcessor(shared_ptr<FasthandServiceHandler>(new FasthandServiceHandler())));
  shared_ptr<TProcessor> calculatorProcessor(new CalculatorProcessor(shared_ptr<CalculatorHandler>(new CalculatorHandler())));

  //多路复用处理器
  shared_ptr<TMultiplexedProcessor> processor(new TMultiplexedProcessor());
  processor->registerProcessor("FasthandService", fasthandServiceProcessor);
  processor->registerProcessor("Calculator", calculatorProcessor);

  shared_ptr<TServerTransport> serverTransport(new TServerSocket(port));
  shared_ptr<TTransportFactory> transportFactory(new TBufferedTransportFactory());
  shared_ptr<TProtocolFactory> protocolFactory(new TBinaryProtocolFactory());
  
  //线程池
  shared_ptr<ThreadManager> threadManager = ThreadManager::newSimpleThreadManager(15);
  shared_ptr<PosixThreadFactory> threadFactory = shared_ptr<PosixThreadFactory>(new PosixThreadFactory());
  threadManager->threadFactory(threadFactory);
  threadManager->start();
	
  //单线程阻塞式服务
  //TSimpleServer server(processor, serverTransport, transportFactory, protocolFactory);
  //多线程阻塞式服务
  TThreadPoolServer server(processor, serverTransport, transportFactory, protocolFactory, threadManager);
  server.serve();

  return 0;
}
