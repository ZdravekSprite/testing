<?php
$tpl = <<< EOTPL
[Config]
;WAMPCONFIGSTART
ImageList=images_off.bmp
ServiceCheckInterval=1
ServiceGlyphRunning=13
ServiceGlyphPaused=10
ServiceGlyphStopped=11
;TrayIcon=wampserver.ico
TrayIconAllRunning=16
TrayIconSomeRunning=17
TrayIconNoneRunning=18
ID={wampee}
AboutHeader=WAMPEE (Wampserver Portable)
AboutVersion=Version ${c_wampVersion}
;WAMPCONFIGEND

[AboutText]
Wampee Version ${c_wampVersion}

Created by Herve Leclerc (herve.leclerc@alterway.fr) 								(version 2.x.x)
Updated by Renan LAVAREC - Ti-R - renan.lavarec@ti-r.com - http://www.ti-r.com/		(version 3.1.x)

Sources are available at SourceForge

Forum Wampserver: http://forum.wampserver.com/index.php
${w_translated_by}
______________________ Versions used ______________________
Apache ${c_apacheVersion} - PHP ${c_phpVersion}
${SupportMySQL}MySQL ${c_mysqlVersion}
${SupportMariaDB}MariaDB ${c_mariadbVersion}
PHP ${c_phpVersion} for CLI (Command-Line Interface)

[Messages]
AllRunningHint=WAMPEE - ${w_serverOffline}
SomeRunningHint=WAMPEE - ${w_serverOffline}
NoneRunningHint=WAMPEE - ${w_serverOffline}

[StartupAction]
;WAMPSTARTUPACTIONSTART
;WAMPSTARTUPACTIONEND

[Menu.Right.Settings]
;WAMPMENURIGHTSETTINGSSTART
AutoLineReduction=no
BarVisible=no
SeparatorsAlignment=center
SeparatorsFade=yes
SeparatorsFadeColor=clBtnShadow
SeparatorsFlatLines=yes
SeparatorsGradientEnd=clSilver
SeparatorsGradientStart=clGray
SeparatorsGradientStyle=horizontal
SeparatorsFont=Arial,8,clWhite,bold
SeparatorsSeparatorStyle=caption
;WAMPMENURIGHTSETTINGSEND

[Menu.Left.Settings]
;WAMPMENULEFTSETTINGSSTART
AutoLineReduction=no
BarVisible=yes
BarCaptionAlignment=bottom
BarCaptionCaption=Wampee ${c_wampVersion}
BarCaptionDepth=1
BarCaptionDirection=downtoup
BarCaptionFont=Tahoma,16,clWhite,bold italic
BarCaptionHighlightColor=clNone
BarCaptionOffsetY=0
BarCaptionShadowColor=clNone
BarPictureHorzAlignment=center
BarPictureOffsetX=0
BarPictureOffsetY=0
BarPicturePicture=barimage.bmp
BarPictureTransparent=yes
BarPictureVertAlignment=bottom
BarBorder=clNone
BarGradientEnd=$00550000
BarGradientStart=clBlue
BarGradientStyle=horizontal
BarSide=left
BarSpace=0
BarWidth=34
SeparatorsAlignment=center
SeparatorsFade=yes
SeparatorsFadeColor=clBtnShadow
SeparatorsFlatLines=yes
SeparatorsFont=Arial,8,clWhite,bold 
SeparatorsGradientEnd=$00FFAA55
SeparatorsGradientStart=$00550000
SeparatorsGradientStyle=horizontal
SeparatorsSeparatorStyle=caption
;WAMPMENULEFTSETTINGSEND

