package multi;
import org.apache.thrift.TException;
  
import multi.service.Topic;
import multi.service.TopicService;
  
public class TopicServiceHandler implements TopicService.Iface {
	public void storeTopic(Topic topic) throws TException {
	        System.out.println("storeTopic: ");
	        System.out.println("	id:" + topic.getId());
	        System.out.println("	uid:" + topic.getUid());
	        System.out.println("	name:" + topic.getName());
	        System.out.println("	content:" + topic.getContent());
	}
	public Topic retrieveTopicById(int id) throws TException {
	        System.out.println("retrieveTopicById: " + id);
	        return new Topic(id, 1, "test1", "test1");
	}
	public Topic retrieveTopicByUid(int uid) throws TException {
	        System.out.println("retrieveTopicByUid: " + uid);
	        return new Topic(2, uid, "test2", "test2");
	}
}
