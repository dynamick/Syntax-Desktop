<?php
/*
 +--------------------------------------------------------------------------+
 | phpMyBackupPro                                                           |
 +--------------------------------------------------------------------------+
 | Copyright (c) 2004-2007 by Dirk Randhahn                                 |                               
 | http://www.phpMyBackupPro.net                                            |
 | version information can be found in definitions.php.                     |
 |                                                                          |
 | This program is free software; you can redistribute it and/or            |
 | modify it under the terms of the GNU General Public License              |
 | as published by the Free Software Foundation; either version 2           |
 | of the License, or (at your option) any later version.                   |
 |                                                                          |
 | This program is distributed in the hope that it will be useful,          |
 | but WITHOUT ANY WARRANTY; without even the implied warranty of           |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            |
 | GNU General Public License for more details.                             |
 |                                                                          |
 | You should have received a copy of the GNU General Public License        |
 | along with this program; if not, write to the Free Software              |
 | Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307,USA.|
 +--------------------------------------------------------------------------+
*/

/*basic data*/
define('BD_LANG_SHORTCUT',  "it"); // used for the php function setlocale() (http://www.php.net/setlocale)
define('BD_DATE_FORMAT',    "%x %X"); // used for the php function strftime() (http://www.php.net/strftime)
define('BD_CHARSET_HTML',   "ISO-8859-1"); // the charset used in you language for html
define('BD_CHARSET_EMAIL',  "ISO-8859-1"); // the charset used in your langauge for MIME-emails

/*functions.inc.php*/
define('F_START',           "inizio");
define('F_CONFIG',          "configurazione");
define('F_IMPORT',          "importa");
define('F_BACKUP',          "backup");
define('F_SCHEDULE',        "pianifica backup");
define('F_DB_INFO',         "informazioni database");
define('F_SQL_QUERY',       "query sql");
define('F_HELP',            "aiuto");
define('F_LOGOUT',          "logout");
define('F_FOOTER',          "Visita il %ssito di phpMyBackupPro%s per aggiornamenti e novit&agrave;");
define('F_NOW_AVAILABLE',   "Una nuova versione di phpMyBackupPro &egrave; disponibile su %s".PMBP_WEBSITE."%s");
define('F_SELECT_DB',       "Seleziona i database da backuppare");
define('F_SELECT_ALL',      "seleziona tutto");
define('F_COMMENTS',        "Commenti");
define('F_EX_TABLES',       "esporta tabelle");
define('F_EX_DATA',         "esporta dati");
define('F_EX_DROP',         "aggiungi 'drop table'");
define('F_EX_COMP',         "compressione");
define('F_EX_OFF',          "nessuna");
define('F_EX_GZIP',         "gzip");
define('F_EX_ZIP',          "zip");
define('F_DEL_FAILED',      "La cancellazione del backup %s &egrave; fallita.");
define('F_FTP_1',           "La connessione al server FTP &egrave; fallita.");
define('F_FTP_2',           "Impossibile autenticare l'utente");
define('F_FTP_3',           "Upload FTP fallito");
define('F_FTP_4',           "File correttamente uploadato come");
define('F_FTP_5',           "Cancellazione via FTP del file '%s' fallita");
define('F_FTP_6',           "File '%s' correttamente eliminato dal server FTP");
define('F_FTP_7',           "File '%s' non disponibile sul server FTP");
define('F_MAIL_1',          "Un destinatario email non &egrave; valido");
define('F_MAIL_2',          "Questa email &egrave; stata inviata da phpMyBackupPro ".PMBP_VERSION." ".PMBP_WEBSITE." funzionante su");
define('F_MAIL_3',          "non pu&ograve; essere letto");
define('F_MAIL_4',          "Backup MySQL da ");
define('F_MAIL_5',          "La mail non pu&ograve; essere inviata");
define('F_MAIL_6',          "File inviati via email a ");
define('F_YES',             "si");
define('F_NO',              "no");
define('F_DURATION',        "Durata");
define('F_SECONDS',         "secondi");

