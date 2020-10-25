-- xoops_ の接頭子は 適宜書き換え

ALTER TABLE xoops_mybbs_master
    ADD tarea_dhtml TINYINT(1) DEFAULT 0 NOT NULL;
ALTER TABLE xoops_mybbs_master
    ADD tarea_smily TINYINT(1) DEFAULT 0 NOT NULL;
ALTER TABLE xoops_mybbs_master
    ADD tarea_font TINYINT(1) DEFAULT 0 NOT NULL;
