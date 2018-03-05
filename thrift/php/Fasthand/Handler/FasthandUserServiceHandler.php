<?php
namespace Fasthand\Handler;

use thrift\TException;
use Fasthand\Service\User.*;

public class FasthandUserServiceHandler implements FasthandUserService.Iface {
	public void storeUser(FasthandUser user) throws TException {
		System.out.println("storeUser: ");
		System.out.println(user);
	}
	public FasthandUser retrieveUserById(int id) throws TException {
		System.out.println("retrieveUserById: " + id);
		return new FasthandUser(id, "jiafangdong", "jiafd", "123456ooo", (byte)25, "其他信息");
	}
	public void deleteUserById(int id) throws TException {
		System.out.println("deleteUserById: " + id);
	}
}
