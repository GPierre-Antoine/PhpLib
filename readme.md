# Php Library for multiple project
###### *Makes use of PSR interfaces*

### Introduction

This is a PHP Library project, allowing to avoid redoing boilerplate code.

This library tends to follow the PSR Guidelines : https://www.php-fig.org/

As of today (July the 21st, 2018), the page advertises for 3 major sections :

- Autoloading (PSR-4)
- Interfaces (PSR-3, 6, 7, 11, 13, 15, 16)
- Coding styles (PSR-1, 2)

This code try to follows the PSR recommendations except for PSR-2 5.1 (namely putting curly braces on the same line as conditional statements), which :
- I have troubles reading
- Doesn't play well with my IDE's code autofolding


### About the content
This library's content is organized in many files :

* **bin** contains an executable php file *phex*

It can be used to, for instance, transform text into json via

    phex csvize < file
    
* **ManualTesting** contains a collection of tests that cannot be automated (for instance, testing `register_shutdown_function`) that is automatically called in the test suite

* **Test** contains the test suite

* **test_bootstrap.php** is a initialiser for the test suite

* **lib** contains the actual library files.

  * **Psr** contains files from the **P**hp **S**tandard **R**ecommendations
  * **PAG** contains my personnal libraries
  * **Experimental** contains non-conform thoughts or experiments
  

### Notable namespaces

#### PAG\Connection 

**Connection** is a set of utilitary classes that handle various connection in an abstract manner. 

It allows SSH (**SFTP**) connections via **password** and **pubkey** authentication, as well as **FTP** and **FTPS** connection via **password** and **netrc** authentication.

It provides simple interfaces such as `RemoteFileTransferTool`, which allows to `copyLocalToRemote(local, remote)` and `copyRemoteToLocal(remote, local)`, that can be used independantly from the type of connexion used, with an intelligent type-resolution transfer for FTP, which can be forced as well.

#### PAG\IO\Format

**Format** is a namespace to handle the formatting of data to be exported.

For now, handled formats are CSV and Json.


### Currently thinking about

* Php Cron Automation

* Export / Import automation through factory and handlers 

#### Licence : MIT
