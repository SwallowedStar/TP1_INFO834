import redis as red
import sys
import datetime
import time



r=red.Redis(host="192.168.0.22",port=6379,db=1)

id=sys.argv[1]
type_service=sys.argv[2]

r.lpush(f"id:{id} type:{type_service}", time.time())

results = r.lrange(f"id:{id} type:{type_service}", 0, 9)
print(results)
sys.exit(8)