[Menu.Right]
;WAMPMENURIGHTSTART
Type: item; Caption: "${w_about}"; Action: about; Glyph: 22
Type: item; Caption: "${w_refresh}"; Action: multi; Actions: wampreload; Glyph: 12
Type: item; Caption: "${w_help}"; Action: run; FileName: "${c_navigator}"; Parameters: "${c_edge}http://forum.wampserver.com/list.php?${forum}"; Glyph: 31
Type: submenu; Caption: "${w_language}"; SubMenu: language; Glyph: 3
Type: submenu; Caption: "${w_wampSettings}"; Submenu: submenu.settings; Glyph: 25
Type: submenu; Caption: "${w_wampTools}"; Submenu: submenu.tools; Glyph: 29
Type: item; Caption: "${w_exit}"; Action: multi; Actions: myexit; Glyph: 30
;WAMPMENURIGHTEND

[wampreload]
;WAMPRELOADSTART
Action: run; FileName: "${c_phpCli}";Parameters: "refresh.php";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated
Action: run; FileName: "${c_installDir}/scripts/CheckProcess.exe"; WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated 
Action: readconfig;
;WAMPRELOADEND

[language]
;WAMPLANGUAGESTART
;WAMPLANGUAGEEND

[submenu.settings]
;WAMPSETTINGSSTART
;WAMPSETTINGSEND

[Menu.Left]
;WAMPMENULEFTSTART
Type: separator; Caption: "Made in France by Ti-R"
Type: item; Caption: "${w_localhost}"; Action: run; FileName: "${c_navigator}"; Parameters: "http://localhost:@APACHE_PORT@/"; Glyph: 27
Type: item; Caption: "${w_phpmyadmin}	${phpmyadminVersion}"; Action: run; FileName: "${c_navigator}"; Parameters: "http://localhost:@APACHE_PORT@/phpmyadmin/"; Glyph: 28
Type: item; Caption: "${w_wwwDirectory}"; Action: shellexecute; FileName: "${wwwDir}"; Glyph: 2
Type: submenu; Caption: "Apache		${c_apacheVersion}"; SubMenu: apacheMenu; Glyph: 37
Type: submenu; Caption: "PHP		${c_phpVersion}"; SubMenu: phpMenu; Glyph: 39
;Type: submenu; Caption: "MySQL		${c_mysqlVersion}"; SubMenu: mysqlMenu; Glyph: 38
;Type: submenu; Caption: "MariaDB		${c_mariadbVersion}"; SubMenu: mariadbMenu; Glyph: 42
;WAMPDBMSMENU
Type: separator; Caption: "Debug"
Type: item; Caption: "Client XDebug"; Glyph: 41; Action: run; FileName: "${c_installDir}/tools/xdc/xdc.exe"
Type: separator; Caption: "${c_wampVersion} - ${c_wampMode} - ${w_services}"
Type: item; Caption: "${w_startServices}"; Action: multi; Actions: StartAll; Glyph: 9
Type: item; Caption: "${w_stopServices}"; Action: multi; Actions: StopAll; Glyph: 11
Type: item; Caption: "${w_restartServices}"; Action: multi; Actions: RestartAll; Glyph: 12
Type: separator;
Type: item; Caption: "Eject Key"; Action: multi; Actions: WampEject; Glyph: 40
Type: separator;
Type: item; Caption: "${w_putOnline}"; Action: multi; Actions: onlineoffline
;WAMPMENULEFTEND

[apacheMenu]
;WAMPAPACHEMENUSTART
Type: submenu; Caption: "Version"; SubMenu: apacheVersion; Glyph: 3
Type: submenu; Caption: "Service"; SubMenu: apacheService; Glyph: 3
Type: submenu; Caption: "${w_apacheModules}"; SubMenu: apache_mod; Glyph: 25
Type: submenu; Caption: "${w_aliasDirectories}"; SubMenu: alias_dir; Glyph: 3
Type: item; Caption: "httpd.conf"; Glyph: 6; Action: run; FileName: "${c_phpExe}"; Parameters: "openFiles.php ${c_apacheConfFile}"; WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated;
Type: item; Caption: "${w_apacheErrorLog}"; Glyph: 6; Action: run; FileName: "${c_phpExe}"; Parameters: "openFiles.php ${c_installDir}/${logDir}apache_error.log"; WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated;
Type: item; Caption: "${w_apacheAccessLog}"; Glyph: 6; Action: run; FileName: "${c_phpExe}"; Parameters: "openFiles.php ${c_installDir}/${logDir}access.log"; WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated;
Type: item; Caption: "${w_apacheDoc}"; Action: run; FileName: "${c_navigator}"; Parameters: "${c_edge}http://httpd.apache.org/docs/2.4/en/"; Glyph: 35
;WAMPAPACHEMENUEND

