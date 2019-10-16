#!/bin/bash
mysqldump -d -h localhost --no_data -u root -p _aurora | sed 's/ AUTO_INCREMENT=[0-9]*//g' |  sed 's/varchar(256)/varchar(255)/g'  > app.sql
