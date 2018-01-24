git checkout admin-dev
git pull origin admini-dev
git checkout admin
mv dockerfiles/ /tmp/
mv config/params.php /tmp
mv config/console.php /tmp
git merge admin-dev
rm -r dockerfiles
rm config/params.php
rm config/console.php
mv /tmp/dockerfiles/ .
mv /tmp/params.php ./config/
mv /tmp/console.php ./config/