<?php
// 3.1.0 Support of sql-mode - added $typebase = 'mariadb'; for sql-mode


//MariaDB submenu
$myPattern = ';WAMPMARIADBMENUSTART';
$myreplace = <<< EOF
;WAMPMARIADBMENUSTART
Type: submenu; Caption: "${w_version}"; SubMenu: mariadbVersion; Glyph: 3
;Type: servicesubmenu; Caption: "${w_service} '${c_mariadbService}'"; Service: ${c_mariadbService}; SubMenu: mariadbService
Type: submenu; Caption: "${w_mariaSettings}"; SubMenu: mariadb_params; Glyph: 25
Type: item; Caption: "${w_mariadbConsole}"; Action: run; FileName: "${c_mariadbConsole}";Parameters: "-u root -p"; Glyph: 0
Type: item; Caption: "my.ini"; Glyph: 6; Action: run; FileName: "${c_phpExe}"; Parameters: "openFiles.php ${c_mariadbConfFile}"; WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated;
Type: item; Caption: "${w_mariadbLog}"; Glyph: 6; Action: run; FileName: "${c_phpExe}"; Parameters: "openFiles.php ${c_installDir}/${logDir}mariadb.log"; WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated;
${MariaTestPortUsed}Type: separator; Caption: "${w_portUsedMaria}${c_UsedMariaPort}"
${MariaTestPortUsed}Type: item; Caption: "${w_testPortMaria}"; Action: run; FileName: "${c_phpExe}"; Parameters: "testPort.php 3306 ${c_mariadbService}";WorkingDir: "$c_installDir/scripts"; Flags: waituntilterminated; Glyph: 24
;${MariaTestPortUsed}Type: item; Caption: "${w_AlternateMariaPort}"; Action: multi; Actions: UseAlternateMariaPort; Glyph: 24
${MariaTestPortUsed}Type: item; Caption: "${w_testPortMariaUsed}${c_UsedMariaPort}"; Action: run; FileName: "${c_phpExe}"; Parameters: "testPort.php ${c_UsedMariaPort} ${c_mariadbService}";WorkingDir: "$c_installDir/scripts"; Flags: waituntilterminated; Glyph: 24
Type: item; Caption: "${w_mariadbDoc}"; Action: run; FileName: "${c_navigator}"; Parameters: "${c_edge}http://mariadb.com/kb/en/mariadb/documentation"; Glyph: 35
EOF;
$tpl = str_replace($myPattern,$myreplace,$tpl);

// ************************
// Versions of MariaDB
$mariadbVersionList = listDir($c_mariadbVersionDir,'checkMariaDBConf','mariadb');
// Sort in versions number order
natcasesort($mysqlVersionList);

