<?php

require ('config.inc.php');
require ('wampserver.lib.php');


// Check PhpMyAdmin version
$phpmyadminVersion = '';
$phmyadOK = false;
if(file_exists($aliasDir.'phpmyadmin.conf')) {
	$phmyadOK = true;
	$myalias = @file_get_contents($aliasDir.'phpmyadmin.conf');
	//Alias /phpmyadmin "J:/wamp/apps/phpmyadmin4.7.3/"
if(preg_match('~^Alias\s*/phpmyadmin\s*".*apps/phpmyadmin([0-9\.]*)/"\s?$~m',$myalias,$matches) > 0 )
	$phpmyadminVersion = $matches[1];
}
// Check adminer version
$adminerVersion = '';
$adminerOK = false;
if(file_exists($aliasDir.'adminer.conf')) {
	$adminerOK = true;
	$myalias = @file_get_contents($aliasDir.'adminer.conf');
	//Alias /adminer "J:/wamp/apps/adminer4.3.1/"
if(preg_match('~^Alias\s*/adminer\s*".*apps/adminer([0-9\.]*)/"\s?$~m',$myalias,$matches) > 0 )
	$adminerVersion = $matches[1];
}


//End of verify files

// ************************
// language management
// Get current language
if (isset($wampConf['language']))
{
    $lang = $wampConf['language'];
}
else 
{
    $lang = $wampConf['defaultLanguage'];
}


// Load language file if exists
if (is_file($langDir.$lang.'.lang'))
    {
        require($langDir.$lang.'.lang');
    }
else
    {
        require($langDir.$wampConf['defaultLanguage'].'lang');
    }


// Load modules default language files
if ($handle = opendir($langDir.$modulesDir)) {
	while (false !== ($file = readdir($handle)))	{
    if ($file != "." && $file != ".." && preg_match('|_'.$wampConf['defaultLanguage'].'|',$file)) 
           include($langDir.$modulesDir.$file);
   }
   closedir($handle);
}

// Load modules current language files if exists
if ($handle = opendir($langDir.$modulesDir)) {
	while (false !== ($file = readdir($handle)))	{
       if ($file != "." && $file != ".." && preg_match('|_'.$lang.'|',$file)) 
           include($langDir.$modulesDir.$file);
   }
   closedir($handle);
}

//Check if language file does not content Offline or Online
if (strtolower($w_serverOffline) == "offline")
  $w_serverOffline .= ' ';
if (strtolower($w_serverOnline) == "online")
  $w_serverOnline .= ' ';

//Update string to use alternate port.
$w_AlternatePort = sprintf($w_UseAlternatePort, $c_UsedPort);
if($c_UsedPort == $c_DefaultPort) {
	$UrlPort = '';
	$w_newPort = "8080";
}
else {
	$UrlPort = ':'.$c_UsedPort;
	$w_newPort = "80";
}
//Update string if there are more than one Apache Listen ports
$c_listenPort = listen_ports();
$TplListenPorts = ';';
$ListenPorts = '';
$ListenPortsExists = false;
if(count($c_listenPort) > 1) {
	$ListenPorts = implode(" ",$c_listenPort);
	$TplListenPorts = '';
	$ListenPortsExists = true;
}
//Update string for add Listen Port
$w_addPort = 8081;
while(in_array($w_addPort, $c_listenPort)) {
	$w_addPort++;
}
$w_addPort = (string)$w_addPort;

//Update string to use alternate MySQL port.
$w_AlternateMysqlPort = sprintf($w_UseAlternatePort, $c_UsedMysqlPort);
if($c_UsedMysqlPort == $c_DefaultMysqlPort) {
	$w_newMysqlPort = "3310";
}
else {
	$w_newMysqlPort = "3307";
}

//Update string to use alternate MariaDB port.
$w_AlternateMariaPort = sprintf($w_UseAlternatePort, $c_UsedMariaPort);
if($c_UsedMariaPort == $c_DefaultMysqlPort) {
	$w_newMariaPort = "3309";
}
else {
	$w_newMariaPort = "3308";
}

// ************************
//Before to require wampmanager.tpl ($templateFile)
// we need to change some options, otherwise the variables are replaced by their content.
// Option to launch Homepage at startup
$RunAtStart = ($wampConf['HomepageAtStartup'] == 'on' ? '' : ';');
// Item menu Online / Offline
$ItemPutOnline = ($wampConf['MenuItemOnline'] == 'on' ? '' : ';');
// Item submenu Apache Check port used (if not 80)
$ApaTestPortUsed = ($wampConf['apacheUseOtherPort'] == 'on' ? '' : ';');
// Item Tools submenu Check MySQL port used (if not 3306)
$MysqlTestPortUsed = (($wampConf['SupportMySQL'] == 'on' && $wampConf['mysqlUseOtherPort'] == 'on') ? '' : ';');
// Item Tools submenu Check MariaDB port used (if not 3306)
$MariaTestPortUsed = (($wampConf['SupportMariaDB'] == 'on' && $wampConf['mariaUseOtherPort'] == 'on') ? '' : ';');
// Item Tools submenu Change the names of the services
$ItemChangeServiceNames = ($wampConf['ItemServicesNames'] == 'on' ? '' : ';');
$SupportMysqlAndMariaDB = (($wampConf['SupportMariaDB'] == 'on' && $wampConf['SupportMySQL'] == 'on') ? '' : ';');
$MariadbDefault = (($wampConf['SupportMySQL'] == 'on' && $wampConf['SupportMariaDB'] == 'on' && $wampConf['mariaPortUsed'] == $wampConf['mysqlDefaultPort']) ? '' : ';');
$MysqlDefault = (($wampConf['SupportMySQL'] == 'on' && $wampConf['SupportMariaDB'] == 'on' && $wampConf['mysqlPortUsed'] == $wampConf['mysqlDefaultPort']) ? '' : ';');
$DefaultDBMS = (empty($MariadbDefault) ? 'MariaDB' : 'MySQL');
// Show PhpMyAdmin in Wampmanager menu
$phmyadMenu = (($phmyadOK && $wampConf['ShowphmyadMenu'] == 'on') ? '' : ';');
// Show Adminer in Wampmanager menu
$adminerMenu = (($adminerOK && $wampConf['ShowadminerMenu'] == 'on') ? '' : ';');

//Check some values about Apache VirtualHost
$virtualHost = check_virtualhost(true);
//Option to show Edit httpd-vhosts.conf
$EditVhostConf  = (($virtualHost['include_vhosts'] === false || $virtualHost['vhosts_exist'] === false) ? ';' : '');
//Translated by in About
$w_translated_by = (isset($w_translated_by )) ? $w_translated_by : '';

//Items to run or not process
$MySQLStartProcess = (($wampConf['SupportMySQL'] == 'off') ? '' : ';');
$MariaStartProcess = (($wampConf['SupportMariaDB'] == 'off') ? '' : ';');
$MySQLStopProcess = (($wampConf['SupportMySQL'] == 'on') ? '' : ';');
$MariaStopProcess = (($wampConf['SupportMariaDB'] == 'on') ? '' : ';');

