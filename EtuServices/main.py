import redis as red
import sys
import datetime
import time
r=red.Redis(host="192.168.0.22",port=6379)
"""r2=red.from_url('redis://192.168.0.22')
r2.ping"""



"""
if(r.exists(user)==0):
    
    r.set(user,1)
else:

    r.incr(user,1)

print(r.lrange(user))
"""



id = sys.argv[1]



results = r.lrange(f"conn:{id}", 0, 9)
if(results==[]):
    r.lpush(f"conn:{id}", time.time())
else:


    #print(results)
    connection_times = []
    for re in results:
        connection_times.append(datetime.datetime.fromtimestamp(float(re.decode("utf-8"))))

    last_time = connection_times[-1]
    #print(connection_times)
    current_time = datetime.datetime.now()

    check = current_time - last_time
    #print("Last time:", last_time, "now:", current_time, "delta:", check)
    #print(datetime.timedelta(minutes=10))
    if check <= datetime.timedelta(minutes=10) and len(results)==10:
        print("{id} not authorized to connect. Time to 10th connection :", check)
        
    else:
        r.lpush(f"conn:{id}", time.time())
        print("{id} authorized to connect. Time to 10th connection :", check)