/*index.php*/
define('I_SQL_ERROR',             "ERRORE: inserisci i dati del server MySQL in 'configurazione'!");
define('I_NAME',                  "Backup Manager per Syntax Desktop - basato su phpMyBackupPro");
define('I_WELCOME',               "phpMyBackupPro &egrave; un software libero sotto licenza GPL.<br>\nSe hai bisogno di aiuto prova l'help online o visita %s.<br><br>\n<b>Scegli dal menu quello che vuoi fare!</b><br>Se &egrave; la prima volta che utilizzi phpMyBackupPro devi iniziare con la configurazione!<br>\nI permessi per la cartella <i>'export'</i> ed il file <i>'global_conf.php'</i> devono essere impostati a 0777.");
define('I_CONF_ERROR',            "Non posso scrivere sul file ".PMBP_GLOBAL_CONF."!");
define('I_DIR_ERROR',             "Non posso scrivere sulla cartella ".PMBP_EXPORT_DIR."!");
define('PMBP_I_INFO',             "Informazioni sul Sistema");
define('PMBP_I_SERVER',           "Server");
define('PMBP_I_TIME',             "Ora");
define('PMBP_I_PHP_VERS',         "Versione PHP");
define('PMBP_I_MEM_LIMIT',        "Limite di memoria di PHP");
define('PMBP_I_SAFE_MODE',        "Safe Mode attivo");
define('PMBP_I_FTP',              "Trasferimento FTP possibile");
define('PMBP_I_MAIL',             "Invio Email");
define('PMBP_I_GZIP',             "compressione gzip possibile");
define('PMBP_I_SQL_SERVER',       "Server MySQL");
define('PMBP_I_SQL_CLIENT',       "Client MySQL");
define('PMBP_I_NO_RES',           "*Non determinato*");
define('PMBP_I_LAST_SCHEDULED',   "Ultimo backup pianificato");
define('PMBP_I_LAST_LOGIN',       "Ultimo login");
define('PMBP_I_LAST_LOGIN_ERROR', "Ultimo login incorretto");

/*config.php*/
define('C_SITENAME',        "Nome del sito");
define('C_LANG',            "lingua");
define('C_SQL_HOST',        "Server MySQL");
define('C_SQL_USER',        "Utente MySQL");
define('C_SQL_PASSWD',      "Password MySQL");
define('C_SQL_DB',          "solo questo database");
define('C_FTP_USE',         "salva backup via FTP?");
define('C_FTP_BACKUP',      "usa backup per cartelle?");
define('C_FTP_REC',         "backup delle cartelle ricorsivo?");
define('C_FTP_SERVER',      "Server FTP (url o indirizzo IP)");
define('C_FTP_USER',        "Utente FTP");
define('C_FTP_PASSWD',      "Password FTP");
define('C_FTP_PATH',        "percorso FTP");
define('C_FTP_PASV',        "modalit&agrave; FTP passiva?");
define('C_FTP_PORT',        "Porta FTP");
define('C_FTP_DEL',         "cancella file sul server FTP");
define('C_EMAIL_USE',       "usa email?");
define('C_EMAIL',           "indirizzo email");
define('C_STYLESHEET',      "skin");
define('C_DATE',            "formato data");
define('C_DEL_TIME',        "elimina i backup locali dopo x giorni");
define('C_DEL_NUMBER',      "mantieni al massimo x files per database");
define('C_TIMELIMIT',       "php timelimit");
define('C_IMPORT_ERROR',    "mostra gli errori di importazione?");
define('C_NO_LOGIN',        "disabilita la funzione di login?");
define('C_LOGIN',           "autenticazione HTTP?");
define('C_DIR_BACKUP',      "abilita i backup delle cartelle?");
define('C_DIR_REC',         "il backup delle cartelle comprende le sotto-cartelle?");
define('C_CONFIRM',         "livello di conferma");
define('C_CONFIRM_1',       "svuota, cancella, importa");
define('C_CONFIRM_2',       "... tutto");
define('C_CONFIRM_3',       "... TUTTO");
define('C_CONFIRM_4',       "non confermare nulla");
define('C_BASIC_VAL',       "Configurazione di base");
define('C_EXT_VAL',         "Configurazione estesa");
define('PMBP_C_SYSTEM_VAL', "Variabili di sistema");
define('PMBP_C_SYS_WARNING',"Queste variabili sono gestite da phpMyBackupPro. Non modificarle a meno di sapere cosa stai facendo!");
define('C_TITLE_SQL',       "Dati SQL");
define('C_TITLE_FTP',       "Impostazioni FTP");
define('C_TITLE_EMAIL',     "Backup per email");
define('C_TITLE_STYLE',     "Stile di phpMyBackupPro");
define('C_TITLE_DELETE',    "Cancellazione automatica dei file di backup");
define('C_TITLE_CONFIG',    "Ulteriori opzioni di configurazione");
define('C_WRONG_TYPE',      "non &egrave corretto!");
define('C_WRONG_SQL',       "Dati MySQL non corretti!");
define('C_WRONG_DB',        "Nome del database MySQL non corretto!");
define('C_WRONG_FTP',       "Dati di connsessione FTP non corretti!");
define('C_OPEN',            "Impossibile aprire");
define('C_WRITE',           "Impossibile scrivere su");
define('C_SAVED',           "Dati salvati correttamente");
define('C_WRITEABLE',       "non &egrave; scrivibile");
define('C_SAVE',            "Salva dati");