//Update MySQL and/or MariaDB my.ini file
//Replace # comment by ; to be compatible with parse_ini_file
//PHP 5.3.0 Hash marks (#) should no longer be used as comments and will throw a deprecation warning if used.
//PHP 7.0.0 Hash marks (#) are no longer recognized as comments.
// Option to support MySQL
$mysqlVersionList = listDir($c_mysqlVersionDir,'checkMysqlConf','mysql');
if($wampConf['SupportMySQL'] == 'on' && count($mysqlVersionList) > 0) {
	$SupportMySQL = '';
	$EmptyMysqlLog = ' '.$c_installDir.'/'.$logDir.'mysql.log';
	$newMysqlService = ' %MysqlService%';
	//Check Console prompt
	if($wampConf['mysqlUseConsolePrompt'] == 'on') {
		$mysqlConsolePromptUsed = $wampConf['mysqlConsolePrompt'];
		$mysqlConsolePromptChange = 'off';
	}
	else {
		$mysqlConsolePromptUsed = 'default';
		$mysqlConsolePromptChange = 'on';
	}
	$myIniContents = file_get_contents_dos($c_mysqlConfFile);
	$myIniContents = preg_replace('/^#(.*)$/m',';${1}',$myIniContents,-1,$count);
	if($count > 0) {
		$fp = fopen($c_mysqlConfFile,'w');
		fwrite($fp,$myIniContents);
		fclose($fp);
	}
	unset ($myIniContents);
}
else {
	$SupportMySQL = ';';
	$EmptyMysqlLog = '';
	$newMysqlService = '';
}

// Option to support MariaDB
$mariadbVersionList = listDir($c_mariadbVersionDir,'checkMariaDBConf','mariadb');
if($wampConf['SupportMariaDB'] == 'on' && count($mariadbVersionList) > 0) {
	$SupportMariaDB = '';
	$EmptyMariaLog = ' '.$c_installDir.'/'.$logDir.'mariadb.log';
	//Check Console prompt
	if($wampConf['mariadbUseConsolePrompt'] == 'on') {
		$mariadbConsolePromptUsed = $wampConf['mariadbConsolePrompt'];
		$mariadbConsolePromptChange = 'off';
	}
	else {
		$mariadbConsolePromptUsed = 'default';
		$mariadbConsolePromptChange = 'on';
	}

	$myIniContents = file_get_contents_dos($c_mariadbConfFile);
	$myIniContents = preg_replace('/^#(.*)$/m',';${1}',$myIniContents,-1,$count);
	if($count > 0) {
		$fp = fopen($c_mariadbConfFile,'w');
		fwrite($fp,$myIniContents);
		fclose($fp);
	}
	unset ($myIniContents);
}
else {
	$SupportMariaDB = ';';
	$EmptyMariaLog = '';
}
// Option if neither MySQL nor MariaDB
if($SupportMySQL == ';' && $SupportMariaDB == ';') {
	$noDBMS = true;
	$SupportDBMS = ';';
}
else {
	$noDBMS = false;
	$SupportDBMS = '';
}


//Warnings at the end if needed
$WarningsAtEnd = false;
$WarningMenu = ';WAMPMENULEFTEND
';
$WarningText = '';

//Warning if hosts file is not writable
if(!$c_hostsFile_writable) {
	$WarningsAtEnd = true;
	$WarningMenu .= 'Type: item; Caption: "hosts file not writable"; Glyph: 19; Action: multi; Actions: warning_hostnotwritable
';
	$message = "\r\nThe file C:\\Windows\\System32\\drivers\\etc\\hosts\r\nis not writable.\r\nIn order to create or modify VirtualHost,\r\nit is imperative to be able to write to the hosts file.\r\nCheck that your anti-virus allows to write the hosts file.\r\n";
	$message .= $WarningMsg;
	$WarningText .= '[warning_hostnotwritable]
Action: run; FileName: "'.$c_phpExe.'";Parameters: "msg.php 11 '.base64_encode($message).'";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
';
}

//Forum for help
$forum = ($lang == 'french') ? '1' : '2';

// Template file

require($templateFile);


// ************************
// management of online / offline mode
$c_OnOffLine = 'off';
if ($wampConf['status'] == 'online')
{
    $tpl = str_replace('images_off.bmp', 'images_on.bmp',$tpl);
    $tpl = str_replace($w_serverOffline, $w_serverOnline,$tpl);
    $tpl = str_replace('onlineOffline.php on', 'onlineOffline.php off', $tpl);
    $tpl = str_replace($w_putOnline,$w_putOffline,$tpl);
  $c_OnOffLine = 'on';
}




// ************************
// load menu with the available languages
if ($handle = opendir($langDir)) 
{
   while (false !== ($file = readdir($handle))) 
   {
       if ($file != "." && $file != ".." && preg_match('|\.lang|',$file)) 
       {
           if ($file == $lang.'.lang')
                $langList[$file] = 1;
           else
                $langList[$file] = 0;
       }
   }
   closedir($handle);
}

$langText = ";WAMPLANGUAGESTART
";
ksort($langList);
foreach ($langList as $langname=>$langstatus)
{
    $cleanLangName = str_replace('.lang','',$langname);
    if ($langList[$langname] == 1)
        $langText .= 'Type: item; Caption: "'.$cleanLangName.'"; Glyph: 13; Action: multi; Actions: lang_'.$cleanLangName.'
';
    else
        $langText .= 'Type: item; Caption: "'.$cleanLangName.'"; Action: multi; Actions: lang_'.$cleanLangName.'
';

}

foreach ($langList as $langname=>$langstatus)
{
    $cleanLangName = str_replace('.lang','',$langname);
    $langText .= '[lang_'.$cleanLangName.']
Action: run; FileName: "'.$c_phpCli.'";Parameters: "-c . changeLanguage.php '.$cleanLangName.'";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
Action: run; FileName: "'.$c_phpCli.'";Parameters: "-c . refresh.php";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
Action: run; FileName: "'.$c_installDir.'/scripts/CheckProcess.exe"; WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: readconfig; 
';
    
}

$tpl = str_replace(';WAMPLANGUAGESTART',$langText,$tpl);




// ************************
// Creating the PHP extensions menu

$myphpini = @file($c_phpConfFile) or die ("php.ini file not found");


//on recupere la conf courante
foreach($myphpini as $line) {
  $extMatch = array();
  if(preg_match('/^(;)?extension\s*=\s*"?([a-z0-9_]+)\.dll"?/i', $line, $extMatch)) {
    $ext_name = $extMatch[2];
    
    if($extMatch[1] == ';') {
      $ext[$ext_name] = '0';
    } else {
      $ext[$ext_name] = '1';
    }
  }
}

// on recupere la liste d'extensions presentes dans le répertoire ext
if ($handle = opendir($phpExtDir)) 
{
    
   while (false !== ($file = readdir($handle))) 
   {
    if ($file != "." && $file != ".." && strstr($file,'.dll')) 
       {
           $extDirContents[] = str_replace('.dll','',$file);
       }
   }
   closedir($handle);
}

// on croise les deux tableaux
foreach ($extDirContents as $extname)
{
    if (!array_key_exists($extname,$ext))
    {
        $ext[$extname] = '0';
    }
}

ksort($ext);



//we construct the corresponding menu
$extText = ';WAMPPHP_EXTSTART
';
foreach ($ext as $extname=>$extstatus)
{
    if ($ext[$extname] == 1)
        $extText .= 'Type: item; Caption: "'.$extname.'"; Glyph: 13; Action: multi; Actions: php_ext_'.$extname.'
';
    else
        $extText .= 'Type: item; Caption: "'.$extname.'"; Action: multi; Actions: php_ext_'.$extname.'
';

}

