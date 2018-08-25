<?php
/**
 * Created by PhpStorm.
 * User: cjam
 * Date: 8/21/2018
 * Time: 4:25 p.m.
 */

namespace FastCode\Libraries;
use Config\Autoload;
use  Config\Services;



trait Generate
{
    /**
     * @param string $folder
     * @param string $namespace
     * @return string
     */
    protected function getPathOutput($folder='', $namespace = 'App'){
        // Get namespace location form  PSR4 paths.
        $config = new Autoload();
        $location = $config->psr4[$namespace];

        $path = rtrim($location, '/') . "/". $folder;

        return rtrim($this->normalizePath($path), '/ ') .'/';
    }

    /**
     * @param $path
     * @return string
     */
    protected function normalizePath($path)
    {
        // Array to build a new path from the good parts
        $parts = [];

        // Replace backslashes with forward slashes
        $path = str_replace('\\', '/', $path);

        // Combine multiple slashes into a single slash
        $path = preg_replace('/\/+/', '/', $path);

        // Collect path segments
        $segments = explode('/', $path);

        // Initialize testing variable
        $test = '';

        foreach ($segments as $segment)
        {
            if ($segment != '.')
            {
                $test = array_pop($parts);

                if (is_null($test))
                {
                    $parts[] = $segment;
                } else if ($segment == '..')
                {
                    if ($test == '..')
                    {
                        $parts[] = $test;
                    }

                    if ($test == '..' || $test == '')
                    {
                        $parts[] = $segment;
                    }
                } else
                {
                    $parts[] = $test;
                    $parts[] = $segment;
                }
            }
        }

        return implode('/', $parts);
    }


    /**
     * @param $path
     * @param null $contents
     */
    protected function copyFile($path, $contents = null){
        helper('filesystem');

        $folder = $this->getDirOfFile($path);
        if (! is_dir($folder))
        {
            $this->createDirectory($folder);
        }

        if (! write_file($path, $contents))
        {
            throw new \RuntimeException(sprintf(lang('FastCode.errorWritingFile'), $path));
        }

    }

    /**
     * @param $template_name
     * @param array $data
     * @return mixed|string
     */
    public function render($template_name, $data = [])
    {
        if (empty($this->parser))
        {
            $path         = realpath(__DIR__.'/../Templates/').'/';
            $this->parser = Services::parser($path);
        }

        if (is_null($this->parser))
        {
            throw new \RuntimeException('Unable to create Parser instance.');
        }

        $output = $this->parser
            ->setData($data)
            ->render($template_name);


        $output = str_replace('@php', '<?php', $output);
        $output = str_replace('!php', '?>', $output);
        $output = str_replace('@=', '<?=', $output);
        return $output;
    }

    /**
     * @param $table
     * @return array|bool|false
     */
    protected function getFields($table){
        $this->db = \Config\Database::connect();
        if ($this->db->tableExists($table))
        {
            return  $fields = $this->db->getFieldData($table);
        }else{
            return false;
        }
    }

    /**
     * @param $fields
     * @return mixed
     */
    protected function getPrimaryKey($fields)
    {
        foreach ($fields as $field) {
            if ($field->primary_key) {
                return $field->name;
            }
        }
    }


    /**
     * @param $fields
     * @return array
     */
    protected function getDatesFromFields($fields){
        foreach ($fields as $field){
            $properties      []  =  "\tprotected \${$field->name};";

            if (!$field->primary_key){
                $fields_th       []  =  "<th>".ucfirst($field->name)."</th>";
                $allowedFields   []  =  "'".$field->name."'";
                $fields_data     []  =  '\''.$field->name.'\'=>$data_db->'.$field->name.'';
                $fields_td       []  =  ' <td><?=$row->'.$field->name.';?></td>';
                $valueInput      []  =  '$(\'[name="'.$field->name.'"]\').val((data.'.$field->name.'));';

                if ($this->getTypeInput($field->type)!='textarea'){
                    $inputForm   []  =
                        '  <div class="form-group">
							    <label>'.ucfirst($field->name).'</label>
							    <input name="'.$field->name.'" id="'.$field->name.'" type="'.$this->getTypeInput($field->type).'" class="form-control">
			                </div>
                        ';
                }else{
                    $inputForm   []  =
                        '  <div class="form-group">
							    <label>'.ucfirst($field->name).'</label>
							    <textarea name="'.$field->name.'" id="'.$field->name.'" class="form-control"></textarea>
			                </div>
                        ';
                }
            }
        }

        return array(
            'fieldsTh'      => implode("\n",$fields_th),
            'fieldsTd'      => implode("\n",$fields_td),
            'propertyList'  => implode("\n",$properties),
            'allowedFields' => implode(',', $allowedFields),
            'fieldsDates'   => implode(",", $fields_data),
            'inputForm'     => implode("\n", $inputForm),
            'valueInput'    => implode("\n", $valueInput),
        );
    }


    /**
     * @param $data
     */
    protected function createFileCrud($data){

        $pathEntity         = $this->getPathOutput('Entities',$data['namespace']).$data['nameEntity'].'.php';
        $pathModel          = $this->getPathOutput('Models',$data['namespace']).$data['nameModel'].'.php';
        $pathController     = $this->getPathOutput('Controllers',$data['namespace']).$data['nameController'].'.php';
        $pathView           = $this->getPathOutput('Views',$data['namespace']).$data['table'].'.php';

        $this->copyFile($pathEntity,$this->render('Entity',$data));
        $this->copyFile($pathModel,$this->render('Model',$data));
        $this->copyFile($pathController,$this->render('Controller',$data));
        $this->copyFile($pathView,$this->render('View',$data));

        $this->createRoute($data);
    }


    /**
     * Convert the type field sql to type input html
     * @param $type_sql
     * @return string
     */
    public function getTypeInput($type_sql){
        $type_html = "";
        switch ($type_sql){
            case 'int':
                $type_html = 'number';
                break;
            case 'varchar':
                $type_html = 'text';
                break;
            case 'text':
                $type_html = 'textarea';
                break;
        }

        return $type_html;

    }

    /**
     * @param $data
     */
    public function createRoute($data){
        $route_file = file_get_contents(APPPATH.'Config/Routes.php');

        $data_to_write = $route_file."\n".'$routes->add(\'/'.$data['table'].'\',\''.$data['nameController'].'::index\');';
        $data_to_write.="\n";
        $data_to_write.='$routes->add(\'/'.$data['table'].'/delete/(:segment)\',\''.$data['nameController'].'::delete/$1\');';
        $data_to_write.="\n";
        $data_to_write.='$routes->add(\'/'.$data['table'].'/save\',\''.$data['nameController'].'::save\');';
        $data_to_write.="\n";
        $data_to_write.='$routes->add(\'/'.$data['table'].'/edit/(:segment)\',\''.$data['nameController'].'::edit_ajax/$1\');';
        $data_to_write.="\n";


        file_put_contents(APPPATH.'Config/Routes.php', $data_to_write);
    }

    public function createDirectory($path, $perms = 0755)
    {

        if (is_dir($path))
        {
            return $this;
        }

        if (! mkdir($path, $perms, true))
        {
            throw new \RuntimeException(sprintf(lang('FastCode.errorCreatingDir'), $path));
        }

        return $this;
    }

    public function getDirOfFile($file){
        $segments = explode('/', $file);
        array_pop($segments);
        return $folder = implode('/', $segments);
    }




}