import org.apache.thrift.TException;

import multi.service.User;
import multi.service.UserService;

public class UserServiceHandler implements UserService.Iface{
	public void storeUser(User user) throws TException {
		System.out.println("storeUser: ");
		System.out.println("	uid: " + user.getUid());
		System.out.println("	name: " + user.getName());
		System.out.println("	age: " + user.getAge());
	}
	public User retrieveUserById(int uid)  throws TException {
		System.out.println("retrieveUserById: " + uid);
		return new User(uid, "jiafangdong", 25);
	}
}
