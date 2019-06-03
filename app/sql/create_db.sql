
# ※以下temp。
# mysqldump -Q --opt -u learningportal_db_user -plearningportal_db_pass -h localhost learningportal_db_db > utusu.sql
# mysqldump -Q -q --opt -u learningportal_db_user -plearningportal_db_pass -h localhost learningportal_db_db > utusu.sql
# mysql --default-character-set=UTF8 -f -u learningportal_db_user -plearningportal_db_pass learningportal_db_db < work_server_backup.sql

######################################################################
## システム
## MySQL SQL
## CharcterCode set UTF8 !
## Ver 1.4 2004-02-07 A.Taguchi (Tryit.)
######################################################################

#### ＤＢ作成 ######################################################
#開発サーバrootmysql(t4u.bz)
# mysql -u root -ptr_myroot_tiy mysql
#
# DB_HOST : localhost
# DB_NAME : learningportal_db
# DB_USER : learningportal_user
# DB_PASS : learningportal_pass
# MySQL
#
DROP DATABASE learningportal_db;
CREATE DATABASE learningportal_db DEFAULT CHARACTER SET UTF8;
GRANT ALL ON *.* TO learningportal_user@"localhost" IDENTIFIED BY "learningportal_pass";
GRANT ALL PRIVILEGES ON learningportal_db.* TO learningportal_user IDENTIFIED BY 'learningportal_pass';
FLUSH PRIVILEGES;

######################################################################
## mysql へのアクセス
# 開発 cd /var/www/domain-learningportal_db.t4u.bz/sql
# $ mysql -u learningportal_user -plearningportal_pass learningportal_db --default-character-set=UTF8

######################################################################
## mysql バックアップ
# ※開発環境
# $ mysqldump --default-character-set=UTF8 --add-drop-table -Q -q -u learningportal_user -plearningportal_pass -h localhost learningportal_db > learningportal_db.sql
# スキーマのみ
# $ mysqldump -u learningportal_user -plearningportal_pass -d learningportal_db > learningportal_db.sql
#
# ※OLD
# $ mysqldump --add-drop-table -q -u learningportal_user -plearningportal_pass -h localhost learningportal_db > learningportal_db.sql
# $ mysqldump --add-drop-table -q -u learningportal_user -plearningportal_pass -h 192.168.0.11 learningportal_db > learningportal_db.sql
# $ zip learningportal_db.zip learningportal_db.sql
mysqldump --default-character-set=UTF8 --add-drop-table -Q -q -u learningportal_user -plearningportal_pass -h localhost learningportal_db > learningportal_db_2012_1112.sql
zip learningportal_db_2012_0625.zip learningportal_db_2012_0625.sql

######################################################################
## mysql リストア
# $ unzip learningportal_db.zip
# ※開発環境
# $ mysql --default-character-set=UTF8 -f -u learningportal_user -plearningportal_pass -h localhost learningportal_db < learningportal_db_20161121.sql
# ※本サーバ：テストDB
# $ mysql --default-character-set=UTF8 -f -u learningportal_user -plearningportal_pass -h localhost learningportal_dbtest < learningportal_dbtest.sql
# ※本サーバ：実働DB
# $ mysql --default-character-set=UTF8 -f -u learningportal_user -plearningportal_pass -h localhost learningportal_db < learningportal_db.sql
#
# ※OLD
# $ unzip learningportal_db.zip
# $ mysql --default-character-set=UTF8 -u learningportal_user -plearningportal_pass learningportal_db < learningportal_db.sql

