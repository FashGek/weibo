from mysql import DataBase
from redisConn import r as redis

HOST, USER, PASSWD, DB, PORT = ('localhost', 'root', 'zenxds', 'weibo', 3306)
db = DataBase(host=HOST, user=USER, passwd=PASSWD, db=DB, port=PORT)

sql = "SELECT * FROM weibo WHERE type=0"
weibos = db.execute(sql)

def get_score(forward_count, comment_count, collect_count):
	forward_count = int(forward_count)
	collect_count = int(collect_count)
	collect_count = int(collect_count)
	return forward_count*2 + comment_count*1 + collect_count*2

for weibo in weibos:

	weibo_id = weibo['id']

	key = "weibo:%s:forwardCount" % weibo_id;
	forward_count = redis.get(key) if redis.get(key) else 0


	sql = "SELECT count(*) as count FROM comment WHERE weibo_id=%s"
	params = (weibo_id,)
	r = db.execute(sql, params, is_fetchone=True)
	comment_count = r['count']

	sql = "SELECT count(*) as count FROM collect WHERE weibo_id=%s"
	params = (weibo_id,)
	r = db.execute(sql, params, is_fetchone=True)
	collect_count = r['count']

	
	key = "weibo:rank"
	name = "%s" % weibo_id
	score = get_score(forward_count, comment_count, collect_count)
	redis.zadd(key, score, name)	# not name-score

	# withscores
	# zrem, zincrby