[apacheVersion]
;WAMPAPACHEVERSIONSTART
;WAMPAPACHEVERSIONEND

[phpMenu]
;WAMPPHPMENUSTART
Type: submenu; Caption: "${w_version}"; SubMenu: phpVersion; Glyph: 3
Type: submenu; Caption: "${w_phpSettings}"; SubMenu: php_params;  Glyph: 25
Type: submenu; Caption: "${w_phpExtensions}"; SubMenu: php_ext;  Glyph: 25
Type: item; Caption: "php.ini"; Glyph: 33; Action: run; FileName: "${c_phpExe}"; Parameters: "openFiles.php ${c_phpConfFile}"; WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated;
Type: item; Caption: "${w_phpLog}"; Glyph: 33; Action: run; FileName: "${c_phpExe}"; Parameters: "openFiles.php ${c_installDir}/${logDir}php_error.log"; WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated;
Type: item; Caption: "${w_phpDoc}"; Action: run; FileName: "${c_navigator}"; Parameters: "${c_edge}http://www.php.net/manual/en/"; Glyph: 35
;WAMPPHPMENUEND

[phpVersion]
;WAMPPHPVERSIONSTART
;WAMPPHPVERSIONEND

[mysqlMenu]
;WAMPMYSQLMENUSTART
;Type: submenu; Caption: "Version"; SubMenu: mysqlVersion; Glyph: 3
;Type: submenu; Caption: "Service"; SubMenu: mysqlService; Glyph: 3
;Type: item; Caption: "${w_mysqlConsole}"; Action: run; FileName: "${c_mysqlConsole}";Parameters: "-u root -p"; Glyph: 0
;Type: item; Caption: "my.ini"; Glyph: 6; Action: run; FileName: "${c_phpExe}"; Parameters: "openFiles.php ${c_mysqlConfFile}"; WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated;
;Type: item; Caption: "${w_mysqlLog}"; Glyph: 6; Action: run; FileName: "${c_phpExe}"; Parameters: "openFiles.php ${c_installDir}/${logDir}mysql.log"; WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated;
;Type: item; Caption: "${w_mysqlDoc}"; Action: run; FileName: "${c_navigator}"; Parameters: "${c_edge}http://dev.mysql.com/doc/index.html"; Glyph: 35
;WAMPMYSQLMENUEND

[mysqlVersion]
;WAMPMYSQLVERSIONSTART
;WAMPMYSQLVERSIONEND

[mariadbMenu]
;WAMPMARIADBMENUSTART
;WAMPMARIADBMENUEND

[mariadbVersion]
;WAMPMARIADBVERSIONSTART
;WAMPMARIADBVERSIONEND

[mysql_params]
Type: separator; Caption: "${w_mysqlSettings}"
;WAMPMYSQL_PARAMSSTART
;WAMPMYSQL_PARAMSEND

[mariadb_params]
Type: separator; Caption: "${w_mariaSettings}"
;WAMPMARIADB_PARAMSSTART
;WAMPMARIADB_PARAMSEND

[alias_dir]
;WAMPALIAS_DIRSTART
Type: separator; Caption: "${w_aliasDirectories}"
Type: item; Caption: "${w_addAlias}"; Action: multi; Actions: add_alias;Glyph : 1
Type: separator
;WAMPADDALIAS
;WAMPALIAS_DIREND


[php_params]
Type: separator; Caption: "${w_phpSettings}"
;WAMPPHP_PARAMSSTART
;WAMPPHP_PARAMSEND


