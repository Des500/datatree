<?php

class Tree extends Controller
{
     public function index($id = 0) {
        $dataTree = $this->Model('TreeModel');
        $itemData = $id > 0 ? $dataTree->getElement($id): '';
        $tree = $dataTree->getTree()['items'];
         array_unshift($tree, [
             'level' => 0,
             'item' => [
                 'id' => 0,
                 'title' => 'Корень дерева',
             ]
         ]);
        $data = [
            'tree' =>  $tree,
            'itemdata' =>  $itemData
        ];
        $this->view('tree/index', $data);
    }

    public function adminpanel ($id = 0) {
        $user = $this->Model('UserModel');
        if($user->getUser()['role'] !== 'admin')
            $this->redirect('location: /');

        $dataTree = $this->Model('TreeModel');
        $itemData = $id > 0 ? $dataTree->getElement($id): '';
        $tree = $dataTree->getTree()['items'];
        array_unshift($tree, [
            'level' => 0,
            'item' => [
                'id' => 0,
                'title' => 'Корень дерева',
            ]
        ]);
        $data = [
            'tree' =>  $tree,
            'itemdata' =>  $itemData
        ];
        $this->view('tree/admin', $data);
    }

    public function edit($id) {
        $user = $this->Model('UserModel');
        if($user->getUser()['role'] !== 'admin')
            $this->redirect('location: /');

        $dataTree = $this->Model('TreeModel');
        $itemData = $id > 0 ? $dataTree->getElement($id): '';
        $tree = $dataTree->getTree(0, $id)['items'];
        array_unshift($tree, [
            'level' => 0,
            'item' => [
                'id' => 0,
                'title' => 'Корень дерева',
            ]
        ]);

        $data = [
            'tree' =>  $tree,
            'itemdata' =>  $itemData
        ];
        $this->view('tree/edit', $data);
    }

    public function add($parent_id) {
        $user = $this->Model('UserModel');
        if($user->getUser()['role'] !== 'admin')
            $this->redirect('location: /');

        $dataTree = $this->Model('TreeModel');
        $tree = $dataTree->getTree()['items'];
        array_unshift($tree, [
            'level' => 0,
            'item' => [
                'id' => 0,
                'title' => 'Корень дерева',
            ]
        ]);
        $data = [
            'tree' =>  $tree,
            'parent_id' =>  $parent_id,
        ];
        $this->view('tree/add', $data);
    }

    public function update() {
        $user = $this->Model('UserModel');
        if($user->getUser()['role'] !== 'admin')
            $this->redirect('location: /');

        $dataTree = $this->Model('TreeModel');
        if(isset($_POST['id'])) {
            $dataTree->id = $_POST['id'];
            $dataTree->parent_id = explode('|',$_POST['parent_id'])[1];
            $dataTree->title = $_POST['title'];
            $dataTree->description = $_POST['description'];
            if($dataTree->validForm() === 'ok') {
                $dataTree->save();
                $this->redirect('location: /tree/adminpanel/'.$_POST['id']);
            }
            else {
                $this->redirect('location: /tree/edit/'.$_POST['id']);
            }
        }
    }

    public function store() {
        $user = $this->Model('UserModel');
        if($user->getUser()['role'] !== 'admin')
            $this->redirect('location: /');

        $dataTree = $this->Model('TreeModel');
        if(isset($_POST['parent_id'])) {
            $parent_id = explode('|',$_POST['parent_id'])[1];
            $dataTree->id = 0;
            $dataTree->parent_id = $parent_id;
            $dataTree->title = $_POST['title'];
            $dataTree->description = $_POST['description'];
            if($dataTree->validForm() === 'ok') {
                $dataTree->save();
                $this->redirect('location: /tree/adminpanel/'.$parent_id);
            }
            else
                $this->redirect('location: /tree/adminpanel/');
        }
    }

    public function delete($id) {
        $user = $this->Model('UserModel');
        if($user->getUser()['role'] !== 'admin')
            $this->redirect('location: /');
        else {
            $dataTree = $this->Model('TreeModel');
            $parent_id = $dataTree->getElement($id)['parent_id'];
            $result = $dataTree->delete($id);
            $this->redirect('location: /tree/adminpanel/' . $parent_id);
        }
     }
}