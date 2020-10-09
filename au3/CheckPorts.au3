#NoTrayIcon
#RequireAdmin
#Region ;**** Directives created by AutoIt3Wrapper_GUI ****
#AutoIt3Wrapper_Icon=wampserver.ico
#AutoIt3Wrapper_Outfile=checkports.exe
#AutoIt3Wrapper_AU3Check_Stop_OnWarning=y
#AutoIt3Wrapper_Run_Tidy=y
#EndRegion ;**** Directives created by AutoIt3Wrapper_GUI ****
#include <ButtonConstants.au3>
#include <EditConstants.au3>
#include <GUIConstantsEx.au3>
#include <StaticConstants.au3>
#include <WindowsConstants.au3>
#include <UpdownConstants.au3>
#include <file.au3>
#cs ----------------------------------------------------------------------------

	AutoIt Version: 3.3.5.6 (beta)
	Author:         Herve Leclerc (herve.leclerc@alterway.fr)

#ce ----------------------------------------------------------------------------

Opt("GUIOnEventMode", 1)
Global $wampserverGUI
Global $ef_apache
Global $ef_mysql
Global $ef_mariadb
Global $bu_ok
Global $bu_test
Global $g_IP = "127.0.0.1"
Global $Inifile
Global $WampeeInifile
Global $noGood
Global $php_exe
Global $service_apache
Global $service_mysql
Global $service_mariadb


TCPStartup()

$Inifile = @ScriptDir & "\..\resources\wampmanager.conf"
$WampeeInifile = _PathFull(@ScriptDir & "\..\resources\wampee.ini")
$Papache = IniRead($WampeeInifile, "ports", "apache", "80")
$Pmysql = IniRead($WampeeInifile, "ports", "mysql", "3307")
$Pmariadb = IniRead($WampeeInifile, "ports", "mariadb", "3308")

$php_version = IniRead($Inifile, "php", "phpVersion", "")
$php_name_exe = IniRead($Inifile, "phpCli", "phpExeFile", "")

$service_apache = IniRead($Inifile, "service", "ServiceApache", "")
$service_mysql = IniRead($Inifile, "service", "ServiceMysql", "")
$service_mariadb = IniRead($Inifile, "service", "ServiceMariadb", "")

$php_exe = @ScriptDir & "\..\bin\php\php" & $php_version & "\" & $php_name_exe
$scripts_dir = @ScriptDir & "\..\scripts"


ShowGUI()

GUICtrlSetData($ef_apache, $Papache)
GUICtrlSetData($ef_mysql, $Pmysql)
GUICtrlSetData($ef_mariadb, $Pmariadb)

testPorts()

GUISetState(@SW_SHOW)

While 1
	Sleep(100)
WEnd

Func bu_cancelClick()
	Exit
