namespace java Fasthand.Service.User
namespace php Fasthand.Service.User

//用户结构
struct FasthandUser 
{
    1: i32 id = 1,
    2: string username,
    3: string nick,
    4: string password,
    5: i8 age,
	6: string extendInfo,
}

//用户相关服务
service FasthandUserService
{
	//保存用户
    void storeUser(1: FasthandUser user),

	//根据ID获取用户
    FasthandUser retrieveUserById(1: i32 id),

	//删除用户
    void deleteUserById(1: i32 id)
}
