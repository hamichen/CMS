#!/bin/sh
rm -f ./data/temp/*
vendor/bin/mysql-workbench-schema-export --config=config/db-mwb.json ./db/menu.mwb
