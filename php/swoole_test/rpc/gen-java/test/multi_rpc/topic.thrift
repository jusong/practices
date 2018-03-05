namespace java multi.service
namespace cpp multi.service
namespace php multi.service

struct Topic
{
    1: i32 id,
    2: i32 uid,
    3: string name,
    4: string content
}
service TopicService
{
    void storeTopic(1: Topic topic),
    Topic retrieveTopicById(1: i32 id)
    Topic retrieveTopicByUid(1: i32 uid)
}
