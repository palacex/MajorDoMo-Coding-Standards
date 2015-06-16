# MajorDoMo-Coding-Standards
This project is a collection of PHP_CodeSniffer rules (sniffs) to validate code developed for MajorDoMo. It ensures code quality and adherence to coding conventions, especially the official MajorDoMo Coding Standards.

## Installation
### [XAMPP](https://www.apachefriends.org) for MS Windows
* Open command prompt by going (Start -> Run -> type in 'cmd' -> press Enter).
* Go to your php install directory (cd D:\xampp\php)
* Install PHP Code_Sniffer from the pear.php.net:
```bash
pear install PHP_CodeSniffer
pear install --alldeps PHP_CodeSniffer
```
### Linux
```bash
sudo pear install PHP_CodeSniffer
sudo pear install --alldeps PHP_CodeSniffer
```

## Check successfull installation.
If everything goes well, then it should show list of coding standard definitions found.
```bash
phpcs -i 
```

## Add a new coding standard definition into PHP CodeSniffer
* Get coding standard from git repository
```bash
git clone -b master https://github.com/palacex/MajorDoMo-Coding-Standards.git D:\Data\MajorDoMo-Coding-Standards
```
* Add coding standards path to PHP_CodeSniffer configuration
```bash
phpcs --config-set D:\Data\MajorDoMo-Coding-Standards MajorDoMo
```

## How to use

### Command line

Run the `phpcs` command line tool on a given file or directory, for example:
```bash
phpcs --standard=MajorDoMo D:\Project\MajorDoMo\btraced.php
```
Will result in following output:
```bash
FILE: D:\Project\MajorDoMo\btraced.php
----------------------------------------------------------------------
FOUND 33 ERRORS AND 1 WARNING AFFECTING 24 LINES
----------------------------------------------------------------------
  13 | WARNING | [ ] Silencing errors is discouraged
  19 | ERROR   | [x] Whitespace found at end of line
  25 | ERROR   | [x] Whitespace found at end of line
  33 | ERROR   | [x] Whitespace found at end of line
  41 | ERROR   | [x] Expected 1 space before "="; 0 found
  41 | ERROR   | [x] Expected 1 space after "="; 0 found
  47 | ERROR   | [x] Whitespace found at end of line
  60 | ERROR   | [x] Whitespace found at end of line
  78 | ERROR   | [x] Expected 1 space before "="; 0 found
  78 | ERROR   | [x] Expected 1 space after "="; 0 found
  86 | ERROR   | [x] Expected 1 space before "="; 0 found
  86 | ERROR   | [x] Expected 1 space after "="; 0 found
  89 | ERROR   | [ ] Line contains 596 chars, which is more than the
     |         |     limit of 160
  92 | ERROR   | [x] Concat operator must be surrounded by a single
     |         |     space
  93 | ERROR   | [x] Whitespace found at end of line
  97 | ERROR   | [x] Whitespace found at end of line
 103 | ERROR   | [x] Concat operator must be surrounded by a single
     |         |     space
 103 | ERROR   | [x] Concat operator must be surrounded by a single
     |         |     space
 103 | ERROR   | [x] Concat operator must be surrounded by a single
     |         |     space
 103 | ERROR   | [x] Concat operator must be surrounded by a single
     |         |     space
 104 | ERROR   | [x] Whitespace found at end of line
 105 | ERROR   | [x] Whitespace found at end of line
 108 | ERROR   | [x] Concat operator must be surrounded by a single
     |         |     space
 108 | ERROR   | [x] Concat operator must be surrounded by a single
     |         |     space
 113 | ERROR   | [ ] Expected "function abc(...)"; found "function
     |         |     abc (...)"
 113 | ERROR   | [ ] Missing function doc comment
 113 | ERROR   | [x] Whitespace found at end of line
 114 | ERROR   | [x] Whitespace found at end of line
 115 | ERROR   | [x] Whitespace found at end of line
 117 | ERROR   | [x] Whitespace found at end of line
 118 | ERROR   | [x] Whitespace found at end of line
 120 | ERROR   | [x] Space before opening parenthesis of function
     |         |     call prohibited
 120 | ERROR   | [x] Whitespace found at end of line
 142 | ERROR   | [x] File must end with a newline character
----------------------------------------------------------------------
PHPCBF CAN FIX THE 30 MARKED SNIFF VIOLATIONS AUTOMATICALLY
----------------------------------------------------------------------

Time: 47ms; Memory: 3Mb
```

### Run the `phpcs` with git commit
#### Windows
Copy file `pre-commit` to MajorDomo `.git/hooks`. For example:
```bash
copy D:\Data\MajorDoMo-Coding-Standards\bin\windows\pre-commit D:\Project\MajorDoMo\.git\hooks
```
