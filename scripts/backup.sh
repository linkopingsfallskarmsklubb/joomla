#!/bin/sh

set -e

mysqldump -u backup --all-databases > /tmp/mysqldump.sql 2> /dev/null

OUT=/tmp/$(date +%Y-%m-%d_%H%M)-lfk.tar.xz
tar -Jcf $OUT /opt/bitnami/apps/joomla/htdocs/ /tmp/mysqldump.sql 2> /dev/null

rm -f /tmp/mysqldump.sql

/usr/local/bin/gsutil cp $OUT gs://lfk/backup-$(hostname)/ 2> /dev/null

rm -f $OUT
