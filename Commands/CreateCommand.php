<?php
/**
 * Created by PhpStorm.
 * User: cjam
 * Date: 8/23/2018
 * Time: 8:43 p.m.
 */

namespace FastCode\Commands;

use CodeIgniter\CLI\CLI;
use CodeIgniter\CLI\BaseCommand;
use FastCode\Libraries\Generate;

class CreateCommand extends BaseCommand
{
    use Generate;
    protected $group = 'FastCodeCI';
    protected $name = 'create:command';
    protected $description = 'Create a  Command file';


    public function run(array $params)
    {
        $group = array_shift($params);
        $Command = array_shift($params);
        $description = array_shift($params);
        $namespace = array_shift($params);

        if (empty($Command))
        {
            $Command = CLI::prompt('Command name');
        }

        if (empty($group))
        {
            $group = CLI::prompt('Group command');
        }

        if (empty($description))
        {
            $description = CLI::prompt('Description command');
        }

        if (empty($namespace))
        {
            $namespace = CLI::prompt('Namespace');
        }

        if (empty($namespace) || $namespace==""){
            $namespace = "App";
        }

        $data = [
                'group'         => $group,
                'CommandName'   => ucfirst($Command),
                'namespace'     => $namespace,
                'description'   => $description,
        ];


        $content = $this->render('Command',$data);
        $path    = $this->getPathOutput('Commands',$data['namespace']);
        if (!is_dir($path)){
            $this->createDirectory($path);
        }
        $this->copyFile($path. $data['CommandName'].'.php',$content);

        echo "File created :" .$path.$data['CommandName'].'.php';




    }
}
