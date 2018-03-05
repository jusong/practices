namespace java Fasthand.Service.Article
namespace php Fasthand.Service.Article

//文章结构
struct FasthandArticle 
{
    1: i32 id,
    2: i32 uid,
    3: string title,
    4: string content,
	5: string create_time
}

//文章相关服务
service FasthandArticleService
{
	//保存文章
    void storeArticle(1: FasthandArticle article),

	//根据ID获取文章
    FasthandArticle retrieveArticleById(1: i32 id),

	//根据UID获取用户的文章列表
    list<FasthandArticle> retrieveArticleByUid(1: i32 uid),

	//根据UID列表获取用户的文章列表
	map<i32,list<FasthandArticle>> retrieveNewArticleList(1: list<i32> uidArray),
}
