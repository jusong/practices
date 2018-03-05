package Fasthand.Handler;

import org.apache.thrift.TException;
import Fasthand.Service.Article.*;
import java.util.List;
import java.util.ArrayList;
import java.util.Map;
import java.util.HashMap;
import java.util.Date;  
import java.text.SimpleDateFormat;  

public class FasthandArticleServiceHandler implements FasthandArticleService.Iface {
	//保存文章
	public void storeArticle(FasthandArticle article) throws TException {
		System.out.println("storeArticle: ");
		System.out.println(article);
	}

	//根据ID获取文章
	public FasthandArticle retrieveArticleById(int id) throws TException {
		System.out.println("retieveArticleById: " + id);
		return new FasthandArticle(id, 9, "根据ID查取的文章", "书山有路勤为径，学海无涯苦做舟。。。", new SimpleDateFormat("yyyy-MM-dd hh:mm:ss").format(new Date()));
	}

	//根据UID获取用户的文章列表
	public List<FasthandArticle> retrieveArticleByUid(int uid) throws TException {
		System.out.println("retieveArticleByUid: " + uid);
		List<FasthandArticle> articleList = new ArrayList<FasthandArticle>();
		articleList.add(new FasthandArticle(1, uid, "根据UID查取的文章1", "书山有路勤为径，学海无涯苦做舟。。。", new SimpleDateFormat("yyyy-MM-dd hh:mm:ss").format(new Date())));
		articleList.add(new FasthandArticle(2, uid, "根据UID查取的文章2", "书山有路勤为径，学海无涯苦做舟。。。", new SimpleDateFormat("yyyy-MM-dd hh:mm:ss").format(new Date())));
		articleList.add(new FasthandArticle(3, uid, "根据UID查取的文章3", "书山有路勤为径，学海无涯苦做舟。。。", new SimpleDateFormat("yyyy-MM-dd hh:mm:ss").format(new Date())));
		return articleList;
	}

	//根据UID列表获取用户的文章列表
	public Map<Integer,List<FasthandArticle>> retrieveNewArticleList(List<Integer> uidArray) throws TException {
		System.out.println("retieveNewArticle: ");
		Map<Integer,List<FasthandArticle>> userArticleList = new HashMap<Integer,List<FasthandArticle>>();
		for (Object uid : uidArray) {
			List<FasthandArticle> articleList = new ArrayList<FasthandArticle>();
			for (int i = 0; i < (int)uid; i++) {
				articleList.add(new FasthandArticle(i, (int)uid, "根据UID查取的文章" + i, "书山有路勤为径，学海无涯苦做舟。。。", new SimpleDateFormat("yyyy-MM-dd hh:mm:ss").format(new Date())));
			}
			userArticleList.put((Integer)uid, articleList);
		}
		return userArticleList;
	}
}
