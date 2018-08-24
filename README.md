# FastCodeCi
**FastCodeCi** are several command line interface tools for CodeIgniter 4 that allows you to quickly create a repetitive code in record time. The exit code is so clean that it allows modification without problems...

## Functionalities

 - [x] Create  CRUD of a table
 - [ ] Create Panel Admin
 - [x] Create a Controller file
 - [x] Create a Model file
 - [x] Create a Entity file
 - [x] Create a Migration file
 - [x] Create a Command file.


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
