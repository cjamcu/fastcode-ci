@php

namespace {namespace}\Controllers;


use App\Models\{! nameModel !};
use CodeIgniter\Controller;
use {namespace}\Entities\{! nameEntity !} as {! nameEntity !}Entity;

class {! nameController !} extends Controller
{
    private $model;


    public function __construct()
    {
        $this->model =  new {! nameModel !}();
    }

    public function index(){
        $data = array(
            'rows' => $this->model->findAll(),
        );
        return view('{! table !}',$data);
    }

    public function delete(${! primaryKey !}){
        $this->model->delete(${! primaryKey !});
        return redirect()->to("/{! table !}");
    }

    public function save(){
        $item = new {! nameEntity !}Entity($this->request->getPost());
        $this->model->save($item);
        return redirect()->to("/{! table !}");
    }

    public function edit_ajax(${! primaryKey !}){
        $data_db = $this->model->find(${! primaryKey !});
        $data = array({! fieldsDates !});
        echo  json_encode($data);
    }
}