<?php

namespace App\Controllers;

use App\Models\RegimeModel;
use CodeIgniter\HTTP\ResponseInterface;

class Regimes extends BaseController
{
    public function index()
    {
        $model = new RegimeModel();
        $data = $model->where('actif', 1)->orderBy('id','DESC')->findAll();
        return $this->response->setJSON(['success'=>true,'data'=>$data]);
    }

    public function show($id = null)
    {
        if (!$id) return $this->response->setJSON(['success'=>false,'message'=>'missing id'])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        $model = new RegimeModel();
        $row = $model->find($id);
        if (!$row) return $this->response->setJSON(['success'=>false,'message'=>'not found'])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        return $this->response->setJSON(['success'=>true,'data'=>$row]);
    }

    public function create()
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();
        if (!$data) return $this->response->setJSON(['success'=>false,'message'=>'no data'])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        $model = new RegimeModel();
        $id = $model->insert($data);
        return $this->response->setJSON(['success'=>true,'id'=>$id])->setStatusCode(ResponseInterface::HTTP_CREATED);
    }

    public function update($id = null)
    {
        if (!$id) return $this->response->setJSON(['success'=>false,'message'=>'missing id'])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        $data = $this->request->getJSON(true) ?? $this->request->getRawInput();
        $model = new RegimeModel();
        $updated = $model->update($id,$data);
        return $this->response->setJSON(['success'=>true,'updated'=>$updated]);
    }

    public function delete($id = null)
    {
        if (!$id) return $this->response->setJSON(['success'=>false,'message'=>'missing id'])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        $model = new RegimeModel();
        $deleted = $model->delete($id);
        return $this->response->setJSON(['success'=>true,'deleted'=>$deleted]);
    }
}
