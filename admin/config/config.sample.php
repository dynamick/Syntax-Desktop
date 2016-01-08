<?php
//MySQL ACCOUNT
$synDbHost="localhost";
$synDbUser="root";
$synDbPassword="";
$synDbName="syntax";

//FIRST RUN ROOT ACCOUNT
$synRootUser = "root";
$synRootPassword="syntax";
$synRootPasswordSalt="a9027d553b87217fe8d3b188cf7abfce";

//Upload image directory
//YOU MUST PUT TRAILING SLASH;
//the initial relative path is syntax desktop installation dir
//(i.e. relative path="/syntax desktop/" $mat="../mat" ---> result "/syntax desktop/"+"../mat/")
$mat="/mat";
$thumb="/mat/thumb";

//admin email
$synAdministrator="info@dynamick.it";

//site address "http://www.dynamick.it"
$synWebsiteTitle="Syntax Desktop Demo";

//site address "http://www.dynamick.it"
$synWebsite="/";

//rows per page
$synRowsPerPage=20;

//version
$synVersion="2.13.0";

//relative path. to the /admin folder I.e. /admin
$synAdminPath="/admin";

//relative path. to the /public folder I.e. /public
$synPublicPath="/public";

//absolute path. to the document root folder where Syntax is installed I.e. /var/www/html/mywebsite/
$synAbsolutePath=getenv("DOCUMENT_ROOT");

//a package is a smarty plugin + syntax desktop service
$synPackagePath="/package";

//path to locate the smarty plugin
$synPluginPath="/plugin";

//if true, when insert a new row, the value is copied in all languages
$synInsertValueInAllLang=true;
?>
