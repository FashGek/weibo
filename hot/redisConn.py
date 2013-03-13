# -*- coding:utf-8

import redis

# print redis.__file__

# Config
HOST = "127.0.0.1"
PORT = 6379
DB = 2

r = redis.StrictRedis(host=HOST, port=PORT, db=DB)

# info = r.info()
# for key in info:
# 	print key, '--', info[key]

# append(key, value)
# exists(name)
# get(name)
# set()

# bgsave()	save its data to disk
# blpop, brpop(keys, timeout=0) 返回list的第一个/最后一个值,如果没取到，阻塞xx秒





# r.append('test', 'testvalue')
# r.get('test')
# print r.dbsize()