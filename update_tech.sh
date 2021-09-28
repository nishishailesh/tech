#!/bin/bash
####if root password
echo 'Give mysql password'
read password

####if root password
mysqldump  -d -uroot tech -p$password > tech_blank.sql
####if unix plugin , as root
####mysqldump  -d cl_general > cl_general_blank.sql

git add *
git commit -a
git push https://github.com/nishishailesh/tech master
