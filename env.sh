#!/bin/bash
red=$'\e[1;31m'
grn=$'\e[1;32m'
yel=$'\e[1;33m'
blu=$'\e[1;34m'
mag=$'\e[1;35m'
cyn=$'\e[1;36m'
end=$'\e[0m'

# If set
printf "%-30s %-30s\n" "${grn}ENVIRONMENT:" "${cyn}${ENVIRONMENT}"
if [ "$ENVIRONMENT"  == "development" ]; then

	cp ./config/params.dev ./config/params.php
	cp ./config/web.dev ./config/web.php

fi

if [ "$ENVIRONMENT"  == "stage" ]; then

	cp ./config/params.prod ./config/params.php
	cp ./config/web.prod ./config/web.php

fi

if [ "$ENVIRONMENT"  == "production" ]; then

	cp ./config/params.prod ./config/params.php
	cp ./config/web.prod ./config/web.php

fi

API_URL="$(cat ./config/params.php | grep 'http://' | sed -e "s/=>//g")"
MONGO_URI="$(cat ./config/web.php | grep 'mongodb://' | sed -e "s/dsn = >//g")"
printf "%-30s %-30s\n" "${grn}API URL:${end}" "${cyn}${API_URL}${end}"
printf "%-30s %-30s\n" "${grn}MONGO URI:${end}" "${cyn}${MONGO_URI}${end}"