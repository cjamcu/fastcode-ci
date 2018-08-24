<?php
/**
 * Created by PhpStorm.
 * User: cjam
 * Date: 8/23/2018
 * Time: 8:16 p.m.
 */

namespace FastCode\Commands;

use CodeIgniter\CLI\CLI;
use CodeIgniter\CLI\BaseCommand;
use FastCode\Libraries\Generate;

class CreateEntity extends BaseCommand
{
    use Generate;
    protected $group = 'FastCodeCI';
    protected $name = 'create:entity';
    protected $description = 'Create a  Entity file';

    public function run(array $params)
    {
        $table = array_shift($params);


        if (empty($table))
        {
            $table = CLI::prompt('Table name');
        }

        $namespace = "App";

        if ($fields_db =  $this->getFields($table)){
            $data = [
                'table'             => $table,
                'nameEntity'        => ucfirst($table),
                'namespace'         => $namespace,
                'propertyList'      => $this->getDatesFromFields($fields_db)['propertyList'],

            ];

            $content = $this->render('Entity',$data);
            $path    = $this->getPathOutput('Entities').$data['nameEntity'].'.php';
            $this->copyFile($path,$content);

            echo "File created :" . $path;


        }else{
            echo "Table no found";
        }


    }
}