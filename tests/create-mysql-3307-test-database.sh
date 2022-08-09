#!/bin/bash

echo "DROP DATABASE IF EXISTS test;CREATE DATABASE test;" | mysql -h127.0.0.1 -P3307 -uroot -p123456