foreach ($ext as $extname=>$extstatus)
{
    if ($ext[$extname] == 1)
    $extText .= '[php_ext_'.$extname.']
Action: run; FileName: "'.$c_installDir.'/scripts/WampeeSrv.exe"; Parameters: "stop apache";  WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "'.$c_phpCli.'";Parameters: "-c . switchPhpExt.php '.$extname.' off";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
Action: run; FileName: "'.$c_phpCli.'";Parameters: "-c . refresh.php";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
Action: run; FileName: "'.$c_installDir.'/scripts/WampeeSrv.exe"; Parameters: "start apache";  WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "'.$c_installDir.'/scripts/CheckProcess.exe"; WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: readconfig;
';
    else
    $extText .= '[php_ext_'.$extname.']
Action: run; FileName: "'.$c_installDir.'/scripts/WampeeSrv.exe"; Parameters: "stop apache";  WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "'.$c_phpCli.'";Parameters: "-c . switchPhpExt.php '.$extname.' on";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
Action: run; FileName: "'.$c_phpCli.'";Parameters: "-c . refresh.php";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
Action: run; FileName: "'.$c_installDir.'/scripts/WampeeSrv.exe"; Parameters: "start apache";  WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "'.$c_installDir.'/scripts/CheckProcess.exe"; WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: readconfig;
';
    
}

$tpl = str_replace(';WAMPPHP_EXTSTART',$extText,$tpl);


// ************************
// Creating the PHP configuration menu

$myphpini = parse_ini_file($c_phpConfFile);


// on recupere les valeurs dans le php.ini
foreach($phpParams as $next_param_name=>$next_param_text)
{
    if (isset($myphpini[$next_param_text]))
    {
        if ($myphpini[$next_param_text] == 1)
        {
            $params_for_wampini[$next_param_name] = '1';
        }
        else
        {   
            $params_for_wampini[$next_param_name] = '0';
        }
    }
}



$phpConfText = ";WAMPPHP_PARAMSSTART
";
foreach ($params_for_wampini as $paramname=>$paramstatus)
{
    if ($params_for_wampini[$paramname] == 1)
        $phpConfText .= 'Type: item; Caption: "'.$paramname.'"; Glyph: 13; Action: multi; Actions: '.$phpParams[$paramname].'
';
    else
        $phpConfText .= 'Type: item; Caption: "'.$paramname.'"; Action: multi; Actions: '.$phpParams[$paramname].'
';

}

//$phpConfText .= 'Type: separator
//Type: submenu; Caption: "'.$w_phpExtensions.'"; SubMenu: php_ext;  Glyph: 3
//';

foreach ($params_for_wampini as $paramname=>$paramstatus)
{
    if ($params_for_wampini[$paramname] == 1)
    $phpConfText .= '['.$phpParams[$paramname].']
Action: run; FileName: "'.$c_installDir.'/scripts/WampeeSrv.exe"; Parameters: "stop apache";  WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "'.$c_phpCli.'";Parameters: "switchPhpParam.php '.$phpParams[$paramname].' off";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
Action: run; FileName: "'.$c_phpCli.'";Parameters: "-c . refresh.php";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
Action: run; FileName: "'.$c_installDir.'/scripts/WampeeSrv.exe"; Parameters: "start apache";  WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "'.$c_installDir.'/scripts/CheckProcess.exe"; WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: readconfig;
';
    else
    $phpConfText .= '['.$phpParams[$paramname].']
Action: run; FileName: "'.$c_installDir.'/scripts/WampeeSrv.exe"; Parameters: "stop apache";  WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "'.$c_phpCli.'";Parameters: "switchPhpParam.php '.$phpParams[$paramname].' on";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
Action: run; FileName: "'.$c_phpCli.'";Parameters: "-c . refresh.php";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
Action: run; FileName: "'.$c_installDir.'/scripts/WampeeSrv.exe"; Parameters: "start apache";  WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "'.$c_installDir.'/scripts/CheckProcess.exe"; WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: readconfig;
';
    
}

$tpl = str_replace(';WAMPPHP_PARAMSSTART',$phpConfText,$tpl);


// ************************
// modules Apache


$myhttpd = @file($c_apacheConfFile) or die ("httpd.conf file not found");

foreach($myhttpd as $line)
{
    if (preg_match('|^#LoadModule|',$line))
    {
        $mod_table = explode(' ', $line);
        $mod_name = $mod_table[1];
        $mod[$mod_name] = '0';
    }
    elseif (preg_match('|^LoadModule|',$line))
    {    
        $mod_table = explode(' ', $line);
        $mod_name = $mod_table[1];
        $mod[$mod_name] = '1';
    }
}

$httpdText = ";WAMPAPACHE_MODSTART
";

foreach ($mod as $modname=>$modstatus)
{
    if ($mod[$modname] == 1)
        $httpdText .= 'Type: item; Caption: "'.$modname.'"; Glyph: 13; Action: multi; Actions: apache_mod_'.$modname.'
';
    else
        $httpdText .= 'Type: item; Caption: "'.$modname.'"; Action: multi; Actions: apache_mod_'.$modname.'
';

}

foreach ($mod as $modname=>$modstatus)
{
    if ($mod[$modname] == 1)
    $httpdText .= '[apache_mod_'.$modname.']
	
Action: run; FileName: "'.$c_installDir.'/scripts/WampeeSrv.exe"; Parameters: "stop apache";  WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "'.$c_phpCli.'";Parameters: "switchApacheMod.php '.$modname.' on";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
Action: run; FileName: "'.$c_phpCli.'";Parameters: "-c . refresh.php";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
Action: run; FileName: "'.$c_installDir.'/scripts/WampeeSrv.exe"; Parameters: "start apache";  WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "'.$c_installDir.'/scripts/CheckProcess.exe"; WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: readconfig;
';
    else
    $httpdText .= '[apache_mod_'.$modname.']
Action: run; FileName: "'.$c_installDir.'/scripts/WampeeSrv.exe"; Parameters: "stop apache";  WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "'.$c_phpCli.'";Parameters: "switchApacheMod.php '.$modname.' off";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
Action: run; FileName: "'.$c_phpCli.'";Parameters: "-c . refresh.php";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
Action: run; FileName: "'.$c_installDir.'/scripts/WampeeSrv.exe"; Parameters: "start apache";  WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "'.$c_installDir.'/scripts/CheckProcess.exe"; WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: readconfig;
';
    
}

$tpl = str_replace(';WAMPAPACHE_MODSTART',$httpdText,$tpl);


// ************************
// alias Apache


if ($handle = opendir($aliasDir)) 
{
    
   while (false !== ($file = readdir($handle))) 
   {
    if ($file != "." && $file != ".." && strstr($file,'.conf')) 
       {
           $aliasDirContents[] = $file;
       }
   }
   closedir($handle);
}



$myreplace = $myreplacemenu = $mydeletemenu = '';
foreach ($aliasDirContents as $one_alias)
{

    $mypattern = ';WAMPADDALIAS';
    $newalias_dir = str_replace('.conf','',$one_alias);
    
    
    $alias_contents = @file_get_contents ($aliasDir.$one_alias);
    preg_match('|^Alias /'.$newalias_dir.'/ "(.+)"|',$alias_contents,$match);
    if (isset($match[1]))
        $newalias_dest = $match[1]; 
    else
        $newalias_dest = NULL;
    
    $myreplace .= 'Type: submenu; Caption: "http://localhost/'.$newalias_dir.'/"; SubMenu: alias_'.str_replace(' ','_',$newalias_dir).'; Glyph: 3
';

    $myreplacemenu .= '
[alias_'.str_replace(' ','_',$newalias_dir).']
Type: separator; Caption: "'.$newalias_dir.'"
Type: item; Caption: "Edit alias"; Glyph: 6; Action: multi; Actions: edit_'.str_replace(' ','_',$newalias_dir).'
Type: item; Caption: "Edit .htaccess"; Glyph: 6; Action: run; FileName: "notepad.exe"; parameters: "'.$newalias_dest.'.htaccess"
Type: item; Caption: "Delete alias"; Glyph: 6; Action: multi; Actions: delete_'.str_replace(' ','_',$newalias_dir).'
';

    $mydeletemenu .= '
[delete_'.str_replace(' ','_',$newalias_dir).']
Action: run; FileName: "'.$c_installDir.'/scripts/WampeeSrv.exe"; Parameters: "stop apache";  WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "'.$c_phpExe.'";Parameters: "-c . deleteAlias.php '.str_replace(' ','-whitespace-',$newalias_dir).'";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
Action: run; FileName: "'.$c_phpCli.'";Parameters: "refresh.php";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
Action: run; FileName: "'.$c_installDir.'/scripts/WampeeSrv.exe"; Parameters: "start apache";  WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "'.$c_installDir.'/scripts/CheckProcess.exe"; WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: readconfig;
[edit_'.str_replace(' ','_',$newalias_dir).']
Action: run; FileName: "notepad.exe"; parameters:"'.$c_installDir.'/alias/'.$newalias_dir.'.conf"; Flags: waituntilterminated
Action: run; FileName: "'.$c_installDir.'/scripts/WampeeSrv.exe"; Parameters: "restart apache";  WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
';

}


