<?php

class Tree extends Controller
{
     public function index($id = 0) {
        $dataTree = $this->Model('TreeModel');
        $itemData = $id > 0 ? $dataTree->getElement($id): '';
        $tree = $dataTree->getTree()['items'];
        $data = [
            'tree' =>  $tree,
            'itemdata' =>  $itemData
        ];
        $this->view('tree/index', $data);
    }

    public function adminpanel ($id = 0) {
        $this->userRoleCheck();

        $dataTree = $this->Model('TreeModel');
        $itemData = $id > 0 ? $dataTree->getElement($id): '';
        $tree = $dataTree->getTree()['items'];
        $data = [
            'tree' =>  $tree,
            'itemdata' =>  $itemData
        ];
        $this->view('tree/admin', $data);
    }

    public function edit($id) {
        $this->userRoleCheck();

        $dataTree = $this->Model('TreeModel');
        $itemData = $id > 0 ? $dataTree->getElement($id): '';
        $tree = $dataTree->getTree(0, $id)['items'];
        $data = [
            'tree' =>  $tree,
            'itemdata' =>  $itemData
        ];
        $this->view('tree/edit', $data);
    }

    public function add($parent_id) {
        $this->userRoleCheck();

        $dataTree = $this->Model('TreeModel');
        $tree = $dataTree->getTree()['items'];
        $data = [
            'tree' =>  $tree,
            'parent_id' =>  $parent_id,
        ];
        $this->view('tree/add', $data);
    }

    public function update() {
        $this->userRoleCheck();

        $dataTree = $this->Model('TreeModel');
        if(isset($_POST['id'])) {
            $dataTree->id = $_POST['id'];
            $dataTree->parent_id = explode('|',$_POST['parent_id'])[1];
            $dataTree->title = $_POST['title'];
            $dataTree->description = $_POST['description'];
            $valid = $dataTree->validForm();
            if($valid === 'ok') {
                NotifMessage::setStatus('success', $dataTree->save());
                $this->redirect('location: /tree/adminpanel/'.$_POST['id']);
            }
            else {
                NotifMessage::setStatus('error', $valid);
                $this->redirect('location: /tree/edit/'.$_POST['id']);
            }
        }
    }

    public function store() {
         $this->userRoleCheck();

        $dataTree = $this->Model('TreeModel');
        if(isset($_POST['parent_id'])) {
            $parent_id = explode('|',$_POST['parent_id'])[1];
            $dataTree->id = 0;
            $dataTree->parent_id = $parent_id;
            $dataTree->title = $_POST['title'];
            $dataTree->description = $_POST['description'];
            if($dataTree->validForm() === 'ok') {
                NotifMessage::setStatus('success', $dataTree->save());
                $this->redirect('location: /tree/adminpanel/'.$parent_id);
            }
            else {
                NotifMessage::setStatus('error', $valid);
                $this->redirect('location: /tree/adminpanel/');
            }
        }
    }

    public function delete($id) {
         if($this->userRoleCheck())
         {
            $dataTree = $this->Model('TreeModel');
            $parent_id = $dataTree->getElement($id)['parent_id'];
            NotifMessage::setStatus('success', $dataTree->delete($id));
            $this->redirect('location: /tree/adminpanel/' . $parent_id);
        }
     }

     private function userRoleCheck () {
         $user = $this->Model('UserModel');
         if($user->getUser()['role'] !== 'admin') {
             NotifMessage::setStatus('error', 'У вас нет прав на изменение контента');
             $this->redirect('location: /');
             return false;
         }
         else return true;
     }
}