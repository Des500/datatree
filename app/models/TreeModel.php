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
    private $itemsArray= [];

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
     * @param  bool  $addRootLevel - добавление/пропуск 0-го уровня в начало результирующего массива
     * @return array $treeArray -> дерево данных
     * [items] - массив дерева данных без исключенных id
     * [excludedId] - массив исключенных id
     */
    public function getTree ($id=0, $excludeId = -1, $addRootLevel = true) {
        $query = $this->_db->query("SELECT * FROM `datatree` ORDER BY `parent_id`");
        $this->itemsArray = $query->fetchAll(PDO::FETCH_ASSOC);

//        foreach ($this->itemsArray as $key => $item) {
//            echo $key.' -> ';
//            print_r($item);
//            echo '<hr>';
//        }
//        $this->getChildrenAsTree();
        $this->getChildrenTree($id,0,$excludeId);

        if($addRootLevel)
            array_unshift($this->treeArray['items'], [
                'level' => 0,
                'item' => [
                    'id' => 0,
                    'title' => 'Корень дерева',
                ]
            ]);

        if(( $excludeId>0 ) && ( isset($this->treeArray['items'][$excludeId]) )) {
            unset($this->treeArray['items'][$excludeId]);
            array_unshift($this->treeArray['excludedId'], $excludeId);
        }
        return $this->treeArray;
    }

    public function getParentChildrens ($parentId = 0) {
        $items =[];
        foreach ($this->itemsArray as $key => $item) {
            if($item['parent_id']==$parentId)
                array_push($items, $item);
        }
        return $items;
    }

    /**
     * Функция сбора дерева данных родительского элемента (рекурсивная).
     *
     * @param  int  $parent_id - id родительского элемента, по которому собираются дочерние (для корня - родитель 0)
     * @param  int  $level - родительский уровень (для корня родитель - 0)
     * @param  int  $excludeId - id элемента, ветку которого требуется исключить (невозможно перенести родительский элемент в ветку дочернего)
     * @return array $treeArray - массив дерева данных
     */
    public function getChildrenTree($parent_id = 0, $level = 0, $excludeId = 0) {
//        $result = $this->_db->query("SELECT * FROM `datatree` WHERE `parent_id` = '$parent_id' ORDER BY `parent_id`");
//        $items = $result->fetchAll(PDO::FETCH_ASSOC);
        $items = $this->getParentChildrens($parent_id);

        $level ++;
        foreach ($items as $key => $item) {
            if(($parent_id != $excludeId)) {
                $this->treeArray['items'][$item['id']] = [];
                $this->treeArray['items'][$item['id']]['level'] = $level;
                $this->treeArray['items'][$item['id']]['item'] = $item;
//                echo 'level-> '.$level . ' parent_id-> ' . $parent_id . ' exclude-> '.$excludeId.' -> ';
//                print_r($this->treeArray['items'][$item['id']]);
//                echo '<hr>';
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