$tpl = str_replace($mypattern,$myreplace.$myreplacemenu.$mydeletemenu,$tpl);





// ************************
// Versions of PHP

$phpVersionList = listDir($c_phpVersionDir,'checkPhpConf', 'php');

$myPattern = ';WAMPPHPVERSIONSTART';
$myreplace = $myPattern."
";
$myreplacemenu = '';    
foreach ($phpVersionList as $onePhp)
{
    $phpGlyph = '';
    $onePhpVersion = str_ireplace('php','',$onePhp);
  	//it checks if the PHP is compatible with the current version of apache
  	unset($phpConf);
    include $c_phpVersionDir.'/php'.$onePhpVersion.'/'.$wampBinConfFiles;

    $apacheVersionTemp = $wampConf['apacheVersion'];
    while (!isset($phpConf['apache'][$apacheVersionTemp]) && $apacheVersionTemp != '')
    {
        $pos = strrpos($apacheVersionTemp,'.');
        $apacheVersionTemp = substr($apacheVersionTemp,0,$pos);
    }
    
  // PHP incompatible with the current version of apache
  $incompatiblePhp = 0;
  if (empty($apacheVersionTemp))
  {
    $incompatiblePhp = -1;
    $phpGlyph = '; Glyph: 19';
		$phpErrorMsg = "apacheVersion = empty in wampmanager.conf file";
  }
  elseif (empty($phpConf['apache'][$apacheVersionTemp]['LoadModuleFile']))
  {
    $incompatiblePhp = -2;
    $phpGlyph = '; Glyph: 19';
		$phpErrorMsg = "\$phpConf['apache']['".$apacheVersionTemp."']['LoadModuleFile'] does not exists or is empty in ".$c_phpVersionDir.'/php'.$onePhpVersion.'/'.$wampBinConfFiles;
  }
  elseif (!file_exists($c_phpVersionDir.'/php'.$onePhpVersion.'/'.$phpConf['apache'][$apacheVersionTemp]['LoadModuleFile']))
  {
    $incompatiblePhp = -3;
    $phpGlyph = '; Glyph: 19';
		$phpErrorMsg = $c_phpVersionDir.'/php'.$onePhpVersion.'/'.$phpConf['apache'][$apacheVersionTemp]['LoadModuleFile']." does not exists.";
  }
    
    if ($onePhpVersion === $wampConf['phpVersion'])
        $phpGlyph = '; Glyph: 13';
    
    $myreplace .= 'Type: item; Caption: "'.$onePhpVersion.'"; Action: multi; Actions:switchPhp'.$onePhpVersion.$phpGlyph.'
';
    if ($incompatiblePhp == 0)
    {
        $myreplacemenu .= '[switchPhp'.$onePhpVersion.']
Action: run; FileName: "'.$c_installDir.'/scripts/WampeeSrv.exe"; Parameters: "stop apache";  WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "'.$c_phpCli.'";Parameters: "switchPhpVersion.php '.$onePhpVersion.'";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
Action: run; FileName: "'.$c_phpCli.'";Parameters: "-c . refresh.php";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
Action: run; FileName: "'.$c_installDir.'/scripts/WampeeSrv.exe"; Parameters: "start apache";  WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "'.$c_installDir.'/scripts/CheckProcess.exe"; WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: readconfig;
';
    }
    else
    {
        $myreplacemenu .= '[switchPhp'.$onePhpVersion.']
Action: run; FileName: "'.$c_phpExe.'";Parameters: "msg.php 1 '.base64_encode($onePhpVersion).' '.base64_encode($phpErrorMsg).'";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
';
    }
    
}
$myreplace .= 'Type: separator;
Type: item; Caption: "Get more..."; Action: run; FileName: "'.$c_navigator.'"; Parameters: "http://www.wampserver.com/addons_php.php";
';

$tpl = str_replace($myPattern,$myreplace.$myreplacemenu,$tpl);


// ************************
// versions of Apache

$apacheVersionList = listDir($c_apacheVersionDir,'checkApacheConf', 'apache');
// Sort in versions number order
natcasesort($apacheVersionList);

$myPattern = ';WAMPAPACHEVERSIONSTART';
$myreplace = $myPattern."
";
$myreplacemenu = '';    

foreach ($apacheVersionList as $oneApache)
{
    $apacheGlyph = '';
    $oneApacheVersion = str_ireplace('apache','',$oneApache);
    

	//we check if Apache is compatible with the current version of PHP
  unset($phpConf);
    include $c_phpVersionDir.'/php'.$wampConf['phpVersion'].'/'.$wampBinConfFiles;
    $apacheVersionTemp = $oneApacheVersion;
    while (!isset($phpConf['apache'][$apacheVersionTemp]) && $apacheVersionTemp != '')
    {
        $pos = strrpos($apacheVersionTemp,'.');
        $apacheVersionTemp = substr($apacheVersionTemp,0,$pos);
    }
    
  // Apache incompatible with the current version of PHP
    $incompatibleApache = 0;
  if (empty($apacheVersionTemp))
  {
    $incompatibleApache = -1;
    $apacheGlyph = '; Glyph: 19';
		$apacheErrorMsg = "apacheVersion = empty in wampmanager.conf file";
  }
  elseif (!isset($phpConf['apache'][$apacheVersionTemp]['LoadModuleFile'])
      || empty($phpConf['apache'][$apacheVersionTemp]['LoadModuleFile']))
  {
    $incompatibleApache = -2;
    $apacheGlyph = '; Glyph: 19';
		$apacheErrorMsg = "\$phpConf['apache']['".$apacheVersionTemp."']['LoadModuleFile'] does not exists or is empty in ".$c_phpVersionDir.'/php'.$wampConf['phpVersion'].'/'.$wampBinConfFiles;
  }
  elseif (!file_exists($c_phpVersionDir.'/php'.$wampConf['phpVersion'].'/'.$phpConf['apache'][$apacheVersionTemp]['LoadModuleFile']))
  {
    $incompatibleApache = -3;
    $apacheGlyph = '; Glyph: 23';
		$apacheErrorMsg = $c_phpVersionDir.'/php'.$wampConf['phpVersion'].'/'.$phpConf['apache'][$apacheVersionTemp]['LoadModuleFile']." does not exists.".PHP_EOL.PHP_EOL."First switch on a version of PHP that contains ".$phpConf['apache'][$apacheVersionTemp]['LoadModuleFile']." file before you change to Apache version ".$oneApacheVersion.".";
  }

    
    
  unset($apacheConf);
    include $c_apacheVersionDir.'/apache'.$oneApacheVersion.'/'.$wampBinConfFiles;
    
    if ($oneApacheVersion === $wampConf['apacheVersion'])
        $apacheGlyph = '; Glyph: 13';
    
    $myreplace .= 'Type: item; Caption: "'.$oneApacheVersion.'"; Action: multi; Actions:switchApache'.$oneApacheVersion.$apacheGlyph.'
';


    if ($incompatibleApache == 0)
    {
        $myreplacemenu .= '[switchApache'.$oneApacheVersion.']
Action: run; FileName: "'.$c_installDir.'/scripts/WampeeSrv.exe"; Parameters: "stop apache";  WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "'.$c_apacheExe.'"; Parameters: "'.$c_apacheServiceRemoveParams.'"; ShowCmd: hidden; Flags: ignoreerrors waituntilterminated
Action: closeservices; Flags: ignoreerrors
Action: run; FileName: "'.$c_phpCli.'";Parameters: "switchApacheVersion.php '.$oneApacheVersion.'";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
Action: run; FileName: "'.$c_phpCli.'";Parameters: "switchPhpVersion.php '.$wampConf['phpVersion'].'";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
Action: run; FileName: "'.$c_apacheVersionDir.'/apache'.$oneApacheVersion.'/'.$apacheConf['apacheExeDir'].'/'.$apacheConf['apacheExeFile'].'"; Parameters: "'.$apacheConf['apacheServiceInstallParams'].'"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "'.$c_installDir.'/scripts/WampeeSrv.exe"; Parameters: "start apache";  WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "'.$c_phpCli.'";Parameters: "-c . refresh.php";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
Action: run; FileName: "'.$c_installDir.'/scripts/CheckProcess.exe"; WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: readconfig;
';
    }
    else
    {
        $myreplacemenu .= '[switchApache'.$oneApacheVersion.']
Action: run; FileName: "'.$c_phpExe.'";Parameters: "msg.php 2 '.base64_encode($oneApacheVersion).' '.base64_encode($apacheErrorMsg).'";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
';
    }
}
$myreplace .= 'Type: separator;
Type: item; Caption: "Get more..."; Action: run; FileName: "'.$c_navigator.'"; Parameters: "http://www.wampserver.com/addons_apache.php";
';

