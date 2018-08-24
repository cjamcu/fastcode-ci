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

        $data = [
            'name'      =>  ucfirst($name),
            'space' =>  'App',
        ];

        $content = $this->render('SimpleController',$data);
        $path    = $this->getPathOutput('Controllers').$data['name'].'.php';
        $this->copyFile($path,$content);

        echo "File created :" . $path;


    }
}