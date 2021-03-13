#!/bin/sh

echo "============================================="
echo "creating mysql dump of database ${DB_NAME}"

# shellcheck disable=SC2034
export MYSQL_PWD=${DB_PASSWORD}
mysqldump -u ${DB_USER} -h ${DB_HOST} ${DB_NAME} --opt --single-transaction > /backups/dump.sql

echo "dump creation finished"
echo "============================================="
