CREATE TABLE mybbs_posts (
    post_id   INT(10)      NOT NULL AUTO_INCREMENT,
    parent_id INT(10)      NOT NULL,
    root_id   INT(10)      NOT NULL,
    bbs_id    TINYINT(3)   NOT NULL DEFAULT 1,
    uid       MEDIUMINT(5) NOT NULL DEFAULT 0,
    title     VARCHAR(64)           DEFAULT NULL,
    name      VARCHAR(30)           DEFAULT NULL,
    message   TEXT         NOT NULL,
    post_time INT(10)      NOT NULL,
    edit_time INT(10)      NOT NULL DEFAULT 0,
    post_ip   VARCHAR(22)  NOT NULL DEFAULT '',
    status    TINYINT(1)   NOT NULL DEFAULT 1,
    PRIMARY KEY (post_id)
)
    ENGINE = ISAM;

CREATE TABLE mybbs_master (
    bbs_id       TINYINT(3) DEFAULT '1'  NOT NULL AUTO_INCREMENT,
    bbs_name     VARCHAR(64)             NOT NULL,
    bbs_note     TEXT,
    bbs_contents TEXT,
    page_limit   TINYINT(1) DEFAULT '20' NOT NULL,
    status       TINYINT(1) DEFAULT 0    NOT NULL,
    sort_order   TINYINT(1)              NOT NULL,
    guest_post   TINYINT(1)              NOT NULL,
    guest_name   VARCHAR(20)             NOT NULL DEFAULT 'Guest',
    tarea_dhtml  TINYINT(1) DEFAULT 0    NOT NULL,
    tarea_font   TINYINT(1) DEFAULT 0    NOT NULL,
    tarea_smily  TINYINT(1) DEFAULT 0    NOT NULL,
    PRIMARY KEY (bbs_id)
)
    ENGINE = ISAM;


INSERT INTO mybbs_master (bbs_id, bbs_name, bbs_note, page_limit, status, sort_order)
VALUES (1, 'test', 'test', 10, 1, 1);
