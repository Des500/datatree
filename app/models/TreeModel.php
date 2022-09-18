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
    public function getTree ($id=0, $excludeId = -1, $addRootLevel = true) {
        $query = $this->_db->query("SELECT * FROM `datatree` ORDER BY `id`");
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
        $this->getChildrenTree($id,0,$excludeId);
        if(( $excludeId>0 ) && ( isset($this->treeArray['items'][$excludeId]) )) {
            unset($this->treeArray['items'][$excludeId]);
            array_unshift($this->treeArray['excludedId'], $excludeId);
        }
        return $this->treeArray;
    }

    /**
     * Функция сбора дочерних элементов 1 го уровня.
     *
     * @param  int  $parent_id - id родительского элемента, по которому собираются дочерние (для корня - родитель 0)
     * @return array $items - массив дочерних элементов
     */
    public function getParentChildrens ($parentId = 0) {
        $items =[];
        foreach ($this->queryArray as $key => $item) {
            if($item['parent_id']==$parentId) {
                array_push($items, $item);
                unset($this->queryArray[$key]);
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
    public function getChildrenTree($parent_id = 0, $level = 0, $excludeId = 0) {
        $items = $this->getParentChildrens($parent_id);
        $level ++;
        foreach ($items as $key => $item) {
            if(($parent_id != $excludeId)) {
                $this->treeArray['items'][$item['id']] = [
                    'level' => $level,
                    'item' => $item
                ];
                $this->getChildrenTree($item['id'], $level, $excludeId);
            }
            else {
                array_push($this->treeArray['excludedId'], $item['id']);
                $this->getChildrenTree($item['id'], $level, $item['id']);
            }
        }
    }

    /**
     * Функция сбора дерева данных родительского элемента (рекурсивная).
     *
     * @param  int  $id - id элемента
     */
    public function getElement($id) {
        $result = $this->_db->query("SELECT * FROM `datatree` WHERE `id` = '$id'");
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Функция добавления/обновления элемента.
     *
     * @param  int  $id - id элемента
     */
    public function save() {
        if (!empty($this->getElement($this->id))) {
            $sql = 'UPDATE datatree SET parent_id = :parent_id, title = :title, description = :description WHERE id = :id';
            $query = $this->_db->prepare($sql);
            $message = $query->execute([
                'id' => $this->id,
                'parent_id' => $this->parent_id,
                'title' => $this->title,
                'description' => $this->description
            ]) ? 'Элемент успешно обновлен': 'Элемент не обновлен';
        }
        else {
            $sql = 'INSERT INTO datatree(parent_id, title, description) VALUES (:parent_id, :title, :description)';
            $query = $this->_db->prepare($sql);
            $message = $query->execute([
                'parent_id' => $this->parent_id,
                'title' => $this->title,
                'description' => $this->description
            ]) ? 'Элемент успешно добавлен': 'Элемент не добавлен';
        }
        return $message;
    }

    /**
     * Функция валидации формы.
     *
     * @param  int  $id - id элемента
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
     */
    public function delete ($id) {
        $deletingId = $this->getTree(0,$id, false)['excludedId'];
        foreach ($deletingId as $item) {
            $sql = 'DELETE FROM datatree WHERE id = :id';
            $query = $this->_db->prepare($sql);
            $query->execute([
                'id' => $item
            ]);
        }
        return 'Ветка удалена';
    }
}
