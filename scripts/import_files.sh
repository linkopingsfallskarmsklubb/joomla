#!/bin/sh

htdocs="/opt/bitnami/apps/joomla/htdocs/"
IFS='
'
for i in ${2?}/*
do
  filename=$(basename $i)
  friendly=$(basename $i)
  path="images/simplefilemanager/old/${3?}/$friendly"

  size=$(stat -c %s $i)
  created=$(date +%Y-%m-%d)

  echo "insert into jos_simplefilemanager (title, catid, state, description, file_created, file_name, file_size, author)  values ('$filename', ${1?}, 1, '', '$created', '$path', $size, 43);"

  mkdir -p "${htdocs}$(dirname $path)"
  cp $i "${htdocs}${path}"
done