$tpl = str_replace($myPattern,$myreplace.$myreplacemenu,$tpl);

// *****************************
// DBMS menus
$myDBMSPattern = ';WAMPDBMSMENU';
$myDBMSreplace = $myDBMSPattern."
";
if($noDBMS) {
	$myDBMSreplace .= <<< EOF
Type: separator; Caption: "${w_noDBMS}"
Type: separator
EOF;
}
else {
	$myDBMSreplacearray = array();
	$DBMSdefault = 'none';
	// MySQL versions and settings
	if($wampConf['SupportMySQL'] == 'on') {
		$glyph = '38';
		if($c_UsedMysqlPort == $c_DefaultMysqlPort) {
			$glyph = '36';
			$DBMSdefault = 0;
		}
		$myDBMSreplacearray[] = <<< EOF
Type: submenu; Caption: "MySQL		${c_mysqlVersion}"; SubMenu: mysqlMenu; Glyph: ${glyph}

EOF;
		include 'refreshMySQL.php';
	}
	//MariaDB versions and settings
	if($wampConf['SupportMariaDB'] == 'on') {

		$glyph = '42';
		if($c_UsedMariaPort == $c_DefaultMysqlPort) {
			$glyph = '36';
			$DBMSdefault = 1;
		}
		$myDBMSreplacearray[] = <<< EOF
Type: submenu; Caption: "MariaDB		${c_mariadbVersion}"; SubMenu: mariadbMenu; Glyph: ${glyph}

EOF;
		include 'refreshMariadb.php';

	}
	if(count($myDBMSreplacearray) > 1 && $DBMSdefault == 1) {
		krsort($myDBMSreplacearray);
	}
	foreach($myDBMSreplacearray as $value)
		$myDBMSreplace .= $value;
}
$tpl = str_replace($myDBMSPattern,$myDBMSreplace,$tpl);

// ************************
// versions de MySQL
/*
$mysqlVersionList = listDir($c_mysqlVersionDir,'checkMysqlConf');

$myPattern = ';WAMPMYSQLVERSIONSTART';
$myreplace = $myPattern."
";
$myreplacemenu = '';    
foreach ($mysqlVersionList as $oneMysql)
{
    $oneMysqlVersion = str_ireplace('mysql','',$oneMysql);
    if (isset($mysqlConf))
        $mysqlConf = NULL;
    include $c_mysqlVersionDir.'/mysql'.$oneMysqlVersion.'/'.$wampBinConfFiles;
    if ($oneMysqlVersion === $wampConf['mysqlVersion'])
        $myreplace .= 'Type: item; Caption: "'.$oneMysqlVersion.'"; Action: multi; Actions:switchMysql'.$oneMysqlVersion.'; Glyph: 13
';
    else
        $myreplace .= 'Type: item; Caption: "'.$oneMysqlVersion.'"; Action: multi; Actions:switchMysql'.$oneMysqlVersion.'
';

    $myreplacemenu .= '[switchMysql'.$oneMysqlVersion.']
Action: run; FileName: "'.$c_installDir.'/scripts/WampeeSrv.exe"; Parameters: "stop mysql";  WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "'.$c_mysqlExe.'"; Parameters: "'.$c_mysqlServiceRemoveParams.'"; ShowCmd: hidden; Flags: ignoreerrors waituntilterminated

Action: run; FileName: "'.$c_phpCli.'";Parameters: "switchMysqlVersion.php '.$oneMysqlVersion.'";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated 
Action: run; FileName: "'.$c_mysqlVersionDir.'/mysql'.$oneMysqlVersion.'/'.$mysqlConf['mysqlExeDir'].'/'.$mysqlConf['mysqlExeFile'].'"; Parameters: "'.$mysqlConf['mysqlServiceInstallParams'].'"; ShowCmd: hidden; Flags: waituntilterminated
${SupportMySQL}Action: run; FileName: "'.$c_installDir.'/scripts/WampeeSrv.exe"; Parameters: "start mysql";  WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "'.$c_phpCli.'";Parameters: "-c . refresh.php";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
Action: run; FileName: "'.$c_installDir.'/scripts/CheckProcess.exe"; WorkingDir: "'.$c_installDir.'/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: readconfig;

';

}
$myreplace .= 'Type: separator;
Type: item; Caption: "Get more..."; Action: run; FileName: "'.$c_navigator.'"; Parameters: "http://www.wampserver.com/addons_mysql.php";
';


$tpl = str_replace($myPattern,$myreplace.$myreplacemenu,$tpl);
*/


// Port HTTP

$tpl = str_replace('@APACHE_PORT@',$c_UsedPort,$tpl);


