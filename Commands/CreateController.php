<?php
/**
 * Created by PhpStorm.
 * User: cjam
 * Date: 8/23/2018
 * Time: 6:52 p.m.
 */

namespace FastCode\Commands;

use CodeIgniter\CLI\CLI;
use CodeIgniter\CLI\BaseCommand;
use FastCode\Libraries\Generate;

class CreateController extends BaseCommand
{
    use Generate;
    protected $group = 'FastCodeCI';
    protected $name = 'create:controller';
    protected $description = 'Create a  Controller file';



    public function run(array $params)
    {
        $name = array_shift($params);

        if (empty($name))
        {
            $name = CLI::prompt('Controller Name');
        }


        if (empty($namespace))
        {
            $namespace = CLI::prompt('Namespace');
        }

        if (empty($namespace) || $namespace==""){
            $namespace = "App";
        }

        $data = [
            'nameController'    =>  ucfirst($name),
            'namespace'         =>  $namespace,
        ];

        $content = $this->render('SimpleController',$data);
        $path    = $this->getPathOutput('Controllers',$data['namespace']);
        if (!is_dir($path)){
            $this->createDirectory($path);
        }
        $this->copyFile($path.$data['nameController'].'.php',$content);

        echo "File created :" . $path.$data['nameController'].'.php';


    }
}