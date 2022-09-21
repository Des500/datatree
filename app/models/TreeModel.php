<?php
require_once "DB.php";
class TreeModel
{
    private $_db = null;

    /**
     * $treeArray -> дерево данных
     * [items] - 2-мерный массив дерева данных без исключенных id
     * [excludedId] - массив исключенных id
     */
    private $treeArray = [
        'items' => [],
        'excludedId' => []
    ];
    private $queryArray= [];

    private $table = 'datatree_alg02';

    public $id;
    public $parent_id;
    public $title;
    public $description;

    public function __construct() {
        $this->_db = DB::getInstence();
    }

    /**
     * Функция сбора дерева данных.
     *
     * @param  int  $id - id родительского элемента, по которому собираются дочерние (для корня - родитель 0)
     * @param  int  $excludeId - id элемента, ветку которого требуется исключить (невозможно перенести родительский элемент в ветку дочернего)
     * @param  bool  $addRootLevel - флаг добавления/пропуска 0-го уровня в начало результирующего массива
     * @return array $treeArray -> дерево данных
     * [items] - массив дерева данных без исключенных id
     * [excludedId] - массив исключенных id
     */
    public function getTree ($id=1, $excludeId = 0, $addRootLevel = true) {
        $query = $this->_db->query("SELECT * FROM $this->table ORDER BY `id`");
        $this->queryArray = $query->fetchAll(PDO::FETCH_ASSOC);
        if($addRootLevel)
            $this->treeArray['items'][0] = [
                'level' => 0,
                'item' => [
                    'id' => 0,
                    'title' => 'Корень дерева',
                    'description' => 'Выберите элемент'
                ]
            ];
        echo $id;
        echo '<hr>';
        $this->getChildrenTree($this->getElement($id),0,$excludeId);
        if(( $excludeId>0 ) && ( isset($this->treeArray['items'][$excludeId]) )) {
            unset($this->treeArray['items'][$excludeId]);
            array_unshift($this->treeArray['excludedId'], $excludeId);
        }
        echo '<hr>';
        print_r($this->treeArray);
        return $this->treeArray;
    }

    /**
     * Функция сбора дочерних элементов 1 го уровня.
     *
     * @param  int  $parent_id - id родительского элемента, по которому собираются дочерние (для корня - родитель 0)
     * @return array $items - массив дочерних элементов
     */
    public function getChildrens ($rootItem) {
        $childrens = explode(',', $rootItem['children_id']);
        $items =[];
        foreach ($childrens as $children_id) {
            if (!empty($children_id)) {
                array_push($items, $this->getElement($children_id));
            }
        }
        return $items;
    }

    /**
     * Функция сбора дерева данных родительского элемента (рекурсивная).
     *
     * @param  int  $parent_id - id родительского элемента, по которому собираются дочерние (для корня - родитель 0)
     * @param  int  $level - родительский уровень (для корня родитель - 0)
     * @param  int  $excludeId - id элемента, ветку которого требуется исключить (невозможно перенести родительский элемент в ветку дочернего)
     */
    public function getChildrenTree($rootItem = '', $level = 0, $excludeId = 0) {
        $items = $this->getChildrens($rootItem);
        $level++;
        foreach ($items as $key => $item) {
            if ($rootItem['id'] != $excludeId) {
                $this->treeArray['items'][$item['id']] = [
                    'level' => $level,
                    'item' => $item
                ];
                $this->getChildrenTree($item, $level, $excludeId);
            }
            else {
                array_push($this->treeArray['excludedId'], $rootItem['id']);
                $this->getChildrenTree($item, $level, $rootItem['id']);
            }
        }
    }

    /**
     * Функция сбора дерева данных родительского элемента (рекурсивная).
     *
     * @param  int  $id - id элемента
     * @return array $item - найденный элемент
     */
    public function getElement($id) {
        $result = $this->_db->query("SELECT * FROM `$this->table` WHERE `id` = '$id'");
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Функция добавления/обновления элемента.
     *
     */
    public function save() {
        if (!empty($this->getElement($this->id))) {
            $table = $this->table;
            $sql = 'UPDATE datatree_alg02 SET title = :title, description = :description WHERE id = :id';
            $query = $this->_db->prepare($sql);
            $message = $query->execute([
//                'table' => $this->table,
                'id' => $this->id,
                'title' => $this->title,
                'description' => $this->description
            ]) ? 'Элемент успешно обновлен': 'Элемент не обновлен';
        }
        else {
            $sql = 'INSERT INTO datatree_alg02 (title, description) VALUES (:title, :description)';
            $query = $this->_db->prepare($sql);
            $message = $query->execute([
                'title' => $this->title,
                'description' => $this->description
            ]) ? 'Элемент успешно добавлен': 'Элемент не добавлен';
            $this->id = $this->_db->lastInsertId();
        }
        $this->changeParent($this->id, $this->parent_id);
        return $message;
    }

    /**
     * Функция валидации формы.
     *
     */
    public function validForm () {
        if (strlen($this->title) < 3)
            $message = 'Название должно быть больше 3 символов';
        elseif (strlen($this->description) < 10)
            $message = 'Описание должно быть больше 10 символов';
        else
            $message = 'ok';
        return $message;
    }

    /**
     * Функция удаления элемента с веткой.
     *
     * @param  int  $id - id элемента
     * @return string - сообщение
     */
    public function delete ($id) {
        if ($id <= 1) return false;
        $deletingId = $this->getTree(1,$id, false)['excludedId'];

        foreach ($deletingId as $item) {
            $sql = 'DELETE FROM datatree_alg02 WHERE id = :id';
            $query = $this->_db->prepare($sql);
            $query->execute([
                'id' => $item
            ]);
        }
        $this->removeFromParent($id);
        return 'Ветка удалена';
    }

    private function getParentByChildrenId ($children_id) {
        $result = $this->_db->query("SELECT * FROM `$this->table` WHERE `children_id` REGEXP '$children_id'");
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    private function removeFromParent ($id) {
        $parent = $this->getParentByChildrenId($id);
        $childrens = $parent['children_id'];
        $childrens = str_replace($id.',', '', $childrens);
        return $this->setParent($parent['id'], $childrens);
    }

    private function changeParent ($id, $parent_id) {
        $parent = $this->getParentByChildrenId($id);
//        print_r($parent);
//        die();
        if ($parent_id == $parent['id']) return false;

        $childrens = $parent['children_id'];
        $childrens = str_replace($id.',', '', $childrens);
        $this->setParent($parent['id'], $childrens);

        $parent = $this->getElement($parent_id);
        $childrens = $parent['children_id'].$id.',';
        echo $parent_id.' '.$parent['id'].' ';
        print_r($childrens);
        echo '<hr>';
        print_r($parent);
//        die();

        return $this->setParent($parent_id, $childrens);
    }

    private function setParent($id, $childrens) {
        $sql = 'UPDATE datatree_alg02 SET children_id = :children_id WHERE id = :id';
        $query = $this->_db->prepare($sql);
        $message = $query->execute([
            'id' => $id,
            'children_id' => $childrens,
        ]) ? true : false;
    }
}
