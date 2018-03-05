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
import java.util.List;
import java.util.ArrayList;
import java.util.Date;
import java.text.SimpleDateFormat;

import Fasthand.Service.User.*;
import Fasthand.Service.Article.*;

public class threadPoolClient {
	public static void main(String[] args) {  
	    try {
			//创建传输通道
			TSocket transport = new TSocket("localhost", 9511);
			
			//传输协议	
			//TBinaryProtocol protocol = new TBinaryProtocol(transport);
			TCompactProtocol protocol = new TCompactProtocol(transport);
			
			//多路复用传输协议
			//user
			TMultiplexedProtocol userMp = new TMultiplexedProtocol(protocol,"FasthandUserService");
			FasthandUserService.Client userClient = new FasthandUserService.Client(userMp);
			//article
			TMultiplexedProtocol articleMp = new TMultiplexedProtocol(protocol,"FasthandArticleService");
			FasthandArticleService.Client articleClient = new FasthandArticleService.Client(articleMp);

			//打开传输 
			transport.open();
			
			//user接口测试  
			userClient.storeUser(new FasthandUser(5, "jiafangdong", "jiafd", "o12345665", (byte)25, "其他信息"));
			System.out.println(userClient.retrieveUserById(999));
			userClient.deleteUserById(5);
			
			//article接口测试
			articleClient.storeArticle(new FasthandArticle(23, 5, "大话西游", "爱你亿万年！", new SimpleDateFormat("yyyy-MM-dd hh:mm:ss").format(new Date())));
    		System.out.println(articleClient.retrieveArticleById(34));
    		System.out.println(articleClient.retrieveArticleByUid(5));
			List<Integer> uidArray = new ArrayList<Integer>();
			uidArray.add(1);
			uidArray.add(2);
			uidArray.add(4);
    		System.out.println(articleClient.retrieveNewArticleList(uidArray));

			//关闭传输
			transport.close();
	    } catch (Exception e) {  
	        e.printStackTrace();  
	    }  
	}
}