EndFunc   ;==>bu_cancelClick
Func bu_okClick()
	IniWrite($WampeeInifile, "ports", "apache", GUICtrlRead($ef_apache))
	IniWrite($WampeeInifile, "ports", "mysql", GUICtrlRead($ef_mysql))
	IniWrite($WampeeInifile, "ports", "mariadb", GUICtrlRead($ef_mariadb))
	$pathAbs = _PathFull(@ScriptDir & "\..\")
	FileChangeDir($pathAbs)
	$value = Run($pathAbs & "wampee.exe", $pathAbs)
	Exit
EndFunc   ;==>bu_okClick
Func bu_testClick()
	testPorts()
EndFunc   ;==>bu_testClick
Func ef_apacheChange()
	GUICtrlSetState($bu_ok, $GUI_DISABLE)
EndFunc   ;==>ef_apacheChange
Func ef_mysqlChange()
	GUICtrlSetState($bu_ok, $GUI_DISABLE)
EndFunc   ;==>ef_mysqlChange
Func ef_mariadbChange()
	GUICtrlSetState($bu_ok, $GUI_DISABLE)
EndFunc   ;==>ef_mariadbChange
Func label_apacheClick()

EndFunc   ;==>label_apacheClick
Func ud_apacheChange()
	GUICtrlSetState($bu_ok, $GUI_DISABLE)
EndFunc   ;==>ud_apacheChange


Func bu_apachePID()
	$value = Run($php_exe & " testPort.php " & GUICtrlRead($ef_apache) & " " & $service_apache, $scripts_dir)
EndFunc   ;==>bu_apachePID

Func ud_mysqlChange()
	GUICtrlSetState($bu_ok, $GUI_DISABLE)
EndFunc   ;==>ud_mysqlChange
Func bu_mysqlPID()
	$value = Run($php_exe & " testPort.php " & GUICtrlRead($ef_mysql) & " " & $service_mysql, $scripts_dir)
EndFunc   ;==>bu_mysqlPID

Func ud_mariadbChange()
	GUICtrlSetState($bu_ok, $GUI_DISABLE)
EndFunc   ;==>ud_mariadbChange
Func bu_mariaPID()
	$value = Run($php_exe & " testPort.php " & GUICtrlRead($ef_mariadb) & " " & $service_mariadb, $scripts_dir)
EndFunc   ;==>bu_mariaPID

Func wampserverClose()
	Exit
EndFunc   ;==>wampserverClose
Func wampserverMaximize()

EndFunc   ;==>wampserverMaximize
Func wampserverMinimize()

EndFunc   ;==>wampserverMinimize
Func wampserverRestore()

EndFunc   ;==>wampserverRestore

Func testPorts()
	GUICtrlSetState($bu_ok, $GUI_ENABLE)
	$noGood = False
	$socketa = TCPConnect($g_IP, GUICtrlRead($ef_apache))
	If $socketa = -1 Or $socketa = 0 Then
		GUICtrlSetBkColor($ef_apache, 0x00ff00)
	Else
		GUICtrlSetBkColor($ef_apache, 0xff0000)
		GUICtrlSetState($bu_ok, $GUI_DISABLE)
		$noGood = True
	EndIf

	$socketm = TCPConnect($g_IP, GUICtrlRead($ef_mysql))
	If $socketm = -1 Or $socketm = 0 Then
		GUICtrlSetBkColor($ef_mysql, 0x00ff00)
	Else
		GUICtrlSetBkColor($ef_mysql, 0xff0000)
		GUICtrlSetState($bu_ok, $GUI_DISABLE)
		$noGood = True
	EndIf

	$socketma = TCPConnect($g_IP, GUICtrlRead($ef_mariadb))
	If $socketma = -1 Or $socketma = 0 Then
		GUICtrlSetBkColor($ef_mariadb, 0x00ff00)
	Else
		GUICtrlSetBkColor($ef_mariadb, 0xff0000)
		GUICtrlSetState($bu_ok, $GUI_DISABLE)
		$noGood = True
	EndIf

	Return $noGood

EndFunc   ;==>testPorts

Func ShowGUI()
	$wampserverGUI = GUICreate("Wampserver", 254, 190, 500, 600)
	GUISetOnEvent($GUI_EVENT_CLOSE, "wampserverClose")
	GUISetOnEvent($GUI_EVENT_MINIMIZE, "wampserverMinimize")
	GUISetOnEvent($GUI_EVENT_MAXIMIZE, "wampserverMaximize")
	GUISetOnEvent($GUI_EVENT_RESTORE, "wampserverRestore")

	$Group1 = GUICtrlCreateGroup("Ports WampServer", 1, 0, 241, 150)

	$label_apache = GUICtrlCreateLabel("Port HTTP Apache", 16, 26, 100, 17)
	GUICtrlSetOnEvent(-1, "label_apacheClick")
	$ef_apache = GUICtrlCreateInput("80", 112, 24, 65, 21, $ES_NUMBER)
	GUICtrlSetOnEvent(-1, "ef_apacheChange")
	$ud_apache = GUICtrlCreateUpdown($ef_apache, $UDS_NOTHOUSANDS)
	GUICtrlSetLimit(-1, 32767, 80)
	GUICtrlSetOnEvent(-1, "ud_apacheChange")
	$bu_apachePID = GUICtrlCreateButton("PID", 180, 22, 60, 25)
	GUICtrlSetOnEvent(-1, "bu_apachePID")


	$Label1 = GUICtrlCreateLabel("Port MySQL", 16, 64, 100, 17)

	$ef_mysql = GUICtrlCreateInput("3307", 112, 62, 65, 21, $ES_NUMBER)
	GUICtrlSetOnEvent(-1, "ef_mysqlChange")
	$ud_mysql = GUICtrlCreateUpdown($ef_mysql, $UDS_NOTHOUSANDS)
	GUICtrlSetLimit(-1, 32767, 1024)
	GUICtrlSetOnEvent(-1, "ud_mysqlChange")
	$bu_mysqlPID = GUICtrlCreateButton("PID", 180, 60, 60, 25)
	GUICtrlSetOnEvent(-1, "bu_mysqlPID")

	$Label2 = GUICtrlCreateLabel("Port MariaDB", 16, 102, 100, 17)

	$ef_mariadb = GUICtrlCreateInput("3308", 112, 102, 65, 21, $ES_NUMBER)
	GUICtrlSetOnEvent(-1, "ef_mariadbChange")
	$ud_mariadb = GUICtrlCreateUpdown($ef_mariadb, $UDS_NOTHOUSANDS)
	GUICtrlSetLimit(-1, 32767, 1024)
	GUICtrlSetOnEvent(-1, "ud_mariadbChange")
	$bu_mariaPID = GUICtrlCreateButton("PID", 180, 100, 60, 25)
	GUICtrlSetOnEvent(-1, "bu_mariaPID")

	$bu_ok = GUICtrlCreateButton("OK", 8, 154, 73, 25)
	GUICtrlSetOnEvent(-1, "bu_okClick")
	$bu_cancel = GUICtrlCreateButton("Cancel", 168, 154, 73, 25)
	GUICtrlSetOnEvent(-1, "bu_cancelClick")
	$bu_test = GUICtrlCreateButton("Test", 88, 154, 73, 25)
	GUICtrlSetOnEvent(-1, "bu_testClick")
EndFunc   ;==>ShowGUI
