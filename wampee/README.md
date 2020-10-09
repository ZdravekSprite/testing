# WAMPEE

Just Extract Archive on USB Key, or Hard Drive, or external Disk then simply launch Wampee.exe

Wampee is the 32 bit Portable version of WampServer 3.1
Wampee install nothing on your computer. No services, No registry

Because new MySQL and Apache release, you may have to install some Visual C++ Redist Packages from microsoft (check "Visual C++ Packages" section)


Wampee is using  :</br>
- Apache     : 2.4.33</br>
- MySQL      : 5.7.22</br>
- MariaDB		 : 10.2.14</br> 
- PHP        : 7.2.4</br>
- PHPMyAdmin : 4.9.1</br>
- XDebug     : 1.0 beta6</br>
- phpsysinfo : 3.3.1</br>
- adminer    : 4.7.3</br>

Have fun !!

## infos + passwords

### Local Host
ServerWeb: http://localhost:81/

### Mysql
Server: localhost:3307</br>
User: root </br>
Pass:
### MariaDB
Server: localhost:3308</br>
User: root </br>
Pass:

// there is no pass by default on MySQL DB and Maria DB!!

### phpmyadmin
#### Mysql
ServerChoice: MySQL </br>
User: root </br>
Pass:
#### MariaDB
ServerChoice: MariaDB</br>
User: root </br>
Pass:

### adminer
#### Mysql
Server: localhost:3307</br>
User: root </br>
Pass:
#### Mysql -> MariaDB
Server: localhost:3308</br>
User: root </br>
Pass:

## Visual C++ Packages

Make sure you are "up to date" in the redistributable packages VC9, VC10, VC11, VC13 ,  VC14 and VC15</br>
See **Visual C++ Packages** below.</br>
***Do not install Wampserver OVER an existing version, follow the advice:***</br>
- Install a new version of Wampserver: http://forum.wampserver.com/read.php?2,123606
If you install Wampserver over an existing version, not only it will not work, but you risk losing your existing databases.</br>
- Install Wampserver in a folder at the root of a disk, for example C:\wamp or D:\wamp. Take an installation path that does not include spaces or diacritics; Therefore, no installation in c: \ Program Files\ or C: \ Program Files (x86)</br>
We must BEFORE installing, disable or close some applications:</br>
- Close Skype or force not to use port 80
Item No. 04 of the Wampserver TROUBLESHOOTING TIPS:[http://forum.wampserver.com/read.php?2,134915](http://forum.wampserver.com/read.php?2,134915)</br>
- Disable IIS</br>
Item No. 08 of the Wampserver TROUBLESHOOTING TIPS:[http://forum.wampserver.com/read.php?2,134915](http://forum.wampserver.com/read.php?2,134915)</br>
If these prerequisites are not in place, Press the Cancel button to cancel the installation, then apply the prerequisites and restart the installation.</br>
This program requires Administrator privileges to function properly. It will be launched with the "Run as administrator" option. If you do not want a program to have this option, cancel the installation.
### Visual C++ Packages
The MSVC runtime libraries VC9, VC10, VC11 are required for Wampserver 2.4, 2.5 and 3.0, even if you use only Apache and PHP versions with VC11. Runtimes VC13, VC14 is required for PHP 7 and Apache 2.4.17 or more
#### VC9 Packages (Visual C++ 2008 SP1)
[http://www.microsoft.com/en-us/download/details.aspx?id=5582](http://www.microsoft.com/en-us/download/details.aspx?id=5582)</br>
[http://www.microsoft.com/en-us/download/details.aspx?id=2092](http://www.microsoft.com/en-us/download/details.aspx?id=2092)
#### VC10 Packages (Visual C++ 2010 SP1)
[http://www.microsoft.com/en-us/download/details.aspx?id=8328](http://www.microsoft.com/en-us/download/details.aspx?id=8328)</br>
[http://www.microsoft.com/en-us/download/details.aspx?id=13523](http://www.microsoft.com/en-us/download/details.aspx?id=13523)
#### VC11 Packages (Visual C++ 2012 Update 4)
The two files VSU4\vcredist_x86.exe and VSU4\vcredist_x64.exe to be download are on the same page:</br>
[http://www.microsoft.com/en-us/download/details.aspx?id=30679](http://www.microsoft.com/en-us/download/details.aspx?id=30679)
#### VC13 Packages] (Visual C++ 2013)
The two files VSU4\vcredist_x86.exe and VSU4\vcredist_x64.exe to be download are on the same page:</br>
[https://www.microsoft.com/en-us/download/details.aspx?id=40784](https://www.microsoft.com/en-us/download/details.aspx?id=40784)
#### VC14 Packages (Visual C++ 2015 Update 3)
The two files vcredist_x86.exe and vcredist_x64.exe to be download are on the same page:</br>
[http://www.microsoft.com/fr-fr/download/details.aspx?id=53840](http://www.microsoft.com/fr-fr/download/details.aspx?id=53840)
#### VC15 Redistribuable (Visual C++ 2017)
[https://go.microsoft.com/fwlink/?LinkId=746571](https://go.microsoft.com/fwlink/?LinkId=746571)</br>
Visual C++ Redistributable Packages for Visual Studio 2017 x86</br>
[https://go.microsoft.com/fwlink/?LinkId=746572](https://go.microsoft.com/fwlink/?LinkId=746572)</br>
Visual C++ Redistributable Packages for Visual Studio 2017 x64</br>
VC2017 (VC15) is backward compatible to VC2015 (VC14). That means, a VC14 module can be used inside a VC15 binary. Because this compatibility the version number of the Redistributable is 14.1x.xx and after you install the Redistributable VC2017, VC2015 is removed but you can still use VC14.

If you have a 64-bit Windows, you must install both 32 and 64bit versions of each VisualC++ package, even if you do not use Wampserver 64 bit.</br>
Warning: Sometimes Microsoft may update the VC ++ package by breaking the download links and without redirect to the new. If the case happens to you, remember that item number 20 below will be updated and the page [http://wampserver.aviatechno.net/](http://wampserver.aviatechno.net/) section Visual C++ Redistribuable Packages is up to date.</br>
This is item number 20 of TROUBLESHOOTING TIPS of Wampserver:</br>
[http://forum.wampserver.com/read.php?2,134915](http://forum.wampserver.com/read.php?2,134915)
 
## phpMyAdmin

When starting phpMyAdmin, you will be asked for a user name and password.</br>
After installing Wampserver 3, the default username is "root" (without quotes) and there is no password, which means that you must leave the form Password box empty.
There will be a warning:</br>
You are connected as 'root' with no password, which corresponds to the default MySQL privileged account. Your MySQL server is running with this default, is open to intrusion, and you really should fix this security hole by setting a password for user 'root'.</br>
This is not a problem as long as access to Phpmyadmin remain locally.</br>
However, some web applications or CMS asking that the MySQL user has a password. In which case, you will create a user with password via the PhpMyAdmin Accounts Users tab.
  
## Using the menus and submenus of

Do not use the keyboard to navigate through the menus and submenus of Wampmanager icon.

## For questions regarding Wampserver 3
Please use the specific forum: [http://forum.wampserver.com/list.php?2](http://forum.wampserver.com/list.php?2)</br>
Do not use an existing discussion, but create your own thread:</br>
New Topic</br>
after having read  READ BEFORE YOU ASK A QUESTION in this forum.