/*import.php*/
define('IM_ERROR',          "%d errore(i) occorso. Puoi usare 'svuota database' per assicurarti che il database non contenga tabelle.");
define('IM_SUCCESS',        "Importato correttamente");
define('IM_TABLES',         "tabelle e");
define('IM_ROWS',           "righe");
define('B_EMPTIED_ALL',     "Tutti i database sono stati svuotati correttamente");
define('B_EMPTIED',         "Il database &egrave; svuotato correttamente");
define('B_DELETED',         "Il file &egrave; stato cancellato correttamente");
define('B_DELETED_ALL',     "Tutti i file sono stati eliminati correttamtente");
define('B_NO_FILES',        "Al momento non ci sono file di backup");
define('B_DELETE_ALL_2',    "cancella TUTTI i backup");
define('B_IMPORT_ALL',      "importa TUTTI i backup");
define('B_EMPTY_ALL',       "svuota TUTTI i database");
define('B_EMPTY_DB',        "svuota database");
define('B_DELETE_ALL',      "cancella tutti i backup");
define('B_INFO',            "informazioni");
define('B_VIEW',            "vista");
define('B_DOWNLOAD',        "scarica");
define('B_IMPORT',          "importa");
define('B_IMPORT_FRAG',     "frammentato");
define('B_DELETE',          "cancella");
define('B_CONF_EMPTY_DB',   "Vuoi veramente svuotare il database?");
define('B_CONF_DEL_ALL',    "Vuoi veramente cancellare tutti i backup di questo database?");
define('B_CONF_IMP',        "Vuoi veramente importare questo backup?");
define('B_CONF_DEL',        "Vuoi veramente cancellare questo backup?");
define('B_CONF_EMPT_ALL',   "Vuoi veramente svuotare TUTTI i database?");
define('B_CONF_IMP_ALL',    "Vuoi veramente importare TUTTI i backup?");
define('B_CONF_DEL_ALL_2',  "Vuoi veramente cancellare TUTT i backup?");
define('B_LAST_BACKUP',     "Ultimo backup salvato il");
define('B_SIZE_SUM',        "Peso totale di tutti i backup");

/*backup.php*/
define('EX_SAVED',             "File correttamente salvato come");
define('EX_NO_DB',             "Nessu database selezionato");
define('EX_EXPORT',            "Backup");
define('EX_NOT_SAVED',         "Impossibile salvare il backup del database %s in '%s'");
define('EX_DIRS',              "Seleziona le cartelle da backuppare sul server FTP");
define('EX_DIRS_MAN',          "Inserisci altri percorsi relativi alla cartella di phpMyBackupPro.<br>Separali con '|'");
define('EX_PACKED',            "Comprimi tutto in un unico file ZIP");
define('PMBP_EX_NO_AVAILABLE', "Il database %s non &egrave; disponibile");
define('PMBP_EXS_UPDATE_DIRS', "Aggiorna l'elenco delle cartelle");
define('PMBP_EX_NO_ARGV',      "Esempio di utilizzo:\n$ php backup.php db1,db2,db3\nPer ulteriori funzioni leggi 'SHELL_MODE.txt' nella cartella 'documentation'");

