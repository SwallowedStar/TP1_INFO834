import redis as red
import sys
print(sys.argv)
r=red.Redis(host="192.168.0.22",port=6379)
"""r2=red.from_url('redis://192.168.0.22')
r2.ping"""

user=sys.argv[0]
if(r.get(user)==None):
    r.set(user,1)
else:
    r.incr(user,1)


r.incr('test',1)

print(r.get('test'))