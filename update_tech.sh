#!/bin/bash
####if root password
echo 'Give mysql username'
read username

####if root password
mysqldump  -d -u$username tech -p > tech_blank.sql

git add *
git commit -a
git push https://github.com/nishishailesh/tech