if(count($mariadbVersionList) == 0) {
	error_log("No version of MariaDB is installed.");
	$glyph = '19';
	$WarningsAtEnd = true;
	if(!isset($WarningMariadb)) {
		$WarningMariadb = true;
		$WarningText .= 'Type: separator; Caption: "Warning MariaDB"
';
	}
	$WarningText .= 'Type: item; Caption: "No version of MariaDB is installed"; Glyph: '.$glyph.'; Action: multi; Actions: none
';

}
else {
$maPattern = ';WAMPMARIADBVERSIONSTART';
$mareplace = $maPattern."
";
$mareplacemenu = '';
foreach ($mariadbVersionList as $oneMariaDB) {
  $oneMariaDBVersion = str_ireplace('mariadb','',$oneMariaDB);
  if(isset($mariadbConf))
    $mariadbConf = array();
  include $c_mariadbVersionDir.'/mariadb'.$oneMariaDBVersion.'/'.$wampBinConfFiles;
  if ($oneMariaDBVersion === $wampConf['mariadbVersion']) {
    $mareplace .= 'Type: item; Caption: "'.$oneMariaDBVersion.'"; Action: multi; Actions:switchMariaDB'.$oneMariaDBVersion.'; Glyph: 13
';
	}
  else {
    $mareplace .= 'Type: item; Caption: "'.$oneMariaDBVersion.'"; Action: multi; Actions:switchMariaDB'.$oneMariaDBVersion.'
';
    $mareplacemenu .= <<< EOF
[switchMariaDB${oneMariaDBVersion}]
;Action: service; Service: ${c_mariadbService}; ServiceAction: stop; Flags: ignoreerrors waituntilterminated
Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "stop mariadb";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "${c_mariadbExe}"; Parameters: "${c_mariadbServiceRemoveParams}"; ShowCmd: hidden; Flags: ignoreerrors waituntilterminated
;Action: closeservices;
Action: run; FileName: "{$c_phpCli}";Parameters: "switchMariaDBVersion.php ${oneMariaDBVersion}";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated
${SupportMariaDB}Action: run; FileName: "${c_mariadbVersionDir}/mariadb${oneMariaDBVersion}/${mariadbConf['mariadbExeDir']}/${mariadbConf['mariadbExeFile']}"; Parameters: "${mariadbConf['mariadbServiceInstallParams']}"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "{$c_phpCli}";Parameters: "switchMariaPort.php ${c_UsedMariaPort}";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated
;Action: run; FileName: "net"; Parameters: "start ${c_mariadbService}"; ShowCmd: hidden; Flags: waituntilterminated
${SupportMariaDB}Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "start mariadb";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "${c_phpCli}";Parameters: "-c . refresh.php";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated
Action: run; FileName: "${c_installDir}/scripts/CheckProcess.exe"; WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: resetservices;
Action: readconfig;

EOF;
	}
}

$tpl = str_replace($maPattern,$mareplace.$mareplacemenu,$tpl);

// Configuration of MariaDB
// It only retrieves the values of the [wampmariadb] or [wampmariadb64] section
$mariadbiniS = parse_ini_file($c_mariadbConfFile, true);
$mariadbini = $mariadbiniS[$c_mariadbService];

//Check if default sql-mode
if(!array_key_exists('sql-mode', $mariadbini))
	$mariadbini = $mariadbini + array('sql-mode' => 'default');

$myIniFileContents = @file_get_contents($c_mariadbConfFile) or die ("my.ini file not found");
//Check if there is a commented or not user sql-mode
$UserSqlMode = (preg_match('/^[;]?sql-mode[ \t]*=[ \t]*"[^"].*$/m',$myIniFileContents) > 0 ? true : false);
//Check if skip-grant-tables is on (uncommented)
if(preg_match('/^skip-grant-tables[\r]?$/m',$myIniFileContents) > 0) {
	$mariadbini = $mariadbini + array('skip-grant-tables' => 'On - !! WARNING !!');
}
unset($myIniFileContents);

$mariadbErrorMsg = array();
$mariadbParams = array_combine($mariadbParams,$mariadbParams);
foreach($mariadbParams as $next_param_name=>$next_param_text)
{
  if (isset($mariadbini[$next_param_text]))
  {
  	if(array_key_exists($next_param_name, $mariadbParamsNotOnOff)) {
  		if($mariadbParamsNotOnOff[$next_param_name]['change'] !== true) {
  	  	$params_for_mariadb[$next_param_name] = -2;
  	  	if(!empty($mariadbParamsNotOnOff[$next_param_name]['msg']))
  	  		$mariadbErrorMsg[$next_param_name] = "\n".$mariadbParamsNotOnOff[$next_param_name]['msg']."\n";
   	  	else
					$mariadbErrorMsg[$next_param_name] = "\nThe value of this MariaDB parameter must be modified in the file:\n".$c_mariadbConfFile."\nNot to change the wrong file, the best way to access this file is:\nWampmanager icon->MariaDB->my.ini\n";
  		}
  		else {
  	  	$params_for_mariadb[$next_param_name] = -3;
  	  	if($mariadbParamsNotOnOff[$next_param_name]['title'] == 'Special')
  	  		$params_for_mariadb[$next_param_name] = -4;
  		}
  	}
  	elseif($mariadbini[$next_param_text] == "Off")
  		$params_for_mariadb[$next_param_name] = '0';
  	elseif($mariadbini[$next_param_text] == 0)
  		$params_for_mariadb[$next_param_name] = '0';
  	elseif($mariadbini[$next_param_text] == "On")
  		$params_for_mariadb[$next_param_name] = '1';
  	elseif($mariadbini[$next_param_text] == 1)
  		$params_for_mariadb[$next_param_name] = '1';
  	else
  	  $params_for_mariadb[$next_param_name] = -2;
  }
  else //Parameter in $mariadbParams (config.inc.php) does not exist in my.ini
    $params_for_mariadb[$next_param_name] = -1;
}

$mariadbConfText = ";WAMPMARIADB_PARAMSSTART
";
$mariadbConfTextInfo = $mariadbConfForInfo = "";
$action_sup = array();
$information_only = false;
foreach ($params_for_mariadb as $paramname=>$paramstatus)
{
	if($params_for_mariadb[$paramname] == 0 || $params_for_mariadb[$paramname] == 1) {
		$glyph = ($params_for_mariadb[$paramname] == 1 ? '13' : '22');
    $mariadbConfText .= 'Type: item; Caption: "'.$paramname.'"; Glyph: '.$glyph.'; Action: multi; Actions: maria_'.$mariadbParams[$paramname].'
';
	}
	elseif ($params_for_mariadb[$paramname] == -2) { // I blue to indicate different from 0 or 1 or On or Off
		if(!$information_only) {
			$mariadbConfForInfo .= 'Type: separator; Caption: "'.$w_phpparam_info.'"
';
			$information_only = true;
		}
		$glyph = '22';
		if($paramname == 'skip-grant-tables') {
			$glyph = '19';
			$WarningsAtEnd = true;
			if(!isset($WarningMariadb)) {
				$WarningMariadb = true;
				$WarningText .= 'Type: separator; Caption: "Warning MariaDB"
';
			}
			$WarningText .= 'Type: item; Caption: "'.$paramname.' = '.$mariadbini[$paramname].'"; Glyph: '.$glyph.'; Action: multi; Actions: maria_'.$mariadbParams[$paramname].'
';
		}
     $mariadbConfForInfo .= 'Type: item; Caption: "'.$paramname.' = '.$mariadbini[$paramname].'"; Glyph: '.$glyph.'; Action: multi; Actions: maria_'.$mariadbParams[$paramname].'
';
	}
	elseif ($params_for_mariadb[$paramname] == -3) { // Indicate different from 0 or 1 or On or Off but can be changed
		$action_sup[] = $paramname;
		$text = ($mariadbParamsNotOnOff[$paramname]['title'] == 'Number' ? ' - '.$mariadbParamsNotOnOff[$paramname]['text'][$mariadbini[$paramname]] : '');
		$mariadbConfText .= 'Type: submenu; Caption: "'.$paramname.' = '.$mariadbini[$paramname].$text.'"; Submenu: maria_'.$paramname.'; Glyph: 9
';
	}
	elseif ($params_for_mariadb[$paramname] == -4) { // Indicate different from 0 or 1 or On or Off but can be changed with Special treatment
		$action_sup[] = $paramname;
		if($paramname == 'sql-mode') {
			$typebase = 'mariadb';
			$mariadbConfTextMode = '';
			$default_modes = array(
				'10.1' => array('NONE'),
				'10.2.3' => array('NO_ENGINE_SUBSTITUTION','NO_AUTO_CREATE_USER'),
				'10.2.4' => array('NO_ENGINE_SUBSTITUTION','STRICT_TRANS_TABLES','ERROR_FOR_DIVISION_BY_ZERO','NO_AUTO_CREATE_USER'),
				'valid' => array('ALLOW_INVALID_DATES','ANSI','ANSI_QUOTES','DB2','ERROR_FOR_DIVISION_BY_ZERO','HIGH_NOT_PRECEDENCE','IGNORE_BAD_TABLE_OPTIONS','IGNORE_SPACE','MAXDB','MSSQL','MYSQL323','MYSQL40','NO_AUTO_CREATE_USER','NO_AUTO_VALUE_ON_ZERO','NO_BACKSLASH_ESCAPES','NO_DIR_IN_CREATE','NO_ENGINE_SUBSTITUTION','NO_FIELD_OPTIONS','NO_KEY_OPTIONS','NO_TABLE_OPTIONS','NO_UNSIGNED_SUBTRACTION','NO_ZERO_DATE','NO_ZERO_IN_DATE','ONLY_FULL_GROUP_BY','ORACLE','PAD_CHAR_TO_FULL_LENGTH','PIPES_AS_CONCAT','POSTGRESQL','REAL_AS_FLOAT','STRICT_ALL_TABLES','STRICT_TRANS_TABLES','TRADITIONAL'),
				);
				//Memorize default values
				if(version_compare($c_mariadbVersion, '10.2', '<'))
					$default_valeurs = $default_modes['10.1'];
				elseif(version_compare($c_mariadbVersion, '10.2.4', '>='))
					$default_valeurs = $default_modes['10.2.4'];
				else
					$default_valeurs = $default_modes['10.2.3'];

			if(empty($mariadbini['sql-mode'])) {
				$valeurs[0] = 'NONE';
				$m_valeur = 'none';
				$mariadbini['sql-mode'] = 'none';
      	$mariadbConfTextInfo .= 'Type: separator; Caption: "sql-mode: '.$w_mysql_none.'"
';
				$mariadbConfTextMode = 'Type: submenu; Caption: "'.$paramname.'"; Submenu: '.$paramname.$typebase.'; Glyph: 9
';
			}
			elseif($mariadbini['sql-mode'] == 'default') {
				$valeurs = $default_valeurs;
      	$mariadbConfTextInfo .= 'Type: separator; Caption: "sql-mode:  '.$w_mysql_default.'"
';
				foreach($valeurs as $val) {
					$mariadbConfTextInfo .= 'Type: item; Caption: "'.$val.'"; Action: multi; Actions: none
';
				}
				$m_valeur = 'default';
				$mariadbConfTextMode = 'Type: submenu; Caption: "'.$paramname.'"; Submenu: '.$paramname.$typebase.'; Glyph: 9
';
			}
			else {
				$valeurs = explode(',',$mariadbini['sql-mode']);
				$valeurs = array_map('trim',$valeurs);
     		$mariadbConfTextInfo .= 'Type: separator; Caption: "sql-mode: '.$w_mysql_user.'"
';
				$MyUserError = false;
				foreach($valeurs as $val) {
					//Check if each user value is allowed
					if(in_array($val,$default_modes['valid'])) {
						$UserGlyph = '';
						$notValid = '';
					}
					else {
						$MyUserError = true;
						$UserGlyph = '; Glyph: 19';
						$notValid = ' - Not valid mode';
					}
					$mariadbConfTextInfo .= 'Type: item; Caption: "'.$val.$notValid.'"; Action: multi; Actions: none'.$UserGlyph.'
';
				}
				$m_valeur = 'user';
				$mariadbini['sql-mode'] = 'user';
				$mariadbConfTextMode = 'Type: submenu; Caption: "'.$paramname.'"; Submenu: '.$paramname.$typebase.'; Glyph: 9
';
			}
			$mariadbConfTextInfo .= $mariadbConfTextMode;
		}
		else {
			$mariadbConfText .= 'Type: submenu; Caption: "'.$paramname.' = '.$mariadbini[$paramname].'"; Submenu: maria_'.$paramname.$typebase.'; Glyph: 9
';
		}
	}
}
//Check for supplemtary actions
$MenuSup = $SubMenuSup = array();
if(count($action_sup) > 0) {
	$i = 0;
	foreach($action_sup as $action) {
		$MenuSup[$i] = $SubMenuSup[$i] = '';
		if($mariadbParamsNotOnOff[$action]['title'] == 'Special') {
			if($action == 'sql-mode') {
				$actionToDo = $actionName = $param_value = array();
				if($mariadbini['sql-mode'] == 'default') {
					if($UserSqlMode) {
						$actionToDo[] = 'user';
						$actionName[] = $w_mysql_user;
						$param_value[] = 'user';
					}
					$actionToDo[] = 'none';
					$actionName[] = $w_mysql_none;
					$param_value[] = 'none';
				}
				elseif($mariadbini['sql-mode'] == 'none') {
					if($UserSqlMode) {
						$actionToDo[] = 'user';
						$actionName[] = $w_mysql_user;
						$param_value[] = 'user';
					}
					$actionToDo[] = 'default';
					$actionName[] = $w_mysql_default;
					$param_value[] = 'default';
				}
				if($mariadbini['sql-mode'] == 'user') {
					$actionToDo[] = 'none';
					$actionName[] = $w_mysql_none;
					$param_value[] = 'none';
					$actionToDo[] = 'default';
					$actionName[] = $w_mysql_default;
					$param_value[] = 'default';
				}
				$MenuSup[$i] .= '[sql-mode'.$typebase.']
Type: separator; Caption: "sql-mode"
';
				for($j = 0 ; $j < count($actionToDo) ; $j++) {
					if($actionToDo[$j] == 'default') {
						$MenuSup[$i] .= <<< EOF

Type: separator; Caption: "MariaDB ${c_mariadbVersion}"
Type: separator; Caption: "sql-mode ${actionName[$j]} = "

EOF;
						foreach($default_valeurs as $val) {
						$MenuSup[$i] .= 'Type: item; Caption: "'.$val.'"; Action: multi; Actions: none
';
						}
						$MenuSup[$i] .= 'Type: separator
';
					}
				$MenuSup[$i] .= 'Type: item; Caption: "sql-mode -> '.$actionName[$j].'"; Action: multi; Actions: '.$action.$actionToDo[$j].$typebase.'
';
					$SubMenuSup[$i] .= <<< EOF
[${action}${actionToDo[$j]}${typebase}]
;Action: service; Service: ${c_mariadbService}; ServiceAction: stop; Flags: waituntilterminated
Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "stop mariadb";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "${c_phpExe}";Parameters: "changeMariadbParam.php noquotes ${action} ${param_value[$j]}";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated
Action: run; FileName: "${c_phpCli}";Parameters: "refresh.php";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated
;Action: run; FileName: "net"; Parameters: "start ${c_mariadbService}"; ShowCmd: hidden; Flags: waituntilterminated
${SupportMariaDB}Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "start mariadb";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "${c_installDir}/scripts/CheckProcess.exe"; WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: resetservices
Action: readconfig;

EOF;
				}
			}
		}
		else {
			$MenuSup[$i] .= '[maria_'.$action.']
Type: separator; Caption: "'.$mariadbParamsNotOnOff[$action]['title'].'"
';
			$c_values = $mariadbParamsNotOnOff[$action]['values'];
			if($mariadbParamsNotOnOff[$action]['quoted'])
				$quoted = 'quotes';
			else
				$quoted = 'noquotes';
			foreach($c_values as $value) {
				$text = ($mariadbParamsNotOnOff[$action]['title'] == 'Number' ? " - ".$mariadbParamsNotOnOff[$action]['text'][$value] : "");
				$MenuSup[$i] .= 'Type: item; Caption: "'.$value.$text.'"; Action: multi; Actions: maria_'.$action.$value.'
';
				if(strtolower($value) == 'choose') {
					$param_value = '%'.$mariadbParamsNotOnOff[$action]['title'].'%';
					$param_third = ' '.$mariadbParamsNotOnOff[$action]['title'];
					$c_phpRun = $c_phpExe;
				}
				else {
					$param_value = $value;
					$param_third = '';
					$c_phpRun = $c_phpCli;
				}
				$SubMenuSup[$i] .= <<< EOF
[maria_${action}${value}]
;Action: service; Service: ${c_mariadbService}; ServiceAction: stop; Flags: waituntilterminated
Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "stop mariadb";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "${c_phpRun}";Parameters: "changeMariadbParam.php ${quoted} ${action} ${param_value}${param_third}";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated
Action: run; FileName: "${c_phpCli}";Parameters: "refresh.php";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated
;Action: run; FileName: "net"; Parameters: "start ${c_mariadbService}"; ShowCmd: hidden; Flags: waituntilterminated
${SupportMariaDB}Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "start mariadb";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "${c_installDir}/scripts/CheckProcess.exe"; WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: resetservices
Action: readconfig;

EOF;
			}
		}
	$i++;
	}
}
$mariadbConfText .= $mariadbConfTextInfo.$mariadbConfForInfo;

foreach ($params_for_mariadb as $paramname=>$paramstatus) {
	if ($params_for_mariadb[$paramname] == 1 || $params_for_mariadb[$paramname] == 0) {
		$SwitchAction = ($params_for_mariadb[$paramname] == 1 ? 'off' : 'on');
  	$mariadbConfText .= <<< EOF
[maria_${mariadbParams[$paramname]}]
;Action: service; Service: ${c_mariadbService}; ServiceAction: stop; Flags: waituntilterminated
Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "stop mariadb";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "${c_phpCli}";Parameters: "switchMariadbParam.php ${mariadbParams[$paramname]} ${SwitchAction}";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated
Action: run; FileName: "${c_phpCli}";Parameters: "refresh.php";WorkingDir: "${c_installDir}/scripts"; Flags: waituntilterminated
;Action: run; FileName: "net"; Parameters: "start ${c_mariadbService}"; ShowCmd: hidden; Flags: waituntilterminated
${SupportMariaDB}Action: run; FileName: "${c_installDir}/scripts/WampeeSrv.exe"; Parameters: "start mariadb";  WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "${c_installDir}/scripts/CheckProcess.exe"; WorkingDir: "${c_installDir}/scripts"; ShowCmd: hidden; Flags: waituntilterminated
Action: resetservices
Action: readconfig;

EOF;
	}
  elseif ($params_for_mariadb[$paramname] == -2)  {//Parameter is neither 'on' nor 'off'
  	$mariadbConfText .= '[maria_'.$mariadbParams[$paramname].']
Action: run; FileName: "'.$c_phpExe.'";Parameters: "msg.php 6 '.base64_encode($paramname).' '.base64_encode($mariadbErrorMsg[$paramname]).'";WorkingDir: "'.$c_installDir.'/scripts"; Flags: waituntilterminated
';
	}
}
if(count($MenuSup) > 0) {
	for($i = 0 ; $i < count($MenuSup); $i++)
		$mariadbConfText .= $MenuSup[$i].$SubMenuSup[$i];
}

$tpl = str_replace(';WAMPMARIADB_PARAMSSTART',$mariadbConfText,$tpl);
}

?>