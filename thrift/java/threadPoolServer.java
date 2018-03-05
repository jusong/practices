import org.apache.thrift.TProcessor; 
import org.apache.thrift.protocol.TBinaryProtocol; 
import org.apache.thrift.protocol.TCompactProtocol; 
import org.apache.thrift.protocol.TProtocolFactory; 
import org.apache.thrift.server.TServer;
import org.apache.thrift.server.TThreadPoolServer; 
import org.apache.thrift.server.TThreadPoolServer.Args; 
import org.apache.thrift.transport.TServerSocket; 
import org.apache.thrift.transport.TServerTransport; 
import org.apache.thrift.transport.TTransportException; 
import org.apache.thrift.TMultiplexedProcessor; 
import org.apache.thrift.*; 

import Fasthand.Service.User.*;
import Fasthand.Service.Article.*;
import Fasthand.Handler.FasthandUserServiceHandler;
import Fasthand.Handler.FasthandArticleServiceHandler;

public class threadPoolServer {
	public static void main(String[] args) {
		int port = 9500;
	    try {
	        //设置传输通道，普通通道  
	        TServerTransport serverTransport = new TServerSocket(port);  
	          
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
	        TServer server = new TThreadPoolServer(  
	                new TThreadPoolServer.Args(serverTransport)  
	                .protocolFactory(proFactory)  
	                .processor(processor)  
	            );  
	
	        System.out.println("Start Fasthand Server on port " + port + "...");  
			//启动服务
	        server.serve();  
	    } catch (Exception e) {
	        e.printStackTrace();
	    }  
	}
}