[php_ext]
Type: separator; Caption: "${w_phpExtensions}"
;WAMPPHP_EXTSTART
;WAMPPHP_EXTEND



[add_alias]
;WAMPADD_ALIASSTART
Action: run; FileName: "${c_phpExe}";Parameters: "-c . addAlias.php";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated
Action: run; FileName: "${c_phpCli}";Parameters: "refresh.php";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated
Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "stop apache";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated 
Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "start apache";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated 
Action: run; FileName: "${c_installDir}/scripts/CheckProcess.exe"; WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated 
Action: readconfig;
;WAMPADD_ALIASEND


[DoubleClickAction]
Action: about;

[apacheService]
;WAMPAPACHESERVICESTART
Type: separator; Caption: "${w_apache}"
Type: item; Caption: "${w_startResume}"; Action: multi; Actions: start_apache_p; Glyph: 9
Type: item; Caption: "${w_stopService}"; Action: multi; Actions: stop_apache_p ; Glyph: 11
Type: item; Caption: "${w_restartService}"; Action: multi; Actions: restart_apache_p; Glyph: 12
Type: separator
Type: item; Caption: "${w_testPort80}"; Action: run; FileName: "${c_phpExe}";Parameters: "-c . testPort.php";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated; Glyph: 9
;WAMPAPACHESERVICEEND

[start_apache_p]
Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "start apache";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated ;
Action: run; FileName: "${c_installDir}/scripts/CheckProcess.exe"; WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated 
Action: readconfig

[stop_apache_p]
Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "stop apache";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated ;
Action: run; FileName: "${c_installDir}/scripts/CheckProcess.exe"; WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated 
Action: readconfig

[restart_apache_p]
Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "stop apache";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated ;
Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "start apache";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated ;
Action: run; FileName: "${c_installDir}/scripts/CheckProcess.exe"; WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated 
Action: readconfig


[MySqlService]
;WAMPMYSQLSERVICESTART
Type: separator; Caption: "${w_mysql}"
Type: item; Caption: "${w_startResume}"; Action: multi; Actions: start_mysql_p; Glyph: 9
Type: item; Caption: "${w_stopService}"; Action: multi; Actions: stop_mysql_p; Glyph: 11
Type: item; Caption: "${w_restartService}"; Action: multi; Actions: restart_mysql_p; Glyph: 12
;WAMPMYSQLSERVICEEND

[start_mysql_p]
${SupportMySQL}Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "start mysql";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated ;
Action: run; FileName: "${c_installDir}/scripts/CheckProcess.exe"; WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated 
Action: readconfig

[stop_mysql_p]
Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "stop mysql";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated ;
Action: run; FileName: "${c_installDir}/scripts/CheckProcess.exe"; WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated 
Action: readconfig

[restart_mysql_p]
Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "stop mysql";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated ;
${SupportMySQL}Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "start mysql";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated ;
Action: run; FileName: "${c_installDir}/scripts/CheckProcess.exe"; WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated 
Action: readconfig


[StartAll]
;WAMPSTARTALLSTART
Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "start all";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated ;
Action: run; FileName: "${c_installDir}/scripts/CheckProcess.exe"; WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated 
Action: readconfig
;WAMPSTARTALLEND

[StopAll]
;WAMPSTOPALLSTART
Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "stop all";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated ; 
Action: run; FileName: "${c_installDir}/scripts/CheckProcess.exe"; WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated 
Action: readconfig
;WAMPSTOPALLEND

[WampEject]
;WAMPEJECT
Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "stop all";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated ; 
;Action: run; FileName: "${c_apacheExe}"; Parameters: "${c_apacheServiceRemoveParams}"; ShowCmd: hidden; Flags: waituntilterminated
;Action: run; FileName: "${c_mysqlExe}"; Parameters: "${c_mysqlServiceRemoveParams}"; ShowCmd: hidden; Flags: waituntilterminated
Action:  exit
;WAMPEJECTEND

