#!/usr/bin/env bash

HOSTNAME="hulk.cosprings.teknofile.net:8080"
API_KEY="25703c8c-f8ea-41fc-9b6f-7d8b99157f4e"

curl -X GET "http://${HOSTNAME}/api/v1/recognition/subjects/" \
	-H "Content-Type: application/json" \
	-H "x-api-key: ${API_KEY}"
