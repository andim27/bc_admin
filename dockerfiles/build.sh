cp -r ..//../. ./code/

docker build -t bpt-admin . --no-cache
docker tag bpt-bc:latest 893041221537.dkr.ecr.eu-central-1.amazonaws.com/bpt-admin:latest
docker push 893041221537.dkr.ecr.eu-central-1.amazonaws.com/bpt-admin:latest
rm -r code
ecs-cli compose -f composer-admin.yml service up
#ecs-cli compose -f composer-bc.yml service scale 2