[RestartAll]
;WAMPRESTARTALLSTART
Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "restart all";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "${c_installDir}/scripts/CheckProcess.exe"; WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated 
Action: readconfig
;WAMPRESTARTALLEND

[myexit]
;WAMPMYEXITSTART
Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "stop all";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "${c_installDir}/scripts/CheckProcess.exe"; WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated 
;Action: readconfig ; not working....
Action:  exit
;WAMPMYEXITEND

[apache_mod]
Type: separator; Caption: "${w_apacheModules}"
;WAMPAPACHE_MODSTART
;WAMPAPACHE_MODEND


[submenu.tools]
;WAMPTOOLSSTART
Type: Separator; Caption: "${w_wampTools}"
;Type: item; Caption: "${w_restartDNS}"; Action: multi; Actions: DnscacheServiceRestart; Glyph: 24
Type: item; Caption: "${w_testConf}"; Action: run; FileName: "${c_phpExe}"; Parameters: "openCmd.php ${c_apacheExe} -t -w"; WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated; Glyph: 24
Type: item; Caption: "${w_dnsorder}"; Action: run; FileName: "${c_phpExe}"; Parameters: "msg.php dnsorder";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated; Glyph: 24
;Type: item; Caption: "${w_compilerVersions}"; Action: run; FileName: "${c_phpExe}"; Parameters: "openCmd.php ${c_phpExe} msg.php compilerversions";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated; Glyph: 24
Type: item; Caption: "${w_vhostConfig}"; Action: run; FileName: "${c_phpExe}"; Parameters: "msg.php vhostconfig";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated; Glyph: 24
Type: item; Caption: "${w_apacheLoadedModules}"; Action: run; FileName: "${c_phpExe}"; Parameters: "msg.php apachemodules";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated; Glyph: 24
;Type: item; Caption: "Gestionnaire des services/Services Control Panel"; Action: controlpanelservices; Glyph: 24
Type: submenu; Caption: "${w_deleteVer}"; Submenu: DeleteOldVersions; Glyph: 26
Type: separator; Caption: "${w_portUsed}${c_UsedPort}"
${TplListenPorts}Type: separator; Caption: "Listen ports: ${ListenPorts}"
;Type: item; Caption: "${w_AddListenPort}"; Action: multi; Actions: AddListenPort; Glyph: 24
;${TplListenPorts}Type: submenu; Caption: "${w_deleteListenPort}"; Submenu: DeleteListenPort; Glyph: 26
Type: item; Caption: "${w_testPort80}"; Action: run; FileName: "${c_phpExe}"; Parameters: "testPort.php @APACHE_PORT@ ${c_apacheService}";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated; Glyph: 24
;Type: item; Caption: "${w_AlternatePort}"; Action: multi; Actions: UseAlternatePort; Glyph: 24
${ApaTestPortUsed}Type: item; Caption: "${w_testPortUsed}${c_UsedPort}"; Action: run; FileName: "${c_phpExe}"; Parameters: "testPort.php ${c_UsedPort} ${c_apacheService}";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated; Glyph: 24
${SupportMySQL}Type: separator; Caption: "${w_portUsedMysql}${c_UsedMysqlPort}"
${SupportMySQL}Type: item; Caption: "${w_testPortMysql}"; Action: run; FileName: "${c_phpExe}"; Parameters: "testPort.php 3306 ${c_mysqlService}";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated; Glyph: 24
${MysqlTestPortUsed}Type: item; Caption: "${w_testPortMysqlUsed}${c_UsedMysqlPort}"; Action: run; FileName: "${c_phpExe}"; Parameters: "testPort.php ${c_UsedMysqlPort} ${c_mysqlService}";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated; Glyph: 24
;${SupportMySQL}Type: item; Caption: "${w_AlternateMysqlPort}"; Action: multi; Actions: UseAlternateMysqlPort; Glyph: 24
${SupportMariaDB}Type: separator; Caption: "${w_portUsedMaria}${c_UsedMariaPort}"
${SupportMariaDB}Type: item; Caption: "${w_testPortMaria}"; Action: run; FileName: "${c_phpExe}"; Parameters: "testPort.php 3306 ${c_mariadbService}";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated; Glyph: 24
${MariaTestPortUsed}Type: item; Caption: "${w_testPortMariaUsed}${c_UsedMariaPort}"; Action: run; FileName: "${c_phpExe}"; Parameters: "testPort.php ${c_UsedMariaPort} ${c_mariadbService}";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated; Glyph: 24
;${SupportMariaDB}Type: item; Caption: "${w_AlternateMariaPort}"; Action: multi; Actions: UseAlternateMariaPort; Glyph: 24
;${ItemChangeServiceNames}Type: separator; Caption: "Apache: ${c_apacheService} - MySQL: ${c_mysqlService}"
;${ItemChangeServiceNames}Type: item; Caption: "${w_changeServices}"; Action: multi; Actions: changeservicesnames; Glyph: 24
Type: separator; Caption: "${w_empty} logs"
Type: item; Caption: "${w_empty} ${w_phpLog}"; Action: run; FileName: "${c_phpExe}"; parameters: "msg.php refreshLogs ${c_installDir}/${logDir}php_error.log";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated; Glyph: 32
Type: item; Caption: "${w_empty} ${w_apacheErrorLog}"; Action: run; FileName: "${c_phpExe}"; parameters: "msg.php refreshLogs ${c_installDir}/${logDir}apache_error.log";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated; Glyph: 32
Type: item; Caption: "${w_empty} ${w_apacheAccessLog}"; Action: run; FileName: "${c_phpExe}"; parameters: "msg.php refreshLogs ${c_installDir}/${logDir}access.log";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated; Glyph: 32
${SupportMySQL}Type: item; Caption: "${w_empty} ${w_mysqlLog}"; Action: run; FileName: "${c_phpExe}"; parameters: "msg.php refreshLogs ${c_installDir}/${logDir}mysql.log";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated; Glyph: 32
${SupportMariaDB}Type: item; Caption: "${w_empty} ${w_mariadbLog}"; Action: run; FileName: "${c_phpExe}"; parameters: "msg.php refreshLogs ${c_installDir}/${logDir}mariadb.log";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated; Glyph: 32
Type: item; Caption: "${w_emptyAll} ${w_logFiles}"; Action: run; FileName: "${c_phpExe}"; parameters: "msg.php refreshLogs ${c_installDir}/${logDir}php_error.log ${c_installDir}/${logDir}apache_error.log ${c_installDir}/${logDir}access.log${EmptyMysqlLog}${EmptyMariaLog}";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated; Glyph: 32
;WAMPTOOLSEND

[DnscacheServiceRestart]
;WAMPDNSCACHESERVICESTART
Action: service; Service: ${c_apacheService}; ServiceAction: stop; Flags: ignoreerrors waituntilterminated
Action: run; Filename: "ipconfig"; Parameters: "/flushdns"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; Filename: "net"; Parameters: "stop dnscache"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; Filename: "net"; Parameters: "start dnscache"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "net"; Parameters: "start ${c_apacheService}"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "${c_phpCli}"; Parameters: "refresh.php"; WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated
Action: resetservices
Action: readconfig;
;WAMPDNSCACHESERVICEEND

[DeleteOldVersions]
;WAMPDELETEOLDVERSIONSSTART
;WAMPDELETEOLDVERSIONSEND


[onlineoffline]
;WAMPONLINEOFFLINESTART
Action: run; FileName: "${c_phpCli}";Parameters: "onlineOffline.php on";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated
Action: run; FileName: "${c_phpCli}";Parameters: "refresh.php";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated
Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "restart all";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "${c_installDir}/scripts/CheckProcess.exe"; WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated 
Action: readconfig;
;WAMPONLINEOFFLINEEND
EOTPL;

?>