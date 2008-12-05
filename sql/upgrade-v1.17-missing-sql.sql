# Upgrade 1.17 - Missing variables on archive table
#---------------------------------------------------------
alter table archive add variables text;
alter table archive add template text;
