import org.apache.thrift.TProcessor; 
import org.apache.thrift.protocol.TMultiplexedProtocol; 
import org.apache.thrift.protocol.TBinaryProtocol; 
import org.apache.thrift.protocol.TCompactProtocol; 
import org.apache.thrift.protocol.TProtocolFactory; 
import org.apache.thrift.server.TServer;
import org.apache.thrift.server.TThreadPoolServer; 
import org.apache.thrift.server.TThreadPoolServer.Args; 
import org.apache.thrift.transport.TSocket; 
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

public class client {
	public static void main(String[] args) {  
	    try {  
			TSocket transport = new TSocket("121.199.42.40", 7911);
			//TSocket transport = new TSocket("localhost", 7911);
			TBinaryProtocol protocol = new TBinaryProtocol(transport);
			 
			TMultiplexedProtocol mp1 = new TMultiplexedProtocol(protocol,"TopicService");
			TopicService.Client service1 = new TopicService.Client(mp1);
			 
			TMultiplexedProtocol mp2 = new TMultiplexedProtocol(protocol,"UserService");
			UserService.Client service2 = new UserService.Client(mp2);
			 
			transport.open();
			  
			service1.storeTopic(new Topic(668, 2, "test topic","just a test!"));
			service2.storeUser(new User(888,"tom",(byte)8));
			 
			System.out.println(service1.retrieveTopicById(168));
			System.out.println(service2.retrieveUserById(999));
			transport.close();
	    } catch (Exception e) {  
	        e.printStackTrace();  
	    }  
	}
}
