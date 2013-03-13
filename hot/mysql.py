#!/usr/bin/env python
# -*- coding: utf-8 -*-
import MySQLdb
CHARSET = 'utf8'

class DataBase(object):
	"""docstring for db"""
	def __init__(self, host, user, passwd, db, port=3306):
		self.host = host
		self.user = user
		self.passwd = passwd
		self.db = db
		self.port = port

		self.connect_db()

	def connect_db(self):
		try:
			self.conn = MySQLdb.connect(host=self.host, port=self.port, user=self.user, passwd=self.passwd, db=self.db, charset=CHARSET)
			self.cursor = self.conn.cursor(MySQLdb.cursors.DictCursor)
		except Exception, e:
			print "Can't connect to database!"

	def execute(self, sql, param=(), is_many=False, is_fetchone=False):
		try:
			if is_many:
				self.cursor.executemany(sql, param)
			else:
				self.cursor.execute(sql, param)

		except Exception, e:
			print 'Error in execute sql: --%s-- with param --%s-- !' % (sql, param)
			print e
			self.rollback()
			return 1 		# error code

		else: 
			# print 'Execute %s successfully!' % sql
			if is_fetchone:
				return self.fetchone()
			else:
				return self.fetchall()
	def get_cursor(self):
		self.cursor = self.conn.cursor(MySQLdb.cursors.DictCursor)
		return self.cursor
	def close_cursor(self):
		try:
			self.cursor.close()
		except Exception, e:
			print 'Close Cursor Exception!'
	def commit(self):
		self.conn.commit()

	def rollback(self):
		self.conn.rollback()

	def get_last_id(self):
		return self.cursor.lastrowid

	def fetchone(self):
		return self.cursor.fetchone()

	def fetchall(self):
		return self.cursor.fetchall()

	def fetchmany(self, size=0):
		return self.cursor.fetchmany(size)


	def close(self):
		try:
			self.cursor.close()
			self.conn.close()
		except Exception, e:
			print 'Close Exception!'
		

HOST, USER, PASSWD, DB, PORT = ('localhost', 'root', 'zenxds', 'satyr', 3306)
db = DataBase(host=HOST, user=USER, passwd=PASSWD, db=DB, port=PORT)

# import datetime
# if __name__ == '__main__':
# 	n = 1
# 	sql = "insert into temp_data(type, value, modified_datetime) values(%s, %s, %s)"
# 	param = (n, 2, datetime.datetime.now())
# 	db.execute(sql, param)
# 	db.commit()
# 	n += 1	
# 	param = (n, 2, datetime.datetime.now())
# 	db.execute(sql, param)
# 	db.commit()	
# 	n += 1
# 	param = (n, 2, datetime.datetime.now())
# 	db.execute(sql, param)
# 	db.commit()	