/*scheduled.php*/
define('EXS_PERIOD',        "Seleziona il periodo");
define('EXS_PATH',          "Seleziona la cartella in cui depositare il file PHP");
define('EXS_BACK',          "indietro");
define('PMBP_EXS_ALWAYS',   "Ad ogni chiamata");
define('EXS_HOUR',          "ora");
define('EXS_HOURS',         "ore");
define('EXS_DAY',           "giorno");
define('EXS_DAYS',          "giorni");
define('EXS_WEEK',          "settimana");
define('EXS_WEEKS',         "settimane");
define('EXS_MONTH',         "mese");
define('EXS_SHOW',          "Mostra script");
define('PMBP_EXS_INCL',     "Includi questo script nel file PHP (%s) che far&agrave; il backup");
define('PMBP_EXS_SAVE',     "oppure salva lo script in un nuovo file (sovrascrivendo quello gi&agrave; presente!)");

/*file_info.php*/
define('INF_INFO',          "informazioni");
define('INF_DATE',          "Data");
define('INF_DB',            "Database");
define('INF_SIZE',          "Dimensione del backup");
define('INF_COMP',          "Compresso");
define('INF_DROP',          "Contiene 'drop table'");
define('INF_TABLES',        "Contiene tabelle");
define('INF_DATA',          "Contiene dati");
define('INF_COMMENT',       "Commenti");
define('INF_NO_FILE',       "Nessun file selezionato");

/*db_status.php*/
define('DB_NAME',           "nome del database");
define('DB_NUM_TABLES',     "numero di tabelle ");
define('DB_NUM_ROWS',       "numero di righe");
define('DB_SIZE',           "dimensione");
define('DB_DIFF',           "Le dimensioni possono essere diverse da quelle dei file di backup!");
define('DB_NO_DB',          "Nessun databases disponibile");
define('DB_TABLES',         "informazioni sulla tabella");
define('DB_TAB_TITLE',      "tabelle del database ");
define('DB_TAB_NAME',       "nome della tabella");
define('DB_TAB_COLS',       "numero di campi");

/*sql_query.php*/
define('SQ_ERROR',          "Errore occorso alla linea");
define('SQ_SUCCESS',        "Eseguito correttamente");
define('SQ_RESULT',         "Risultato della query");
define('SQ_AFFECTED',       "Numero di righe coinvolte");
define('SQ_WARNING',        "ATTENZIONE: questa pagina &egrave; progettata per eseguire semplici interrogazioni. L'uso sconsiderato pu&ograve; causare la perdita dei dati!");
define('SQ_SELECT_DB',      "Seleziona database");
define('SQ_INSERT',         "Query SQL");
define('SQ_FILE',           "Upload file sql");
define('SQ_SEND',           "Esegui");

/*login.php*/
define('LI_MSG',            "Prego autenticarsi (usa utente e password MySQL)");
define('LI_USER',           "utente");
define('LI_PASSWD',         "password");
define('LI_LOGIN',          "Login");
define('LI_LOGED_OUT',      "Scollegato in sicurezza!");
define('LI_NOT_LOGED_OUT',  "Non scollegato in sicurezza!<br>Per aumentare la sicurezza inserire una password SBAGLIATA");

/*big_import.php*/
define('BI_IMPORTING_FILE', "Importazione del file");
define('BI_INTO_DB',        "Nel database");
define('BI_SESSION_NO',     "Sessione numero");
define('BI_STARTING_LINE',  "Inizio alla linea");
define('BI_STOPPING_LINE',  "Arresto alla linea");
define('BI_QUERY_NO',       "Numero di query eseguite");
define('BI_BYTE_NO',        "Numero di bytes processati finora");
define('BI_DURATION',       "Durata dell'ultima sessione");
define('BI_THIS_LAST',      "questa sessione/totale");
define('BI_END',            "Fine del file raggiunta, l'importazione sembra essere OK");
define('BI_RESTART',        "Ripeti l'importazione del file ");
define('BI_SCRIPT_RUNNING', "Questo script &egrave; ancora in esecuzione!<br>Attendi il raggiungimento della fine del file");
define('BI_CONTINUE',       "Continua dalla linea");
define('BI_ENABLE_JS',      "Abilita Javascript per continuare automaticamente");
define('BI_BROKEN_ZIP',     "Il file ZIP sembra essere corrotto");
define('BI_WRONG_FILE',     "Interruzione alla linea %s.<br>La query corrente include pi&ugrave; di %s istruzioni. Questo succede quando il backup viene creato da un'applicazione che non inserisce interruzioni di linea alla fine delle query, oppure il file contiene INSERT estese.");
?>
