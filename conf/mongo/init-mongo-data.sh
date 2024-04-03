#!/bin/bash

mongoimport --username mongouser --password mongopass --authenticationDatabase=test --host rock-mongo0 --port 27017 --db test --mode upsert --upsertFields external_id --collection rockhome --type json --file /conf/mongo/init-mongo-data.json --jsonArray