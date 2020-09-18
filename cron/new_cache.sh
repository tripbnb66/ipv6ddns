#!/bin/sh
cd ../
for d in cache api/cache admin/cache admin/api/cache api/templates admin/templates admin/api/templates; do
	mkdir $d
done

for d in cache api/cache admin/cache admin/api/cache; do
	mkdir $d
    sudo chown apache:apache $d
done

find ./ -name cache -type d -exec ls -ld {} \;
find ./ -name templates -type d -exec ls -ld {} \;

