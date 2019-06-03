######################################################################
## テーブル定義
## MySQL SQL
## CharcterCode set UTF8 !
## Ver 1.4 2017-05-08 A.Taguchi (Tryit.)
######################################################################
#
# 開発サーバ(t4u.bz)
# mysql -u nsp_user -pnsp_pass nsp_db --default-character-set=UTF8
# http://nissan.t4u.bz/phpmyadmin/
# 

# メンバーセッション（受講者単位）
DROP TABLE IF EXISTS M_SESSION;
CREATE TABLE M_SESSION (
  SEQ_NO                            int            NOT NULL AUTO_INCREMENT   COMMENT '連番',
  MEMBER_ID                         int            DEFAULT 0                 COMMENT 'ユーザーID',
  SVAL                              text           DEFAULT NULL              COMMENT 'シリアライズされた値',
  INS_DT                            datetime       DEFAULT NULL              COMMENT 'レコード新規日時',
  UPD_DT                            datetime       DEFAULT NULL              COMMENT 'レコード更新日時',
  DEL_FLG                           tinyint        DEFAULT '0'               COMMENT '0=有効 / 1=削除',
  PRIMARY KEY (SEQ_NO),
  KEY idx1 (MEMBER_ID)
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT = 'M_SESSION';

# 共通
DROP TABLE IF EXISTS M_CONFIG;
CREATE TABLE M_CONFIG (
  SEQ_NO                            int            NOT NULL AUTO_INCREMENT   COMMENT '連番',
  CKEY                              varchar(255)   DEFAULT NULL              COMMENT '参照キー',
  CVAL                              text           DEFAULT NULL              COMMENT '値',
  CNOTE                             text           DEFAULT NULL              COMMENT 'メモ等',
  INS_DT                            datetime       DEFAULT NULL              COMMENT 'レコード新規日時',
  UPD_DT                            datetime       DEFAULT NULL              COMMENT 'レコード更新日時',
  DEL_FLG                           tinyint        DEFAULT '0'               COMMENT '0=有効 / 1=削除',
  PRIMARY KEY (SEQ_NO),
  KEY idx1 (CKEY)
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT = 'M_CONFIG';
INSERT INTO `m_config` (`SEQ_NO`, `CKEY`, `CVAL`, `CNOTE`, `INS_DT`, `UPD_DT`, `DEL_FLG`) VALUES 
(NULL, '研修名', '勉強会2019', '研修名の設定', '2018-09-05 00:00:00', '2018-09-10 13:55:18', 0),
(NULL, '管理メモ', '勉強会メモ2019', '管理メモ', '2018-09-05 00:00:00', '2018-09-10 13:52:08', 0),
(NULL, '受講者登録_最終更新日時', '2019-04-01 16:27:17', NULL, '2018-11-07 19:12:35', '2019-04-01 16:27:17', 0),
(NULL, '勉強会QAカテゴリー', '{"\\uff74\\uff9d\\uff7c\\uff9e\\uff9d\\u30fb\\uff84\\uff97\\uff9d\\uff7d\\uff90\\uff6f\\uff7c\\uff6e\\uff9d":"\\uff74\\uff9d\\uff7c\\uff9e\\uff9d\\u30fb\\uff84\\uff97\\uff9d\\uff7d\\uff90\\uff6f\\uff7c\\uff6e\\uff9d","\\u5148\\u9032\\u5b89\\u5168\\u88c5\\u5099":"\\u5148\\u9032\\u5b89\\u5168\\u88c5\\u5099","\\u5bd2\\u51b7\\u5730":"\\u5bd2\\u51b7\\u5730","\\u305d\\u306e\\u4ed6":"\\u305d\\u306e\\u4ed6","\\u305d\\u306e\\u4ed6\\u88c5\\u5099":"\\u305d\\u306e\\u4ed6\\u88c5\\u5099","\\u30d0\\u30c3\\u30c6\\u30ea\\u30fc":"\\u30d0\\u30c3\\u30c6\\u30ea\\u30fc","AJ":"AJ","D-OPT":"D-OPT","":""}', NULL, '2018-11-08 19:03:12', '2018-11-14 08:02:22', 0),
(NULL, '勉強会QA-登録_最終更新日時', '2018-11-14 08:02:22', NULL, '2018-11-08 19:03:12', '2018-11-14 08:02:22', 0),
(NULL, '勉強会QA-出力_最終更新日時', '2019-01-29 13:59:14', NULL, '2018-11-14 16:54:18', '2019-01-29 13:59:14', 0),
(NULL, 'データマージ-登録_最終更新日時', '2018-11-22 20:50:13', NULL, '2018-11-22 20:50:13', '2018-11-22 20:50:13', 0);


# 会場
DROP TABLE IF EXISTS M_SITE;
CREATE TABLE M_SITE (
  SITE_ID                           int            NOT NULL AUTO_INCREMENT   COMMENT '会場ID',
  SITE_NO                           int            DEFAULT 0                 COMMENT '会場番号',
  SITE_NAME                         varchar(50)    DEFAULT ''                COMMENT 'サイト名',
  AREA_NAME                         varchar(50)    DEFAULT ''                COMMENT 'エリア名',
  LECTURE_NAME                      varchar(255)   DEFAULT NULL              COMMENT '研修名',
  INS_DT                            datetime       DEFAULT NULL              COMMENT 'レコード新規日時',
  UPD_DT                            datetime       DEFAULT NULL              COMMENT 'レコード更新日時',
  DEL_FLG                           tinyint        DEFAULT '0'               COMMENT '0=有効 / 1=削除',
  PRIMARY KEY (SITE_ID),
  KEY idx1 (LECTURE_NAME,SITE_NAME)
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT = 'M_会場';

INSERT INTO m_site VALUES (NULL, 1, '追浜グランドライブ',    '首都圏エリア（GRANDRIVE）',                  '勉強会2019', NULL, NULL, '0');
INSERT INTO m_site VALUES (NULL, 2, '十勝スピードウェイ',    '北海道エリア（十勝スピードウェイ）',         '勉強会2019', NULL, NULL, '0');
INSERT INTO m_site VALUES (NULL, 3, 'セントラルサーキット',  '近畿・中四国エリア（セントラルサーキット）', '勉強会2019', NULL, NULL, '0');
INSERT INTO m_site VALUES (NULL, 4, 'スパ西浦',              '中部・東海エリア（スパ西浦）',               '勉強会2019', NULL, NULL, '0');
INSERT INTO m_site VALUES (NULL, 5, 'スパ直入',              '九州エリア（スパ直入）',                     '勉強会2019', NULL, NULL, '0');
INSERT INTO m_site VALUES (NULL, 6, '群馬サイクルセンター',  '関東エリア（群馬サイクルセンター）',         '勉強会2019', NULL, NULL, '0');
INSERT INTO m_site VALUES (NULL, 7, 'スポーツランドＳＵＧＯ','東北エリア（スポーツランドSUGO）',           '勉強会2019', NULL, NULL, '0');

# 受講者
DROP TABLE IF EXISTS M_MEMBER;
CREATE TABLE M_MEMBER (
  MEMBER_ID                         int            NOT NULL AUTO_INCREMENT   COMMENT 'ユーザーID',
  SEAT_CD                           varchar(10)    DEFAULT NULL              COMMENT '座席番号',
  TERM_CD                           varchar(32)    DEFAULT NULL              COMMENT '端末番号',
  GROUP_CD                          varchar(10)    DEFAULT NULL              COMMENT 'グループCD',
  LECTURE_DT                        date           DEFAULT NULL              COMMENT '受講日付',
  LECTURE_TYPE                      tinyint        DEFAULT NULL              COMMENT '前半・後半',
  COMPANY_CD                        varchar(50)    DEFAULT NULL              COMMENT '社員No',
  SITE_NAME                         varchar(50)    DEFAULT NULL              COMMENT '会場名',
  MEMBER_NAME                       varchar(50)    DEFAULT NULL              COMMENT '利用者名',
  COMPANY_NAME                      varchar(100)   DEFAULT NULL              COMMENT '法人名',
  PLACE_NAME                        varchar(100)   DEFAULT NULL              COMMENT '拠点名',
  DIV_NAME                          varchar(50)    DEFAULT NULL              COMMENT '部署名',
  POST_NAME                         varchar(50)    DEFAULT NULL              COMMENT '役職',
  LECTURE_NAME                      varchar(255)   DEFAULT NULL              COMMENT '研修名',
  INS_DT                            datetime       DEFAULT NULL              COMMENT 'レコード新規日時',
  UPD_DT                            datetime       DEFAULT NULL              COMMENT 'レコード更新日時',
  DEL_FLG                           tinyint        DEFAULT '0'               COMMENT '0=有効 / 1=削除',
  PRIMARY KEY (MEMBER_ID),
  KEY idx1 (LECTURE_NAME,SITE_NAME,LECTURE_DT,LECTURE_TYPE,SEAT_CD),
  KEY idx2 (LECTURE_NAME,SITE_NAME,LECTURE_DT,LECTURE_TYPE,TERM_CD),
  KEY idx3 (MEMBER_NAME)
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT = 'M_受講者';
INSERT INTO `m_member` (`MEMBER_ID`, `SEAT_CD`,`TERM_CD`,`GROUP_CD`, `LECTURE_DT`, `LECTURE_TYPE`, `COMPANY_CD`, `SITE_NAME`, `MEMBER_NAME`, `COMPANY_NAME`, `PLACE_NAME`, `DIV_NAME`, `POST_NAME`, LECTURE_NAME, `INS_DT`, `UPD_DT`, `DEL_FLG`) VALUES
(NULL, '#01', 'XXXXX', '#', '0000-00-00', 1, NULL, NULL, '講師-前半(#01)', '日産自動車（株）', NULL, NULL, NULL, NULL, '2018-11-13 16:52:33', '2019-04-01 16:27:16', 0),
(NULL, '#02', 'XXXXX', '#', '0000-00-00', 1, NULL, NULL, '講師-前半(#02)', '日産自動車（株）', NULL, NULL, NULL, NULL, '2018-11-13 16:52:33', '2019-04-01 16:27:16', 0),
(NULL, '#03', 'XXXXX', '#', '0000-00-00', 2, NULL, NULL, '講師-後半(#03)', '日産自動車（株）', NULL, NULL, NULL, NULL, '2018-11-13 16:52:33', '2019-04-01 16:27:16', 0),
(NULL, '#04', 'XXXXX', '#', '0000-00-00', 2, NULL, NULL, '講師-後半(#04)', '日産自動車（株）', NULL, NULL, NULL, NULL, '2018-11-13 16:52:33', '2019-04-01 16:27:16', 0),
(NULL, 'A01', 'XXXXX', 'A', '2019-07-12', 1, NULL, '追浜グランドライブ', 'テスト(A01)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'A02', 'XXXXX', 'A', '2019-07-12', 1, NULL, '追浜グランドライブ', 'テスト(A02)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'A03', 'XXXXX', 'A', '2019-07-12', 1, NULL, '追浜グランドライブ', 'テスト(A03)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'A04', 'XXXXX', 'A', '2019-07-12', 1, NULL, '追浜グランドライブ', 'テスト(A04)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'A05', 'XXXXX', 'A', '2019-07-12', 1, NULL, '追浜グランドライブ', 'テスト(A05)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'A06', 'XXXXX', 'A', '2019-07-12', 1, NULL, '追浜グランドライブ', 'テスト(A06)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'A07', 'XXXXX', 'A', '2019-07-12', 1, NULL, '追浜グランドライブ', 'テスト(A07)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'A08', 'XXXXX', 'A', '2019-07-12', 1, NULL, '追浜グランドライブ', 'テスト(A08)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'A09', 'XXXXX', 'A', '2019-07-12', 1, NULL, '追浜グランドライブ', 'テスト(A09)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B01', 'XXXXX', 'B', '2019-07-12', 1, NULL, '追浜グランドライブ', 'テスト(B01)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B02', 'XXXXX', 'B', '2019-07-12', 1, NULL, '追浜グランドライブ', 'テスト(B02)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B03', 'XXXXX', 'B', '2019-07-12', 1, NULL, '追浜グランドライブ', 'テスト(B03)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B04', 'XXXXX', 'B', '2019-07-12', 1, NULL, '追浜グランドライブ', 'テスト(B04)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B05', 'XXXXX', 'B', '2019-07-12', 1, NULL, '追浜グランドライブ', 'テスト(B05)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B06', 'XXXXX', 'B', '2019-07-12', 1, NULL, '追浜グランドライブ', 'テスト(B06)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B07', 'XXXXX', 'B', '2019-07-12', 1, NULL, '追浜グランドライブ', 'テスト(B07)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B08', 'XXXXX', 'B', '2019-07-12', 1, NULL, '追浜グランドライブ', 'テスト(B08)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B09', 'XXXXX', 'B', '2019-07-12', 1, NULL, '追浜グランドライブ', 'テスト(B09)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C01', 'XXXXX', 'C', '2019-07-12', 2, NULL, '追浜グランドライブ', 'テスト(C01)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C02', 'XXXXX', 'C', '2019-07-12', 2, NULL, '追浜グランドライブ', 'テスト(C02)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C03', 'XXXXX', 'C', '2019-07-12', 2, NULL, '追浜グランドライブ', 'テスト(C03)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C04', 'XXXXX', 'C', '2019-07-12', 2, NULL, '追浜グランドライブ', 'テスト(C04)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C05', 'XXXXX', 'C', '2019-07-12', 2, NULL, '追浜グランドライブ', 'テスト(C05)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C06', 'XXXXX', 'C', '2019-07-12', 2, NULL, '追浜グランドライブ', 'テスト(C06)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C07', 'XXXXX', 'C', '2019-07-12', 2, NULL, '追浜グランドライブ', 'テスト(C07)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C08', 'XXXXX', 'C', '2019-07-12', 2, NULL, '追浜グランドライブ', 'テスト(C08)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C09', 'XXXXX', 'C', '2019-07-12', 2, NULL, '追浜グランドライブ', 'テスト(C09)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D01', 'XXXXX', 'D', '2019-07-12', 2, NULL, '追浜グランドライブ', 'テスト(D01)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D02', 'XXXXX', 'D', '2019-07-12', 2, NULL, '追浜グランドライブ', 'テスト(D02)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D03', 'XXXXX', 'D', '2019-07-12', 2, NULL, '追浜グランドライブ', 'テスト(D03)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D04', 'XXXXX', 'D', '2019-07-12', 2, NULL, '追浜グランドライブ', 'テスト(D04)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D05', 'XXXXX', 'D', '2019-07-12', 2, NULL, '追浜グランドライブ', 'テスト(D05)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D06', 'XXXXX', 'D', '2019-07-12', 2, NULL, '追浜グランドライブ', 'テスト(D06)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D07', 'XXXXX', 'D', '2019-07-12', 2, NULL, '追浜グランドライブ', 'テスト(D07)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D08', 'XXXXX', 'D', '2019-07-12', 2, NULL, '追浜グランドライブ', 'テスト(D08)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D09', 'XXXXX', 'D', '2019-07-12', 2, NULL, '追浜グランドライブ', 'テスト(D09)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-12 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'A01', 'XXXXX', 'A', '2019-07-13', 1, NULL, '追浜グランドライブ', 'テスト(A01)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'A02', 'XXXXX', 'A', '2019-07-13', 1, NULL, '追浜グランドライブ', 'テスト(A02)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'A03', 'XXXXX', 'A', '2019-07-13', 1, NULL, '追浜グランドライブ', 'テスト(A03)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'A04', 'XXXXX', 'A', '2019-07-13', 1, NULL, '追浜グランドライブ', 'テスト(A04)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'A05', 'XXXXX', 'A', '2019-07-13', 1, NULL, '追浜グランドライブ', 'テスト(A05)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'A06', 'XXXXX', 'A', '2019-07-13', 1, NULL, '追浜グランドライブ', 'テスト(A06)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'A07', 'XXXXX', 'A', '2019-07-13', 1, NULL, '追浜グランドライブ', 'テスト(A07)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'A08', 'XXXXX', 'A', '2019-07-13', 1, NULL, '追浜グランドライブ', 'テスト(A08)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'A09', 'XXXXX', 'A', '2019-07-13', 1, NULL, '追浜グランドライブ', 'テスト(A09)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B01', 'XXXXX', 'B', '2019-07-13', 1, NULL, '追浜グランドライブ', 'テスト(B01)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B02', 'XXXXX', 'B', '2019-07-13', 1, NULL, '追浜グランドライブ', 'テスト(B02)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B03', 'XXXXX', 'B', '2019-07-13', 1, NULL, '追浜グランドライブ', 'テスト(B03)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B04', 'XXXXX', 'B', '2019-07-13', 1, NULL, '追浜グランドライブ', 'テスト(B04)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B05', 'XXXXX', 'B', '2019-07-13', 1, NULL, '追浜グランドライブ', 'テスト(B05)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B06', 'XXXXX', 'B', '2019-07-13', 1, NULL, '追浜グランドライブ', 'テスト(B06)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B07', 'XXXXX', 'B', '2019-07-13', 1, NULL, '追浜グランドライブ', 'テスト(B07)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B08', 'XXXXX', 'B', '2019-07-13', 1, NULL, '追浜グランドライブ', 'テスト(B08)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B09', 'XXXXX', 'B', '2019-07-13', 1, NULL, '追浜グランドライブ', 'テスト(B09)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C01', 'XXXXX', 'C', '2019-07-13', 2, NULL, '追浜グランドライブ', 'テスト(C01)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C02', 'XXXXX', 'C', '2019-07-13', 2, NULL, '追浜グランドライブ', 'テスト(C02)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C03', 'XXXXX', 'C', '2019-07-13', 2, NULL, '追浜グランドライブ', 'テスト(C03)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C04', 'XXXXX', 'C', '2019-07-13', 2, NULL, '追浜グランドライブ', 'テスト(C04)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C05', 'XXXXX', 'C', '2019-07-13', 2, NULL, '追浜グランドライブ', 'テスト(C05)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C06', 'XXXXX', 'C', '2019-07-13', 2, NULL, '追浜グランドライブ', 'テスト(C06)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C07', 'XXXXX', 'C', '2019-07-13', 2, NULL, '追浜グランドライブ', 'テスト(C07)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C08', 'XXXXX', 'C', '2019-07-13', 2, NULL, '追浜グランドライブ', 'テスト(C08)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C09', 'XXXXX', 'C', '2019-07-13', 2, NULL, '追浜グランドライブ', 'テスト(C09)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D01', 'XXXXX', 'D', '2019-07-13', 2, NULL, '追浜グランドライブ', 'テスト(D01)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D02', 'XXXXX', 'D', '2019-07-13', 2, NULL, '追浜グランドライブ', 'テスト(D02)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D03', 'XXXXX', 'D', '2019-07-13', 2, NULL, '追浜グランドライブ', 'テスト(D03)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D04', 'XXXXX', 'D', '2019-07-13', 2, NULL, '追浜グランドライブ', 'テスト(D04)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D05', 'XXXXX', 'D', '2019-07-13', 2, NULL, '追浜グランドライブ', 'テスト(D05)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D06', 'XXXXX', 'D', '2019-07-13', 2, NULL, '追浜グランドライブ', 'テスト(D06)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D07', 'XXXXX', 'D', '2019-07-13', 2, NULL, '追浜グランドライブ', 'テスト(D07)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D08', 'XXXXX', 'D', '2019-07-13', 2, NULL, '追浜グランドライブ', 'テスト(D08)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D09', 'XXXXX', 'D', '2019-07-13', 2, NULL, '追浜グランドライブ', 'テスト(D09)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'A01', 'XXXXX', 'A', '2019-07-13', 1, NULL, '十勝スピードウェイ', 'テスト(A01)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'A02', 'XXXXX', 'A', '2019-07-13', 1, NULL, '十勝スピードウェイ', 'テスト(A02)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'A03', 'XXXXX', 'A', '2019-07-13', 1, NULL, '十勝スピードウェイ', 'テスト(A03)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'A04', 'XXXXX', 'A', '2019-07-13', 1, NULL, '十勝スピードウェイ', 'テスト(A04)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'A05', 'XXXXX', 'A', '2019-07-13', 1, NULL, '十勝スピードウェイ', 'テスト(A05)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'A06', 'XXXXX', 'A', '2019-07-13', 1, NULL, '十勝スピードウェイ', 'テスト(A06)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'A07', 'XXXXX', 'A', '2019-07-13', 1, NULL, '十勝スピードウェイ', 'テスト(A07)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'A08', 'XXXXX', 'A', '2019-07-13', 1, NULL, '十勝スピードウェイ', 'テスト(A08)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'A09', 'XXXXX', 'A', '2019-07-13', 1, NULL, '十勝スピードウェイ', 'テスト(A09)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B01', 'XXXXX', 'B', '2019-07-13', 1, NULL, '十勝スピードウェイ', 'テスト(B01)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B02', 'XXXXX', 'B', '2019-07-13', 1, NULL, '十勝スピードウェイ', 'テスト(B02)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B03', 'XXXXX', 'B', '2019-07-13', 1, NULL, '十勝スピードウェイ', 'テスト(B03)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B04', 'XXXXX', 'B', '2019-07-13', 1, NULL, '十勝スピードウェイ', 'テスト(B04)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B05', 'XXXXX', 'B', '2019-07-13', 1, NULL, '十勝スピードウェイ', 'テスト(B05)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B06', 'XXXXX', 'B', '2019-07-13', 1, NULL, '十勝スピードウェイ', 'テスト(B06)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B07', 'XXXXX', 'B', '2019-07-13', 1, NULL, '十勝スピードウェイ', 'テスト(B07)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B08', 'XXXXX', 'B', '2019-07-13', 1, NULL, '十勝スピードウェイ', 'テスト(B08)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'B09', 'XXXXX', 'B', '2019-07-13', 1, NULL, '十勝スピードウェイ', 'テスト(B09)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C01', 'XXXXX', 'C', '2019-07-13', 2, NULL, '十勝スピードウェイ', 'テスト(C01)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C02', 'XXXXX', 'C', '2019-07-13', 2, NULL, '十勝スピードウェイ', 'テスト(C02)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C03', 'XXXXX', 'C', '2019-07-13', 2, NULL, '十勝スピードウェイ', 'テスト(C03)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C04', 'XXXXX', 'C', '2019-07-13', 2, NULL, '十勝スピードウェイ', 'テスト(C04)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C05', 'XXXXX', 'C', '2019-07-13', 2, NULL, '十勝スピードウェイ', 'テスト(C05)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C06', 'XXXXX', 'C', '2019-07-13', 2, NULL, '十勝スピードウェイ', 'テスト(C06)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C07', 'XXXXX', 'C', '2019-07-13', 2, NULL, '十勝スピードウェイ', 'テスト(C07)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C08', 'XXXXX', 'C', '2019-07-13', 2, NULL, '十勝スピードウェイ', 'テスト(C08)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'C09', 'XXXXX', 'C', '2019-07-13', 2, NULL, '十勝スピードウェイ', 'テスト(C09)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D01', 'XXXXX', 'D', '2019-07-13', 2, NULL, '十勝スピードウェイ', 'テスト(D01)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D02', 'XXXXX', 'D', '2019-07-13', 2, NULL, '十勝スピードウェイ', 'テスト(D02)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D03', 'XXXXX', 'D', '2019-07-13', 2, NULL, '十勝スピードウェイ', 'テスト(D03)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D04', 'XXXXX', 'D', '2019-07-13', 2, NULL, '十勝スピードウェイ', 'テスト(D04)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D05', 'XXXXX', 'D', '2019-07-13', 2, NULL, '十勝スピードウェイ', 'テスト(D05)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D06', 'XXXXX', 'D', '2019-07-13', 2, NULL, '十勝スピードウェイ', 'テスト(D06)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D07', 'XXXXX', 'D', '2019-07-13', 2, NULL, '十勝スピードウェイ', 'テスト(D07)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D08', 'XXXXX', 'D', '2019-07-13', 2, NULL, '十勝スピードウェイ', 'テスト(D08)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0),
(NULL, 'D09', 'XXXXX', 'D', '2019-07-13', 2, NULL, '十勝スピードウェイ', 'テスト(D09)', '日産プリンス札幌販売株式会社', NULL, NULL, NULL, '勉強会2019', '2019-07-13 07:58:53', '2019-04-01 16:27:16', 0);

# 質問（共通）
DROP TABLE IF EXISTS M_QUESTION;
CREATE TABLE M_QUESTION (
  QUESTION_ID                       int            NOT NULL AUTO_INCREMENT   COMMENT '質問ID',
  QUESTION_RID                      int            DEFAULT 0                 COMMENT '連携質問ID',
  QUESTION_TYPE                     varchar(20)    DEFAULT ''                COMMENT '質問種別',
  QUESTION_NO                       int            DEFAULT 0                 COMMENT '質問番号',
  QUESTION_STR                      text           DEFAULT ''                COMMENT '質問文字列',
  QUESTION_SHORT_STR                text           DEFAULT ''                COMMENT '質問文字列略称',
  ANSWER_CNT                        int            DEFAULT 0                 COMMENT '回答数',
  ANSWER_0                          varchar(255)   DEFAULT NULL              COMMENT '回答0',
  ANSWER_1                          varchar(255)   DEFAULT NULL              COMMENT '回答1',
  ANSWER_2                          varchar(255)   DEFAULT NULL              COMMENT '回答2',
  ANSWER_3                          varchar(255)   DEFAULT NULL              COMMENT '回答3',
  ANSWER_4                          varchar(255)   DEFAULT NULL              COMMENT '回答4',
  ANSWER_5                          varchar(255)   DEFAULT NULL              COMMENT '回答5',
  ANSWER_6                          varchar(255)   DEFAULT NULL              COMMENT '回答6',
  ANSWER_7                          varchar(255)   DEFAULT NULL              COMMENT '回答7',
  ANSWER_8                          varchar(255)   DEFAULT NULL              COMMENT '回答8',
  ANSWER_9                          varchar(255)   DEFAULT NULL              COMMENT '回答9',
  ANSWER_SHORT_0                    varchar(50)    DEFAULT NULL              COMMENT '回答略称0',
  ANSWER_SHORT_1                    varchar(50)    DEFAULT NULL              COMMENT '回答略称1',
  ANSWER_SHORT_2                    varchar(50)    DEFAULT NULL              COMMENT '回答略称2',
  ANSWER_SHORT_3                    varchar(50)    DEFAULT NULL              COMMENT '回答略称3',
  ANSWER_SHORT_4                    varchar(50)    DEFAULT NULL              COMMENT '回答略称4',
  ANSWER_SHORT_5                    varchar(50)    DEFAULT NULL              COMMENT '回答略称5',
  ANSWER_SHORT_6                    varchar(50)    DEFAULT NULL              COMMENT '回答略称6',
  ANSWER_SHORT_7                    varchar(50)    DEFAULT NULL              COMMENT '回答略称7',
  ANSWER_SHORT_8                    varchar(50)    DEFAULT NULL              COMMENT '回答略称8',
  ANSWER_SHORT_9                    varchar(50)    DEFAULT NULL              COMMENT '回答略称9',
  LECTURE_NAME                      varchar(255)   DEFAULT NULL              COMMENT '研修名',
  INS_DT                            datetime       DEFAULT NULL              COMMENT 'レコード新規日時',
  UPD_DT                            datetime       DEFAULT NULL              COMMENT 'レコード更新日時',
  DEL_FLG                           tinyint        DEFAULT '0'               COMMENT '0=有効 / 1=削除',
  PRIMARY KEY (QUESTION_ID),
  KEY idx1 (LECTURE_NAME,QUESTION_TYPE,QUESTION_NO)
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT = 'M_質問';

# 質問（属性）
DROP TABLE IF EXISTS M_QUESTION_ATR;
CREATE TABLE M_QUESTION_ATR (
  QUESTION_ATR_ID                   int            NOT NULL AUTO_INCREMENT   COMMENT '質問ID',
  QUESTION_TYPE                     varchar(20)    DEFAULT ''                COMMENT '質問タイプ',
  QUESTION_DIV                      char(2)        DEFAULT ''                COMMENT '質問区分 qo=1問1択 qm=1問複数択 qc=比較設問 rp=レポート ex=テスト en=アンケート ',
  QUESTION_NAME                     varchar(255)   DEFAULT NULL              COMMENT '設問名',
  QUESTION_CNT                      int            DEFAULT 0                 COMMENT '質問数',
  QUESTION_EXPLANATION              varchar(255)   DEFAULT NULL              COMMENT '説明',
  ANSWER_SELECT_CNT                 int            DEFAULT 0                 COMMENT '回答選択数',
  LECTURE_NAME                      varchar(255)   DEFAULT NULL              COMMENT '研修名',
  INS_DT                            datetime       DEFAULT NULL              COMMENT 'レコード新規日時',
  UPD_DT                            datetime       DEFAULT NULL              COMMENT 'レコード更新日時',
  DEL_FLG                           tinyint        DEFAULT '0'               COMMENT '0=有効 / 1=削除',
  PRIMARY KEY (QUESTION_ATR_ID),
  KEY idx1 (LECTURE_NAME,QUESTION_TYPE)
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT = 'M_質問属性';
INSERT INTO m_question_atr VALUES (NULL, 'questions_1',    'qo', 'オリエンテーション','', '4', NULL, '0', '勉強会2019', NULL, NULL, '0');
INSERT INTO m_question_atr VALUES (NULL, 'questions_2',    'qo', '座学','', '2', NULL, '0', '勉強会2019', NULL, NULL, '0');
INSERT INTO m_question_atr VALUES (NULL, 'questions_3',    'qc', 'まとめ①','', '3', NULL, '0', '勉強会2019', NULL, NULL, '0');
INSERT INTO m_question_atr VALUES (NULL, 'questions_4',    'qm', 'まとめ②','', '1', NULL, '3', '勉強会2019', NULL, NULL, '0');
INSERT INTO m_question_atr VALUES (NULL, 'questions_5',    'qm', '（予備）','', '0', NULL, '0', '勉強会2019', NULL, NULL, '1');
INSERT INTO m_question_atr VALUES (NULL, 'reporting_1',    'rp', '試乗①','試乗レポート<span style="font-size:2.0em">1</span>', '7', NULL, '0', '勉強会2019', NULL, NULL, '0');
INSERT INTO m_question_atr VALUES (NULL, 'reporting_2',    'rp', '試乗②','', '4', NULL, '0', '勉強会2019', NULL, NULL, '0');
INSERT INTO m_question_atr VALUES (NULL, 'reporting_3',    'rp', '現車・競合車確認','', '7', NULL, '0', '勉強会2019', NULL, NULL, '0');
INSERT INTO m_question_atr VALUES (NULL, 'reporting_4',    'rp', '（予備）','', '0', NULL, '0', '勉強会2019', NULL, NULL, '1');
INSERT INTO m_question_atr VALUES (NULL, 'reporting_5',    'rp', '（予備）','', '0', NULL, '0', '勉強会2019', NULL, NULL, '1');
INSERT INTO m_question_atr VALUES (NULL, 'examinations_1', 'ex', '理解度確認テスト','', '10', NULL, '0', '勉強会2019', NULL, NULL, '0');
INSERT INTO m_question_atr VALUES (NULL, 'examinations_2', 'ex', '（予備）','', '0', NULL, '0', '勉強会2019', NULL, NULL, '1');
INSERT INTO m_question_atr VALUES (NULL, 'examinations_3', 'ex', '（予備）','', '0', NULL, '0', '勉強会2019', NULL, NULL, '1');
INSERT INTO m_question_atr VALUES (NULL, 'examinations_4', 'ex', '（予備）','', '0', NULL, '0', '勉強会2019', NULL, NULL, '1');
INSERT INTO m_question_atr VALUES (NULL, 'examinations_5', 'ex', '（予備）','', '0', NULL, '0', '勉強会2019', NULL, NULL, '1');
INSERT INTO m_question_atr VALUES (NULL, 'enquetes_1',     'en', 'アンケート','', '11', NULL, '0', '勉強会2019', NULL, NULL, '0');
INSERT INTO m_question_atr VALUES (NULL, 'enquetes_2',     'en', '（予備）','', '0', NULL, '0', '勉強会2019', NULL, NULL, '1');
INSERT INTO m_question_atr VALUES (NULL, 'enquetes_3',     'en', '（予備）','', '0', NULL, '0', '勉強会2019', NULL, NULL, '1');
INSERT INTO m_question_atr VALUES (NULL, 'enquetes_4',     'en', '（予備）','', '0', NULL, '0', '勉強会2019', NULL, NULL, '1');
INSERT INTO m_question_atr VALUES (NULL, 'enquetes_5',     'en', '（予備）','', '0', NULL, '0', '勉強会2019', NULL, NULL, '1');


# 回答
DROP TABLE IF EXISTS T_ANSWER;
CREATE TABLE T_ANSWER (
  ANSWER_ID                         int            NOT NULL AUTO_INCREMENT   COMMENT '回答ID',
  ANSWER_DT                         date           DEFAULT NULL              COMMENT '回答日付',
  MEMBER_ID                         int            DEFAULT 0                 COMMENT 'ユーザーID',
  QUESTION_TYPE                     varchar(20)    DEFAULT ''                COMMENT '質問種別',
  QUESTION_NO                       int            DEFAULT 0                 COMMENT '質問番号',
  ANSWER_0                          tinyint        DEFAULT 0                 COMMENT '回答0',
  ANSWER_1                          tinyint        DEFAULT 0                 COMMENT '回答1',
  ANSWER_2                          tinyint        DEFAULT 0                 COMMENT '回答2',
  ANSWER_3                          tinyint        DEFAULT 0                 COMMENT '回答3',
  ANSWER_4                          tinyint        DEFAULT 0                 COMMENT '回答4',
  ANSWER_5                          tinyint        DEFAULT 0                 COMMENT '回答5',
  ANSWER_6                          tinyint        DEFAULT 0                 COMMENT '回答6',
  ANSWER_7                          tinyint        DEFAULT 0                 COMMENT '回答7',
  ANSWER_8                          tinyint        DEFAULT 0                 COMMENT '回答8',
  ANSWER_9                          tinyint        DEFAULT 0                 COMMENT '回答9',
  LECTURE_NAME                      varchar(255)   DEFAULT NULL              COMMENT '研修名',
  INS_DT                            datetime       DEFAULT NULL              COMMENT 'レコード新規日時',
  UPD_DT                            datetime       DEFAULT NULL              COMMENT 'レコード更新日時',
  DEL_FLG                           tinyint        DEFAULT '0'               COMMENT '0=有効 / 1=削除',
  PRIMARY KEY (ANSWER_ID),
  KEY idx1 (LECTURE_NAME,MEMBER_ID,QUESTION_NO),
  KEY idx2 (LECTURE_NAME,ANSWER_DT,MEMBER_ID,QUESTION_NO)
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT = 'T_回答';

# コメント
DROP TABLE IF EXISTS T_COMMENT;
CREATE TABLE T_COMMENT (
  COMMENT_ID                        int            NOT NULL AUTO_INCREMENT   COMMENT 'コメントID',
  COMMENT_DT                        date           DEFAULT NULL              COMMENT 'コメント日付',
  QUESTION_TYPE                     varchar(20)    DEFAULT ''                COMMENT '質問種別',
  MEMBER_ID                         int            DEFAULT 0                 COMMENT 'ユーザーID',
  COMMENT_STR                       text           DEFAULT NULL              COMMENT 'コメントテキスト',
  LECTURE_NAME                      varchar(255)   DEFAULT NULL              COMMENT '研修名',
  INS_DT                            datetime       DEFAULT NULL              COMMENT 'レコード新規日時',
  UPD_DT                            datetime       DEFAULT NULL              COMMENT 'レコード更新日時',
  DEL_FLG                           tinyint        DEFAULT '0'               COMMENT '0=有効 / 1=削除',
  PRIMARY KEY (COMMENT_ID),
  KEY idx1 (LECTURE_NAME,COMMENT_DT,COMMENT_TYPE,MEMBER_ID)
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT = 'T_フリーコメント';

# 標語
DROP TABLE IF EXISTS T_CATCHPHRASE;
CREATE TABLE T_CATCHPHRASE (
  CATCHPHRASE_ID                    int            NOT NULL AUTO_INCREMENT   COMMENT 'コメントID',
  CATCHPHRASE_DT                    date           DEFAULT NULL              COMMENT 'コメント日付',
  MEMBER_ID                         int            DEFAULT 0                 COMMENT 'ユーザーID',
  CATCHPHRASE_STR                   text           DEFAULT NULL              COMMENT 'コメントテキスト',
  VOTE_CNT                          int            DEFAULT 0                 COMMENT '投票数',
  LECTURE_NAME                      varchar(255)   DEFAULT NULL              COMMENT '研修名',
  INS_DT                            datetime       DEFAULT NULL              COMMENT 'レコード新規日時',
  UPD_DT                            datetime       DEFAULT NULL              COMMENT 'レコード更新日時',
  DEL_FLG                           tinyint        DEFAULT '0'               COMMENT '0=有効 / 1=削除',
  PRIMARY KEY (CATCHPHRASE_ID),
  KEY idx1 (LECTURE_NAME,CATCHPHRASE_DT,MEMBER_ID)
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT = 'T_標語';

# 知恵袋
DROP TABLE IF EXISTS T_FAQ;
CREATE TABLE T_FAQ (
  FAQ_ID                            int            NOT NULL AUTO_INCREMENT   COMMENT 'コメントID',
  FAQ_PID                           int            DEFAULT 0                 COMMENT '追加の場合は親ID',
  FAQ_CAT                           varchar(100)   DEFAULT NULL              COMMENT 'カテゴリ―',
  FAQ_TYPE                          char(4)        DEFAULT ''                COMMENT 'FAQタイプ 主 or 追加',
  Q_STR                             text           DEFAULT NULL              COMMENT '質問テキスト',
  A_STR                             text           DEFAULT NULL              COMMENT '回答テキスト',
  MEMBER_ID                         int            DEFAULT 0                 COMMENT 'ユーザーID（予約）',
  LECTURE_NAME                      varchar(255)   DEFAULT NULL              COMMENT '研修名',
  INS_DT                            datetime       DEFAULT NULL              COMMENT 'レコード新規日時',
  UPD_DT                            datetime       DEFAULT NULL              COMMENT 'レコード更新日時',
  DEL_FLG                           tinyint        DEFAULT '0'               COMMENT '0=有効 / 1=削除',
  PRIMARY KEY (FAQ_ID),
  KEY idx1 (FAQ_PID,LECTURE_NAME),
  KEY idx2 (LECTURE_NAME)
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT = 'T_知恵袋';

