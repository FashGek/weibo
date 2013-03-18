# -*- coding: utf-8 -*-
from mysql import DataBase
import datetime

USER_NAMES = {
	'Fenng': 'Fenng@gmail.com',
	'刘彬': 'liubin@126.com',
	'叫兽易小星': 'jiaoshou@126.com',
	'乐嘉': 'lejia@163.com',
	'新浪NBA': 'nba@sina.com',
	'想去': 'hr@xiangqu.com',
	'windylcx': 'windylcx@126.com'
}

HOST, USER, PASSWD, DB, PORT = ('localhost', 'root', 'zenxds', 'weibo', 3306)
db = DataBase(host=HOST, user=USER, passwd=PASSWD, db=DB, port=PORT)

# for name, email in USER_NAMES.items():
# 	sql = "INSERT INTO user(nikename, email, password, last_login_time, create_time) VALUES (%s, %s, %s, %s, %s)"
# 	params = (name, email, '242bf4a64db2a4007aba66761c8d0bd1', datetime.datetime.now(), datetime.datetime.now())
# 	r = db.execute(sql, params)
# db.commit()

USER_IDS = range(43, 50)

sql = "SELECT * FROM weibo"
r = db.execute(sql)

# for i in USER_IDS:
# 	for weibo in r:
# 		weibo['author'] = i
# 		weibo['type'] = 0
# 		sql = "INSERT INTO weibo(author, content, image_id, video_id, music_id, dateline, type) VALUES (%(author)s, %(content)s, %(image_id)s, %(video_id)s, %(music_id)s, %(dateline)s, %(type)s)"
# 		params = weibo
# 		db.execute(sql, params)

# avatar = "http://127.0.0.1/weibo/themes/zenxds/avatar/2.jpg"
# for i in USER_IDS:
# 	sql = "INSERT INTO profile(user_id, sex, location, avatar_url) VALUES (%s, %s, %s, %s)"
# 	params = (i, 0, '浙江 杭州', avatar)
# 	db.execute(sql, params)

v = "该微博已被原作者删除"
sql = "UPDATE weibo SET type=%s WHERE content=%s"
params = (1, v)
db.execute(sql, params)

db.commit()