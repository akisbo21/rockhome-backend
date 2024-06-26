#!/bin/bash
echo "sleeping for 2 seconds"
sleep 2

mongoimport --username mongouser --password mongopass --authenticationDatabase=test --host rock-mongo0 --port 27017 --db test --mode upsert --upsertFields external_id --collection rockhome --type json --file /conf/mongo/init-mongo-data.json --jsonArray