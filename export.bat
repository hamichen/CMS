@ECHO OFF
SET BIN_TARGET=%~dp0/vendor/mysql-workbench-schema-exporter/mysql-workbench-schema-exporter/bin/mysql-workbench-schema-export
php "%BIN_TARGET%" %*  --config=./config/db-wmb.json ./db/menu.mwb
