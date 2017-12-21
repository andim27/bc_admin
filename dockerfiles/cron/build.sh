cp ../../supervisor/supervisord.conf .
cp -r ../../settings/cron/ .
cp ../../settings/php/php.ini .
cp -r ..//../app/api/ ./code/

docker build -t bpt-cron . --no-cache
#docker tag bpt-cron:latest 893041221537.dkr.ecr.eu-central-1.amazonaws.com/bpt-cron:latest
#docker push 893041221537.dkr.ecr.eu-central-1.amazonaws.com/bpt-cron:latest
#rm supervisord.conf
rm -r cron
rm php.ini
rm -r code

ecs-cli compose -f composer-cron.yml service up
