import redis as red
import sys
import datetime
import time



r=red.Redis(host="192.168.0.22",port=6379,db=0)


tab_res=[]
for x in r.keys("conn:*"):

    tab_res.append([x,r.llen(x)])

tab_res.sort(key=lambda x: x[1], reverse=True)
tab_res2=[]

if(len(tab_res)<10):

    for x in range(len(tab_res)):
        tab_res2.append([tab_res[x][0],tab_res[x][1]])
        
else:
    for x in range(10):
        tab_res2.append([tab_res[x][0],tab_res[x][1]])
        
        #print(len(r.lrange(f"conn:"+x+"",0,9)))

print(tab_res2)