#!/usr/bin/python3

import MySQLdb
import http
from http import client
import json

db = MySQLdb.connect(host="localhost",
                     user="ws_user",
                     passwd="s34qhBvVYWu2",
                     db="ws")
cur = db.cursor()

cur.execute("select * from samples")
numrows = cur.rowcount

if numrows == 0:
    print("No samples to upload")
    
    db.close()
    quit()

highest_id = 0

samples = []

for x in range(0, numrows):
    row = cur.fetchone()
    sample = {
        "time": str(row[1]),
        "air_temp": row[2],
        "ground_temp": row[3],
        "pressure": row[4],
        "humidity": row[5],
        "air_conductivity": row[6],
        "light": row[7]
    }

    highest_id = max(highest_id, row[0])
    samples.append(sample)



json_data = json.dumps(samples)
print(json_data)

headers = {
    "Content-Type": "application/json"
    }

con = http.client.HTTPConnection ("www.stormbrewers.com")
con.request("POST", "/weather/update.php", json_data, headers)

response = con.getresponse()
data = response.read()

if data == b'Ok':
    cur.execute("delete from samples where id <= {0}".format(highest_id))
    db.commit()

    print("Success")

db.close()
con.close()
