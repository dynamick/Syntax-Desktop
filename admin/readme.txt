REQUISITI

Syntax Desktop è un'applicazione web-based che necessita di questi requisiti per poter funzionare.

*** Lato server ***
Webserver - Attualmente l'applicazione è stata testata esclusivamente con Apache, ma questo non implica che funzioni solo con questo webserver 
PHP - Interprete php. Testato con la versione 4 
MySql - Testato con database mysql. Syntax usa il wrapper AdoDB, un layer che si occupa dell'accesso al db. Questo implica che anche altri tipi di database potrebbero funzionare. 
E' preferito l'uso di tabelle di tipo innoDb per poter utilizzare le foreign keys.

*** Lato client ***
Purtroppo syntax funziona correttamente usando solamente Internet Explorer. Gli altri browser non sono mai stati testati completamente. Mi scuso con questa grave limitazione. Cercherò di aumentare la compatibilità il più presto possibile.

********************************************************************************
INSTALLAZIONE

L'installazione, come in molte applicazioni web, richiede la semplice modifica del file /syntax desktop/config/cfg.php.

*** Configurazione del Database *** 
Occorre prima di tutto creare un database mysql, possibilmente di tipo InnoDB, in modo da poter gestire le Foreign keys. Dopo di che, bisogna inserire i dati di accesso al db nel file cfg.php come riportato nelle righe qui sotto:

//ACCOUNT 
$synDbHost="localhost";
$synDbUser="root";
$synDbPassword="";
$synDbName="syntax";

Altri valori di configurazione
Ci sono altri parametri da configurare. Ecco le righe da valorizzare nel cfg.php

//Upload image directory 
//YOU MUST PUT TRAILING SLASH; 
//the initial relative path is syntax desktop installation dir 
//(i.e. relative path="/syntax desktop/" $mat="../mat" ---> result "/syntax desktop/"+"../mat/")
$mat="../mat/";
$thumb="../mat/thumb/";

//admin email
$synAdministrator="info@dynamick.it";

//site address "http://www.dynamick.it"
$synWebsite="/";

//rows per page
$synRowsPerPage=17;

//version
$synVersion="2 Beta";

*** Configurazione di php.ini ***
Assicuratevi di avere questi parametri settati nel file php.ini
error_reporting = E_ALL & ~E_NOTICE 
register_globals = On 
allow_call_time_pass_reference = On 

*** Permessi sui file ***
Verificate di avere i permessi di scrittura sui seguenti file:
/syntax desktop/config/cfg.php
/syntax desktop/public/configs/files.txt
/syntax desktop/includes/php/smarty/templates_c
/syntax desktop/modules/dump/backup/

*** Esecuzione di Syntax Desktop ***
Ora si tratta solo di eseguire l'applicazione. Se tutti i parametri di accesso al db sono corretti, Syntax vi chiederà di scegliere il dump da caricare sul vostro database. Altrimenti vi chiederà di riconfigurare i parametri di accesso al db.

