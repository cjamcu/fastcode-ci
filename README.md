# FastCodeCi
**FastCodeCi** are several command line interface tools for CodeIgniter 4 that allows you to quickly create a repetitive code in record time. The exit code is so clean that it allows modification without problems...

## Functionalities

 - [x] Create  CRUD of a table
 - [ ] Create Panel Admin
 - [ ] Create a Controller file
 - [ ] Create a Model file
 - [ ] Create a Entity file
 - [ ] Create a Migration file
 - [ ] Create a Command file.


## Installation
 1- Add the following to the `require-dev` section of your project's 
  ```"cjam/fastcode-ci" :"dev-master"```
 
 2-```composer update```
 
 3- open up `/application/Config/Autoload.php` and create the `FastCode` namespace in the `$psr4` array:
```
public $psr4 = [
	'FastCode'   	=> ROOTPATH.'vendor/cjam/fastcode-ci',
];
```

## Start using it
Everything is ready to start generating repetitive code with FastCodeCi
#### Command to create CRUD
```
php spark create:crud
```
**This command prompted to enter the name of the table, the model  and controller.**