// ***************
//Projects submenu
if($wampConf['ProjectSubMenu'] == "on")
{
	//Add item for submenu
	$myPattern = ';WAMPPROJECTSUBMENU';
	$myreplace = $myPattern."
";
	$myreplacesubmenu = 'Type: submenu; Caption: "'.$w_projectsSubMenu.'"; Submenu: myProjectsMenu; Glyph: 3
';
	$tpl = str_replace($myPattern,$myreplace.$myreplacesubmenu,$tpl);

	//Add submenu
	$myPattern = ';WAMPMENULEFTEND';
	$myreplace = $myPattern."
";
	$myreplacesubmenu = '

[myProjectsMenu]
;WAMPPROJECTMENUSTART
;WAMPPROJECTMENUEND

';
	$tpl = str_replace($myPattern,$myreplace.$myreplacesubmenu,$tpl);

	//Construct submenu
	$myPattern = ';WAMPPROJECTMENUSTART';
	$myreplace = $myPattern."
Type: separator; Caption: \"".$w_projectsSubMenu."\"
";
	// Place projects into submenu Hosts
	// Folder to ignore in projects
	$projectsListIgnore = $projectsListIgnore = array ('.','..','wampthemes','wamplangues');
	// List projects
	$myDir = $wwwDir;
	if(substr($myDir,-1) != "/")
		$myDir .= "/";
	$handle=opendir($myDir);
	$projectContents = array();
	while (($file = readdir($handle))!==false)
	{
		if (is_dir($myDir.$file) && !in_array($file,$projectsListIgnore))
			$projectContents[] = $file;
	}
	closedir($handle);

	$myreplacesubmenuProjects = '';
	if (count($projectContents) > 0)
	{
		for($i = 0 ; $i < count($projectContents) ; $i++)
		{ //Support de suppressLocalhost dans wampmanager.conf
			$myreplacesubmenuProjects .= 'Type: item; Caption: "'.$projectContents[$i].'"; Action: run; FileName: "'.$c_navigator.'"; Parameters: "';
			if($c_suppressLocalhost)
				 $myreplacesubmenuProjects .= $c_edge.'http://'.$projectContents[$i].$UrlPort.'/"';
			else
				$myreplacesubmenuProjects .= $c_edge.'http://localhost'.$UrlPort.'/'.$projectContents[$i].'/"';
			$myreplacesubmenuProjects .= '; Glyph: 5
';
		}
	}
	$tpl = str_replace($myPattern,$myreplace.$myreplacesubmenuProjects,$tpl);
}
// ************
//Submenu Alias
if($wampConf['AliasSubmenu'] == "on")
{
	//Add item for submenu
	$myPattern = ';WAMPALIASSUBMENU';
	$myreplace = $myPattern."
";
	$myreplacesubmenu = 'Type: submenu; Caption: "'.$w_aliasSubMenu.'"; Submenu: myAliasMenu; Glyph: 3
';
	$tpl = str_replace($myPattern,$myreplace.$myreplacesubmenu,$tpl);

	//Add submenu
	$myPattern = ';WAMPMENULEFTEND';
	$myreplace = $myPattern."
";
	$myreplacesubmenu = '

[myAliasMenu]
;WAMPALIASMENUSTART
;WAMPALIASMENUEND

';
	$tpl = str_replace($myPattern,$myreplace.$myreplacesubmenu,$tpl);

	//Construct submenu
	$myPattern = ';WAMPALIASMENUSTART';
	$myreplace = $myPattern."
Type: separator; Caption: \"".$w_aliasSubMenu."\"
";
	// Place projects into submenu Hosts
	// Folder to ignore in projects
	$AliasListIgnore = array ('.','..');
	// récupération des alias
	$AliasContents = array();
	if (is_dir($aliasDir)) {
    $handle=opendir($aliasDir);
    while (($file = readdir($handle))!==false) {
	    if (is_file($aliasDir.$file) && strstr($file, '.conf')) {
		    $AliasContents[] = str_replace('.conf','',$file);
	    }
    }
    closedir($handle);
	}
	$myreplacesubmenuAlias = '';
	if (count($AliasContents) > 0)	{
		foreach($AliasContents as $AliasValue) {
			$glyph = '5';
			if(strpos($AliasValue,'phpmyadmin') !== false || strpos($AliasValue,'adminer') !== false)
				$glyph = '28';
			$myreplacesubmenuAlias .= 'Type: item; Caption: "'.$AliasValue.'"; Action: run; FileName: "'.$c_navigator.'"; Parameters: "';
			$myreplacesubmenuAlias .= $c_edge.'http://localhost'.$UrlPort.'/'.$AliasValue.'/"; Glyph: '.$glyph.'
';
		}
		$tpl = str_replace($myPattern,$myreplace.$myreplacesubmenuAlias,$tpl);
	}
}
// ********************
//Submenu Virtual Hosts
if($wampConf['VirtualHostSubMenu'] == "on")
{
	//Add item for submenu
	$myPattern = ';WAMPVHOSTSUBMENU';
	$myreplace = $myPattern."
";
	$myreplacesubmenu = 'Type: submenu; Caption: "'.$w_virtualHostsSubMenu.'"; Submenu: myVhostsMenu; Glyph: 3
';
	$tpl = str_replace($myPattern,$myreplace.$myreplacesubmenu,$tpl);
	//Add submenu
	$myPattern = ';WAMPMENULEFTEND';
	$myreplace = $myPattern."
";
	$myreplacesubmenu = '

[myVhostsMenu]
;WAMPVHOSTMENUSTART
;WAMPVHOSTMENUEND

';
	$tpl = str_replace($myPattern,$myreplace.$myreplacesubmenu,$tpl);
	$myPattern = ';WAMPVHOSTMENUSTART';
	$myreplace = $myPattern."
Type: separator; Caption: \"".$w_virtualHostsSubMenu."\"
";
	$myreplacesubmenuVhosts = '';

	$virtualHost = check_virtualhost();

	//is Include conf/extra/httpd-vhosts.conf uncommented?
	if($virtualHost['include_vhosts'] === false) {
		$myreplacesubmenuVhosts .= 'Type: item; Caption: "Virtual Host ERROR"; Action: multi; Actions: server_not_included; Glyph: 21
';
    $myreplacesubmenuVhosts .= '[server_not_included]
Action: run; FileName: "'.$c_phpExe.'";Parameters: "msg.php 14";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
';
	}
	else
	{
		if($virtualHost['vhosts_exist'] === false) {
			$myreplacesubmenuVhosts .= 'Type: item; Caption: "Virtual Host ERROR"; Action: multi; Actions: server_not_exists; Glyph: 21
';
    	$myreplacesubmenuVhosts .= '[server_not_exists]
Action: run; FileName: "'.$c_phpExe.'";Parameters: "msg.php 15 '.base64_encode($virtualHost['vhosts_file']).'";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
';
		}
		else
		{
			$server_name = array();

			if($virtualHost['nb_Server'] > 0)
			{
				$nb_Server = $virtualHost['nb_Server'];
				$nb_Virtual = $virtualHost['nb_Virtual'];
				$nb_Document = $virtualHost['nb_Document'];
				$nb_Directory = $virtualHost['nb_Directory'];
				$nb_End_Directory = $virtualHost['nb_End_Directory'];

				$port_number = true;
				//Check number of <Directory equals to number of </Directory
				if($nb_End_Directory != $nb_Directory) {
					$value = "ServerName_Directory";
					$server_name[$value] = -2;
					$myreplacesubmenuVhosts .= 'Type: item; Caption: "'.$value.'"; Action: multi; Actions: server_'.$value.'; Glyph: 23
';
				}
				//Check number of DocumentRoot equals to number of ServerName
				if($nb_Document != $nb_Server) {
					$value = "ServerName_Document";
					$server_name[$value] = -7;
					$myreplacesubmenuVhosts .= 'Type: item; Caption: "'.$value.'"; Action: multi; Actions: server_'.$value.'; Glyph: 23
';
				}
				//Check validity of DocumentRoot
				$documentPathError = '';
				if($virtualHost['document'] === false) {
					foreach($virtualHost['documentPath'] as $value) {
						if($virtualHost['documentPathValid'][$value] === false) {
							$documentPathError = $value;
							break;
						}
					}
					$value = "DocumentRoot_error";
					$server_name[$value] = -8;
					$myreplacesubmenuVhosts .= 'Type: item; Caption: "'.$value.'"; Action: multi; Actions: server_'.$value.'; Glyph: 23
';
				}
				//Check validity of Directory Path
				$directoryPathError = '';
				if($virtualHost['directory'] === false) {
					foreach($virtualHost['directoryPath'] as $value) {
						if($virtualHost['directoryPathValid'][$value] === false) {
							$directoryPathError = $value;
							break;
						}
					}
					$value = "Directory_Path_error";
					$server_name[$value] = -9;
					$myreplacesubmenuVhosts .= 'Type: item; Caption: "'.$value.'"; Action: multi; Actions: server_'.$value.'; Glyph: 23
';
				}

				//Check number of <VirtualHost equals or > to number of ServerName
				if($nb_Server != $nb_Virtual && $wampConf['NotCheckDuplicate'] == 'off') {
					$value = "ServerName_Virtual";
					$server_name[$value] = -3;
					$port_number = false;
					$myreplacesubmenuVhosts .= 'Type: item; Caption: "'.$value.'"; Action: multi; Actions: server_'.$value.'; Glyph: 23
';
				}

				//Check number of port definition of <VirtualHost *:xx> equals to number of ServerName
				if($virtualHost['nb_Virtual_Port'] != $nb_Virtual) {
					$value = "VirtualHost_Port";
					$server_name[$value] = -4;
					$port_number = false;
					$myreplacesubmenuVhosts .= 'Type: item; Caption: "'.$value.'"; Action: multi; Actions: server_'.$value.'; Glyph: 23
';
				}
				//Check validity of port number
				if($port_number && $virtualHost['port_number'] === false) {
					$value = "VirtualHost_PortValue";
					$server_name[$value] = -5;
					$port_number = false;
					$myreplacesubmenuVhosts .= 'Type: item; Caption: "'.$value.'"; Action: multi; Actions: server_'.$value.'; Glyph: 23
';
				}
				//Check if duplicate ServerName
				if($virtualHost['nb_duplicate'] > 0) {
					$DuplicateNames = '';
					$value = "Duplicate_ServerName";
					$server_name[$value] = -10;
					foreach($virtualHost['duplicate'] as $NameValue)
						$DuplicateNames .= "\r\n\t".$NameValue;
					$myreplacesubmenuVhosts .= 'Type: item; Caption: "'.$value.'"; Action: multi; Actions: server_'.$value.'; Glyph: 23
';
				}

				foreach($virtualHost['ServerName'] as $key => $value) {
					if($virtualHost['ServerNameValid'][$value] === false) {
						$myreplacesubmenuVhosts .= 'Type: item; Caption: "'.$value.'"; Action: multi; Actions: server_'.$value.'; Glyph: 20
';
						$server_name[$value] = -1;
					}
					elseif($virtualHost['ServerNameValid'][$value] === true) {
						$UrlPortVH = ($virtualHost['ServerNamePort'][$value] != '80') ? ':'.$virtualHost['ServerNamePort'][$value] : '';
						if(!$virtualHost['port_listen'] && $virtualHost['ServerNamePortListen'][$value] !== true) {
							$myreplacesubmenuVhosts .= 'Type: item; Caption: "'.$value.'"; Action: multi; Actions: server_'.$value.'; Glyph: 23
';
							$server_name[$value] = -12;
						}
						elseif($virtualHost['ServerNameIp'][$value] !== false) {
							$vh_ip = $virtualHost['ServerNameIp'][$value];
							if($virtualHost['ServerNameIpValid'][$value] !== false) {
								$myreplacesubmenuVhosts .= 'Type: item; Caption: "'.$vh_ip.' ('.$value.')"; Action: run; FileName: "'.$c_navigator.'"; Parameters: "'.$c_edge.'http://'.$vh_ip.$UrlPortVH.'/"; Glyph: 5
';
								$server_name[$value] = 1;
							}
							else {
								$myreplacesubmenuVhosts .= 'Type: item; Caption: "'.$vh_ip.' ('.$value.')"; Action: multi; Actions: server_'.$value.'; Glyph: 20
';
								$server_name[$value] = -11;
							}
						}
						else {
							$glyph = '5';
							if($value == 'localhost')
								$glyph = '27';
							$myreplacesubmenuVhosts .= 'Type: item; Caption: "'.$value.'"; Action: run; FileName: "'.$c_navigator.'"; Parameters: "'.$c_edge.'http://'.$value.$UrlPortVH.'/"; Glyph: '.$glyph.'
';
							$server_name[$value] = 1;
						}
					}
					else {
						$myreplacesubmenuVhosts .= 'Type: item; Caption: "'.$value.'"; Action: multi; Actions: server_'.$value.'; Glyph: 20
';
						$server_name[$value] = -6;
					}
				} //End foreach
				$myreplacesubmenuVhosts .= 'Type: separator
Type: item; Caption: "'.$w_add_VirtualHost.'"; Action: run; FileName: "'.$c_navigator.'"; Parameters: "'.$c_edge.'http://localhost'.$UrlPort.'/add_vhost.php"; Glyph: 33
';
				foreach($server_name as $name=>$value) {
					if($server_name[$name] != 1) {
						if($server_name[$name] == -1) {
    					$myreplacesubmenuVhosts .= '[server_'.$name.']
Action: run; FileName: "'.$c_phpExe.'";Parameters: "msg.php 9 '.base64_encode($name).'";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
';
						}
						else {
							if($server_name[$name] == -2)
								$message = "In the httpd-vhosts.conf file:\r\n\r\n\tThe number of\r\n\r\n\t\t<Directory ...>\r\n\t\tis not equal to the number of\r\n\r\n\t\t</Directory>\r\n\r\nThey should be identical.";
							elseif($server_name[$name] == -3)
								$message = "In the httpd-vhosts.conf file:\r\n\r\n\tThe number of\r\n\r\n\t\t<VirtualHost ...>\r\n\tis not equal to the number of\r\n\r\n\t\tServerName\r\n\r\nThey should be identical.\r\n\r\n\tCorrect syntax is: <VirtualHost *:80>\r\n";
							elseif($server_name[$name] == -4)
								$message = "In the httpd-vhosts.conf file:\r\n\r\n\tPort number into <VirtualHost *:port>\r\n\tis not defined for all\r\n\r\n\t\t<VirtualHost...>\r\n\r\n\tCorrect syntax is: <VirtualHost *:xx>\r\n\r\n\t\twith xx = port number [80 by default]\r\n";
							elseif($server_name[$name] == -5)
								$message = "In the httpd-vhosts.conf file:\r\n\r\n\tPort number into <VirtualHost *:port>\r\n\thas not correct value\r\n\r\nValue are:".print_r($virtualHost['virtual_port'],true)."\r\n";
							elseif($server_name[$name] == -6)
								$message = "The httpd-vhosts.conf file has not been cleaned.\r\nThere remain VirtualHost examples like: dummy-host.example.com\r\n";
							elseif($server_name[$name] == -7)
								$message = "In the httpd-vhosts.conf file:\r\n\r\n\tThe number of\r\n\r\n\t\tDocumentRoot\r\n\tis not equal to the number of\r\n\r\n\t\tServerName\r\n\r\nThey should be identical.\r\n";
							elseif($server_name[$name] == -8)
								$message = "In the httpd-vhosts.conf file:\r\n\r\nThe DocumentRoot path\r\n\r\n\t".$documentPathError."\r\n\r\ndoes not exits\r\n";
							elseif($server_name[$name] == -9)
								$message = "In the httpd-vhosts.conf file:\r\n\r\nThe Directory path\r\n\r\n\t".$directoryPathError."\r\n\r\ndoes not exits\r\n";
							elseif($server_name[$name] == -10)
								$message = "In the httpd-vhosts.conf file:\r\n\r\nThere is duplicate ServerName\r\n".$DuplicateNames."\r\n";
							elseif($server_name[$name] == -11)
								$message = "In the httpd-vhosts.conf file:\r\n\r\nThe IP used for the VirtualHost is not valid local IP\r\n";
							elseif($server_name[$name] == -12)
								$message = "In the httpd-vhost.conf file:\r\n\r\nThe Port used (".$virtualHost['ServerNamePortListen'][$name].") for the VirtualHost ".$name." is not a Listen port\r\n";
    					$myreplacesubmenuVhosts .= '[server_'.$name.']
Action: run; FileName: "'.$c_phpExe.'";Parameters: "msg.php 11 '.base64_encode($message).'";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
';
						}
					}
				}
			}
		}
	}
	$tpl = str_replace($myPattern,$myreplace.$myreplacesubmenuVhosts,$tpl);
}


