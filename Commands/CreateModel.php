<?php
/**
 * Created by PhpStorm.
 * User: cjam
 * Date: 8/23/2018
 * Time: 7:23 p.m.
 */

namespace FastCode\Commands;

use CodeIgniter\CLI\CLI;
use CodeIgniter\CLI\BaseCommand;
use FastCode\Libraries\Generate;

class CreateModel extends BaseCommand
{
    use Generate;
    protected $group = 'FastCodeCI';
    protected $name = 'create:model';
    protected $description = 'Create a  Model file';
    protected $data = [];

    public function run(array $params)
    {
        $table = array_shift($params);
        $modelName = array_shift($params);

        if (empty($table))
        {
            $table = CLI::prompt('Table name');
        }

        if (empty($modelName))
        {
            $modelName = CLI::prompt('Model name');
        }
        $namespace = "App";

        if ($fields_db =  $this->getFields($table)){
            $data = [
                'nameModel'         => ucfirst($modelName),
                'table'             => $table,
                'primaryKey'        => $this->getPrimaryKey($fields_db),
                'namespace'         => $namespace,
                'allowedFields'     =>  $this->getDatesFromFields($fields_db)['allowedFields'],
                'returnType'        => 'array'
            ];

            $content = $this->render('Model',$data);
            $path = $this->getPathOutput('Models').$data['nameModel'].'.php';
            $this->copyFile($path,$content);

            echo "File created :" . $path;


        }else{
            echo "Table no found";
        }


    }
}
