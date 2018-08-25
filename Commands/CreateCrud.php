<?php 
namespace FastCode\Commands;

use CodeIgniter\CLI\CLI;
use CodeIgniter\CLI\BaseCommand;
use FastCode\Libraries\Generate;

class CreateCrud extends BaseCommand
{
    use Generate;
    protected $group       = 'FastCodeCI';
    protected $name        = 'create:crud';
    protected $description = 'Easily create a CRUD with basic operations';
    protected $data        = [];


    public function run(array $params)
    {

    	$table = array_shift($params);
    	$controllerName = array_shift($params);
    	$modelName = array_shift($params);


    	$namespace = "App";

        if (empty($table))
        {
            $table = CLI::prompt('Table name');
        }

		if (empty($controller))
        {
            $controllerName = CLI::prompt('Controller name');
        }

        if (empty($modelName))
        {
            $modelName = CLI::prompt('Model name');
        }

        if ($modelName==$controllerName){
            $modelName = CLI::prompt('Please enter other name for Model');
        }


        if ($fields_db =  $this->getFields($table)){

            $this->data = [
                'table'             => $table,
                'primaryKey'        => $this->getPrimaryKey($fields_db),
                'namespace'         => $namespace,
                'nameEntity'        => ucfirst($table),
                'nameModel'         => ucfirst($modelName),
                'nameController'    => ucfirst($controllerName),
                'propertyList'      => $this->getDatesFromFields($fields_db)['propertyList'],
                'allowedFields'     => $this->getDatesFromFields($fields_db)['allowedFields'],
                'fieldsDates'       => $this->getDatesFromFields($fields_db)['fieldsDates'],
                'fieldsTh'          => $this->getDatesFromFields($fields_db)['fieldsTh'],
                'fieldsTd'          => $this->getDatesFromFields($fields_db)['fieldsTd'],
                'inputForm'         => $this->getDatesFromFields($fields_db)['inputForm'],
                'valueInput'        => $this->getDatesFromFields($fields_db)['valueInput'],
                'returnType'        => "App\Entities\\".ucfirst($table),


            ];

            $this->createFileCrud($this->data);

            echo "Done!";

        }else{
            echo "Table no found";
        }




    }








}