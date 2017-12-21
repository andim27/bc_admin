cp ../../supervisor/supervisord.conf .
cp -r ../../supervisor/conf.d/laravel_worker.conf .
cp ../../settings/php/php.ini .
cp -r ..//../app/api/ ./code/

docker build -t bpt-worker . --no-cache
#docker tag bpt-worker:latest 893041221537.dkr.ecr.eu-central-1.amazonaws.com/bpt-worker:latest
#docker push 893041221537.dkr.ecr.eu-central-1.amazonaws.com/bpt-worker:latest
rm php.ini
rm laravel_worker.conf
rm supervisord.conf
rm -r code
ecs-cli compose -f compose-worker.yml service up 
ecs-cli compose -f compose-worker.yml service scale 4
