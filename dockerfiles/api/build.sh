cp ../../supervisor/supervisord.conf .
cp ../../supervisor/conf.d/nginx.conf .
cp ../../supervisor/conf.d/php.conf .
cp ../../settings/php/php.ini .
cp -r ..//../app/api/ ./code/
cp ../../settings/nginx/api.conf .

docker build -t bpt-api . --no-cache
docker tag bpt-api:latest 893041221537.dkr.ecr.eu-central-1.amazonaws.com/bpt-api:latest
#docker push 893041221537.dkr.ecr.eu-central-1.amazonaws.com/bpt-api:latest
rm php.ini
rm nginx.conf
rm php.conf
rm supervisord.conf
rm -r code
rm api.conf
#ecs-cli compose -f composer-api.yml service up
#ecs-cli compose -f composer-api.yml service scale 2
