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

    public function getElementAjax($id = 0) {
        $dataTree = $this->Model('TreeModel');
        $itemData = $id > 0 ? $dataTree->getElement($id): '';
        die(json_encode($itemData));
    }

    public function adminpanel ($id = 0) {
        if(!$this->userRoleCheck()) return false;

        $dataTree = $this->Model('TreeModel');
        $itemData = $id > 0 ? $dataTree->getElement($id): '';
        $tree = $dataTree->getTree()['items'];
        $data = [
            'tree' =>  $tree,
            'itemdata' =>  $itemData
        ];
        $this->view('tree/admin', $data);
    }

    public function edit( $id = 0) {
        if(!$this->userRoleCheck()) return false;

        if ($id == 0) {
            NotifMessage::setStatus('error', 'Не указан элемент');
            $this->redirect('tree/adminpanel');
            return false;
        }
        $dataTree = $this->Model('TreeModel');
        $itemData = $id > 0 ? $dataTree->getElement($id): '';
        $tree = $dataTree->getTree(0, $id)['items'];
        $data = [
            'title' => 'Редактирование элемента '.$itemData['id'].'|'.$itemData['title'],
            'tree' =>  $tree,
            'itemdata' =>  $itemData,
            'isnew' => 0
        ];
        $this->view('tree/edit', $data);
    }

    public function add($parent_id = 0) {
        if(!$this->userRoleCheck()) return false;

        $dataTree = $this->Model('TreeModel');
        $tree = $dataTree->getTree()['items'];
        $itemData = [
            'parent_id' => $parent_id,
            'title' => '',
            'description' => '',
            'id' => 0,
        ];
        $data = [
            'title' => 'Добавление эелемента',
            'tree' =>  $tree,
            'itemdata' =>  $itemData,
            'isnew' => 1
        ];
        $this->view('tree/edit', $data);
    }

    public function store() {
        if(!$this->userRoleCheck()) return false;

        if(isset($_POST['title'])) {
            $isNew = (int)$_POST['isnew'];
            $parent_id = $_POST['parent_id'];
            $dataTree = $this->Model('TreeModel');
            $dataTree->id = $_POST['id'];
            $dataTree->parent_id = $parent_id;
            $dataTree->title = trim($_POST['title']);
            $dataTree->description = trim($_POST['description']);
            $valid = $dataTree->validForm();
            if($valid === 'ok') {
                NotifMessage::setStatus('success', $dataTree->save());
                if ($isNew == 0)
                    $this->redirect('tree/adminpanel/'.$_POST['id']);
                else
                    $this->redirect('tree/adminpanel/'.$parent_id);
                return true;
            }
            else {
                NotifMessage::setStatus('error', $valid);
                if ($isNew == 0)
                    $this->redirect('tree/edit/'.$_POST['id']);
                else
                    $this->redirect('tree/add/'.$parent_id);
                return false;
            }
        }
        NotifMessage::setStatus('error', 'Не указан элемент');
        $this->redirect('tree/adminpanel');
        return false;
    }

    public function delete($id = 0) {
        if($this->userRoleCheck())
         {
             if ($id == 0) {
                 NotifMessage::setStatus('error', 'Не указан элемент');
                 $this->redirect('tree/adminpanel');
                 return false;
             }
            $dataTree = $this->Model('TreeModel');
            $parent_id = $dataTree->getElement($id)['parent_id'];
            NotifMessage::setStatus('success', $dataTree->delete($id));
            $this->redirect('tree/adminpanel/' . $parent_id);
            return true;
        }
     }

     private function userRoleCheck () {
         $user = $this->Model('UserModel');
         if($user->getUser()['role'] !== 'admin') {
             NotifMessage::setStatus('error', 'У вас нет прав на изменение контента');
             $this->redirect('');
             return false;
         }
         else return true;
     }
}