# WAMPEE

Just Extract Archive on USB Key, or Hard Drive, or external Disk then simply launch Wampee.exe

Wampee is the 32 bit Portable version of WampServer 3.1
Wampee install nothing on your computer. No services, No registry

Because new MySQL and Apache release, you may have to install some Visual C++ Redist Packages from microsoft (check "Visual C++ Packages" section)


Wampee is using  :
Apache     : 2.4.33
MySQL      : 5.7.22
MariaDB		 : 10.2.14 
PHP        : 7.2.4
PHPMyAdmin : 4.9.1
XDebug     : 1.0 beta6
phpsysinfo : 3.3.1
adminer    : 4.7.3

Have fun !!

## infos + passwords

### Local Host
ServerWeb: http://localhost:81/

### Mysql
Server: localhost:3307
User: root 
Pass:
### MariaDB
Server: localhost:3308
User: root 
Pass:

// there is no pass by default on MySQL DB and Maria DB!!

### phpmyadmin
#### Mysql
ServerChoice: MySQL 
User: root 
Pass:
#### MariaDB
ServerChoice: MariaDB
User: root 
Pass:

### adminer
#### Mysql
Server: localhost:3307
User: root 
Pass:
#### Mysql -> MariaDB
Server: localhost:3308
User: root 
Pass:

## Visual C++ Packages

Make sure you are "up to date" in the redistributable packages VC9, VC10, VC11, VC13 ,  VC14 and VC15
See --- Visual C++ Packages below.
--- Do not install Wampserver OVER an existing version, follow the advice:
- Install a new version of Wampserver: http://forum.wampserver.com/read.php?2,123606
If you install Wampserver over an existing version, not only it will not work, but you risk losing your existing databases.
--- Install Wampserver in a folder at the root of a disk, for example C:\wamp or D:\wamp. Take an installation path that does not include spaces or diacritics; Therefore, no installation in c: \ Program Files\ or C: \ Program Files (x86\
We must BEFORE installing, disable or close some applications:
- Close Skype or force not to use port 80
Item No. 04 of the Wampserver TROUBLESHOOTING TIPS:http://forum.wampserver.com/read.php?2,134915
- Disable IIS
Item No. 08 of the Wampserver TROUBLESHOOTING TIPS:http://forum.wampserver.com/read.php?2,134915
If these prerequisites are not in place, Press the Cancel button to cancel the installation, then apply the prerequisites and restart the installation.
This program requires Administrator privileges to function properly. It will be launched with the "Run as administrator" option. If you do not want a program to have this option, cancel the installation.
--- Visual C++ Packages ---
The MSVC runtime libraries VC9, VC10, VC11 are required for Wampserver 2.4, 2.5 and 3.0, even if you use only Apache and PHP versions with VC11. Runtimes VC13, VC14 is required for PHP 7 and Apache 2.4.17 or more
-- VC9 Packages (Visual C++ 2008 SP1)
http://www.microsoft.com/en-us/download/details.aspx?id=5582
http://www.microsoft.com/en-us/download/details.aspx?id=2092
-- VC10 Packages (Visual C++ 2010 SP1)
http://www.microsoft.com/en-us/download/details.aspx?id=8328
http://www.microsoft.com/en-us/download/details.aspx?id=13523
-- VC11 Packages (Visual C++ 2012 Update 4)
The two files VSU4\vcredist_x86.exe and VSU4\vcredist_x64.exe to be download are on the same page: http://www.microsoft.com/en-us/download/details.aspx?id=30679
-- VC13 Packages] (Visual C++ 2013)
The two files VSU4\vcredist_x86.exe and VSU4\vcredist_x64.exe to be download are on the same page: https://www.microsoft.com/en-us/download/details.aspx?id=40784
-- VC14 Packages (Visual C++ 2015 Update 3)
The two files vcredist_x86.exe and vcredist_x64.exe to be download are on the same page:
http://www.microsoft.com/fr-fr/download/details.aspx?id=53840
- VC15 Redistribuable (Visual C++ 2017)
https://go.microsoft.com/fwlink/?LinkId=746571
Visual C++ Redistributable Packages for Visual Studio 2017 x86
https://go.microsoft.com/fwlink/?LinkId=746572
Visual C++ Redistributable Packages for Visual Studio 2017 x64
VC2017 (VC15) is backward compatible to VC2015 (VC14). That means, a VC14 module can be used inside a VC15 binary. Because this compatibility the version number of the Redistributable is 14.1x.xx and after you install the Redistributable VC2017, VC2015 is removed but you can still use VC14.

If you have a 64-bit Windows, you must install both 32 and 64bit versions of each VisualC++ package, even if you do not use Wampserver 64 bit.
Warning: Sometimes Microsoft may update the VC ++ package by breaking the download links and without redirect to the new. If the case happens to you, remember that item number 20 below will be updated and the page http://wampserver.aviatechno.net/ section Visual C++ Redistribuable Packages is up to date.
This is item number 20 of TROUBLESHOOTING TIPS of Wampserver:
 http://forum.wampserver.com/read.php?2,134915
 
## phpMyAdmin

When starting phpMyAdmin, you will be asked for a user name and password.
After installing Wampserver 3, the default username is "root" (without quotes) and there is no password, which means that you must leave the form Password box empty.
There will be a warning:
You are connected as 'root' with no password, which corresponds to the default MySQL privileged account. Your MySQL server is running with this default, is open to intrusion, and you really should fix this security hole by setting a password for user 'root'.
This is not a problem as long as access to Phpmyadmin remain locally.
However, some web applications or CMS asking that the MySQL user has a password. In which case, you will create a user with password via the PhpMyAdmin Accounts Users tab.
  
## Using the menus and submenus of

Do not use the keyboard to navigate through the menus and submenus of Wampmanager icon.

## For questions regarding Wampserver 3
Please use the specific forum: http://forum.wampserver.com/list.php?2
Do not use an existing discussion, but create your own thread:
New Topic
after having read  READ BEFORE YOU ASK A QUESTION in this forum.