cp -r ..//../app/bc/ ./code/

docker build -t bpt-bc . --no-cache
#docker tag bpt-bc:latest 893041221537.dkr.ecr.eu-central-1.amazonaws.com/bpt-bc:latest
#docker push 893041221537.dkr.ecr.eu-central-1.amazonaws.com/bpt-bc:latest
rm -r code
ecs-cli compose -f composer-bc.yml service up
#ecs-cli compose -f composer-bc.yml service scale 2