// *********************************
//Right submenu Wampmanager settings
foreach($wamp_Param as $value) {
  if (isset($wampConf[$value]))
  {
    $wampConfParams[$value] = $value;
    if ($wampConf[$value] == 'on')
      $params_for_wampconf[$value] = '1';
    elseif ($wampConf[$value] == 'off')
      $params_for_wampconf[$value] = '0';
    else
      $params_for_wampconf[$value] = '-1';
  }
  elseif(strpos($value, '##') !== false) { // Separator
  	$value = substr($value,2);
  	$params_for_wampconf[$value] = -2;
  }
  else {//Parameter does not exist in wampmanager.conf
    $params_for_wampconf[$value] = -1;
    $wampConfParams[$value] = $value;
  }
}
$wampConfActions = '';
$wampConfText = ";WAMPSETTINGSSTART
Type: Separator; Caption: \"".$w_wampSettings."\"
";
foreach ($params_for_wampconf as $paramname=>$paramstatus) {
  if ($params_for_wampconf[$paramname] == 1) {
    $wampConfText .= 'Type: item; Caption: "'.$w_settings[$paramname].'"; Glyph: 13; Action: multi; Actions: '.$wampConfParams[$paramname].'
';
		$SwitchAction = 'off';
	}
  elseif ($params_for_wampconf[$paramname] == 0) {
    $wampConfText .= 'Type: item; Caption: "'.$w_settings[$paramname].'"; Action: multi; Actions: '.$wampConfParams[$paramname].'
';
		$SwitchAction = 'on';
	}
  elseif ($params_for_wampconf[$paramname] == -1) {
    $wampConfText .= 'Type: item; Caption: "'.$w_settings[$paramname].'"; Action: multi; Actions: '.$wampConfParams[$paramname].' ;Glyph: 11;
';
		$SwitchAction = 'create off [options]';
	}
  if ($params_for_wampconf[$paramname] == -2) {
    $wampConfText .= 'Type: Separator; Caption: "'.$w_settings[$paramname].'"
';
	}
	else {
	$php_exe_type = (in_array($paramname,$wamp_ParamPhpExe)) ? $c_phpExe : $c_phpCli ;
  $wampConfActions .= <<< EOF
[${wampConfParams[$paramname]}]
;Action: service; Service: ${c_apacheService}; ServiceAction: stop; Flags: waituntilterminated
Action: run; FileName: "${php_exe_type}";Parameters: "switchWampParam.php ${wampConfParams[$paramname]} ${SwitchAction}";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated
Action: run; FileName: "${c_phpCli}";Parameters: "refresh.php";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated
Action: run; FileName: "net"; Parameters: "start ${c_apacheService}"; ShowCmd: hidden; Flags: waituntilterminated

EOF;

	if( $wampConfParams[$paramname] == "SupportMySQL")
	{
	$wampConfActions .= <<< EOF
${MySQLStopProcess}Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "stop mysql";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated
${MySQLStartProcess}Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "start mysql";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated

EOF;
	}
	if( $wampConfParams[$paramname] == "SupportMariaDB")
	{
	$wampConfActions .= <<< EOF
${MariaStopProcess}Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "stop mariadb";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated
${MariaStartProcess}Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "start mariadb";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated

EOF;
	}

	$wampConfActions .= <<< EOF
Action: run; FileName: "${c_installDir}/scripts/CheckProcess.exe"; WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: resetservices
Action: readconfig;

EOF;

}
}

