import org.apache.thrift.TProcessor; 
import org.apache.thrift.protocol.TBinaryProtocol; 
import org.apache.thrift.protocol.TCompactProtocol; 
import org.apache.thrift.protocol.TProtocolFactory; 
import org.apache.thrift.server.TServer;
import org.apache.thrift.server.TThreadedSelectorServer; 
import org.apache.thrift.transport.TNonblockingServerSocket; 
import org.apache.thrift.transport.TNonblockingServerTransport; 
import org.apache.thrift.transport.TTransportFactory; 
import org.apache.thrift.transport.TFramedTransport; 
import org.apache.thrift.transport.TTransportException; 
import org.apache.thrift.TMultiplexedProcessor; 
import org.apache.thrift.*; 

import Fasthand.Service.User.*;
import Fasthand.Service.Article.*;
import Fasthand.Handler.FasthandUserServiceHandler;
import Fasthand.Handler.FasthandArticleServiceHandler;

public class threadSelectorServer {
	public static void main(String[] args) {
		int port = 9511;
	    try {
			//传输通道 - 非阻塞方式  
			TNonblockingServerTransport serverTransport = new TNonblockingServerSocket(port);  
									              
			//异步IO，需要使用TFramedTransport，它将分块缓存读取。  
			TTransportFactory transportFactory = new TFramedTransport.Factory();  

	        //使用普通二进制协议  
	        //TProtocolFactory proFactory = new TBinaryProtocol.Factory();  
	        //使用高密度二进制协议  
	        TProtocolFactory proFactory = new TCompactProtocol.Factory();  
	          
	        //设置多路复用处理器  
			TMultiplexedProcessor processor = new TMultiplexedProcessor();
			//不同service分别添加处理器
			processor.registerProcessor("FasthandArticleService", 
	        	new FasthandArticleService.Processor<FasthandArticleService.Iface>(new FasthandArticleServiceHandler()));
			processor.registerProcessor("FasthandUserService", 
	        	new FasthandUserService.Processor<FasthandUserService.Iface>(new FasthandUserServiceHandler()));
	          
	        //创建服务器  
		    TServer server = new TThreadedSelectorServer(  
				    new TThreadedSelectorServer.Args(serverTransport)  
				   .protocolFactory(proFactory) 
               	   .transportFactory(transportFactory)  
               	   .processor(processor)
				);  
	
	        System.out.println("Start Fasthand NonBlock Server on port " + port + "...");  
			//启动服务
	        server.serve();  
	    } catch (Exception e) {
	        e.printStackTrace();
	    }  
	}
}
