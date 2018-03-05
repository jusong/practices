namespace java multi.service
namespace cpp multi.service
namespace php multi.service
struct User
{
    1: i32 uid,
    2: string name,
    3: i8 age 
}
service UserService
{
    void storeUser(1: User user),
    User retrieveUserById(1: i32 uid)
}
