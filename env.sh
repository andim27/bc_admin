#!/bin/bash
red=$'\e[1;31m'
grn=$'\e[1;32m'
yel=$'\e[1;33m'
blu=$'\e[1;34m'
mag=$'\e[1;35m'
cyn=$'\e[1;36m'
end=$'\e[0m'
cp ./config/params.prod ./config/params.php
cp ./config/web.prod ./config/web.php
sed -i "s/__API_URL__/${API_URL}/" ./config/params.php
sed -i "s|__MONGO_URI__|${MONGO_URI}|" ./config/web.php
# Print the value
printf "%-30s %-30s\n" "${grn}API URL:${end}" "${cyn}${API_URL}${end}"
printf "%-30s %-30s\n" "${grn}MONGO URI:${end}" "${cyn}${MONGO_URI}${end}"