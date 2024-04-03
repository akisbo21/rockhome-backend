#!/bin/bash
echo "sleeping for 2 seconds"
sleep 2

echo mongo_setup.sh time now: `date + "%T" `
mongosh --host rock-mongo0:27017 <<EOF
var cfg = {
	"_id": "rs0",
	"version": 1,
	"members": [
		{
			"_id": 0,
			"host": "rock-mongo0:27017",
			"priority": 2
		},
		{
			"_id": 1,
			"host": "rock-mongo1:27017",
			"priority": 0
		},
		{
			"_id": 2,
			"host": "rock-mongo2:27017",
			"priority": 0
		}
	]
};
rs.initiate(cfg);

db.createUser(
	{
		user: "mongouser",
		pwd: "mongopass",
		roles: [
			{
				role: "readWrite",
				db: "rockhome"
			}
		]
	}
);

db.createCollection("rockhome");

EOF


/conf/mongo/init-mongo-data.sh
