rm -rf /usr/local/psa/admin/htdocs/modules/hosted_exchange/*
rm -rf /usr/local/psa/admin/plib/modules/hosted_exchange/*

cp -r hosted_exchange/htdocs/* /usr/local/psa/admin/htdocs/modules/hosted_exchange/
cp -r hosted_exchange/plib/* /usr/local/psa/admin/plib/modules/hosted_exchange/


#The extension was successfully created.
#The path to extension's entry points: /usr/local/psa/admin/htdocs/modules/hello-world/
#The path to PHP classes: /usr/local/psa/admin/plib/modules/hello-world/
#The path to installation scripts: /usr/local/psa/admin/plib/modules/hello-world/scripts/
#The path to the directory with run-time data: /usr/local/psa/var/modules/hello-world/