$tpl = str_replace(';WAMPSETTINGSSTART',$wampConfText.$wampConfActions,$tpl);

// ************************
// Tool delete old versions
$delOldVer = ";WAMPDELETEOLDVERSIONSSTART
Type: separator; Caption: \"".$w_deleteVer."\"
";
$delOldVerMenu = $delOldVerSub = '';
$Versions = ListVersions();
foreach(array_keys($Versions) as $appli) {
	if(count($Versions[$appli]) > 0) {
		$delOldVerMenu .= "Type: separator; Caption: \" ".strtoupper($appli)." \"
";
		foreach ($Versions[$appli] as $appliVersion) {
  		$delOldVerMenu .= 'Type: item; Caption: "'.$w_delete.' '.$appli.' '.$appliVersion.'"; Glyph: 32; Action: multi; Actions: del_'.$appli.$appliVersion.'
';
		}
		foreach ($Versions[$appli] as $appliVersion) {
			$delOldVerSub .= <<< EOF
[del_${appli}${appliVersion}]
Action: run; FileName: "${c_phpCli}";Parameters: "deleteVersion.php ${appli} ${appliVersion}";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated
Action: run; FileName: "${c_phpCli}";Parameters: "refresh.php";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated
Action: resetservices
Action: readconfig;

EOF;
		}
	}
}
$tpl = str_replace(';WAMPDELETEOLDVERSIONSSTART',$delOldVer.$delOldVerMenu.$delOldVerSub,$tpl);


//*******************************
// Tool delete Listen Port Apache
if($ListenPortsExists) {
	$ForbidenToDel = array('80', '8080',$c_DefaultPort, $c_UsedPort);
	$delListenPort = ";WAMPDELETELISTENPORTSTART
Type: separator; Caption: \"".$w_deleteListenPort."\"
";
	$delListenPortMenu = $delListenPortSub = '';
	foreach($c_listenPort as $ListenPort) {
		if(!in_array($ListenPort,$ForbidenToDel)) {
 			$delListenPortMenu .= 'Type: item; Caption: "'.$w_delete.' Listen port Apache '.$ListenPort.'"; Glyph: 32; Action: multi; Actions: del_apache_port'.$ListenPort.'
';
			$delListenPortSub .= <<< EOF
[del_apache_port${ListenPort}]
Action: service; Service: ${c_apacheService}; ServiceAction: stop; Flags: ignoreerrors waituntilterminated
Action: run; FileName: "${c_phpCli}";Parameters: "ListenPortApache.php delete ${ListenPort}";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated
Action: service; Service: ${c_apacheService}; ServiceAction: startresume; Flags: ignoreerrors waituntilterminated
Action: run; FileName: "${c_phpCli}";Parameters: "refresh.php";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated
Action: resetservices
Action: readconfig;

EOF;
		}
	}
$tpl = str_replace(';WAMPDELETELISTENPORTSTART',$delListenPort.$delListenPortMenu.$delListenPortSub,$tpl);
}

//Add warnings if needed
if($WarningsAtEnd) {
	$WarningTextAll = '
Type: Separator;
';
	$tpl = str_replace(';WAMPMENULEFTEND',$WarningTextAll.$WarningMenu.$WarningText,$tpl);
}

// ************************
//The creation of wampmanager.ini file is complete, save the file.
$fp = fopen($wampserverIniFile,'w');
fwrite($fp,$tpl);
fclose($fp);

?>