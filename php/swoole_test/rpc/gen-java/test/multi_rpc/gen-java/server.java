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

import multi.service.User;
import multi.service.UserService;
import multi.service.Topic;
import multi.service.TopicService;
import multi.TopicServiceHandler;
import multi.UserServiceHandler;

public class server {
	public static void main(String[] args) {  
	    try {  
	        //设置传输通道，普通通道  
	        TServerTransport serverTransport = new TServerSocket(7911);  
	          
	        //使用高密度二进制协议  
	        TProtocolFactory proFactory = new TCompactProtocol.Factory();  
	          
	        //设置处理器HelloImpl  
			TMultiplexedProcessor processor = new TMultiplexedProcessor();
	          
	        //创建服务器  
	        TServer server = new TThreadPoolServer(  
	                new Args(serverTransport)  
	                .protocolFactory(proFactory)  
	                .processor(processor)  
	            );  
	
			processor.registerProcessor("TopicService", 
	        	new TopicService.Processor<TopicService.Iface>(new TopicServiceHandler()));
			processor.registerProcessor("UserService", 
	        	new UserService.Processor<UserService.Iface>(new UserServiceHandler()));
	          
	        System.out.println("Start server on port 7911...");  
	        server.serve();  
	    } catch (Exception e) {  
	        e.printStackTrace();  
	    }  
	}
}
