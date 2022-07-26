<?php

namespace App\Model\Behavior;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\Utility\Text;
use Cake\Filesystem\Folder;
use Cake\ORM\Table;
use Cake\Database\Expression\QueryExpression;

/**
 * Position Behavior.
 *
 * データ並び順を設定
 *
 */
class PositionBehavior extends Behavior {
    /**
     * Defaults
     *
     * @var array
     */
    protected $_defaultConfig = array(
        'field' => 'position',
        'group' => array(),
        'groupMove' => false,
        'order' => 'ASC',
        'contain' => []
    );

    protected $_old_position = 0;
    protected $_old_group_conditions = array();

    public function initialize(array $config) {
        $Model = $this->getTable();
        $this->settings[$Model->getAlias()] = $config + $this->_defaultConfig;
    }

    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options) {
        $Model = $event->getSubject();

        extract($this->settings[$Model->getAlias()]);
        if ($this->enablePosition($Model) && !empty($group) && $this->enableGroupMove($Model)) {
            // 保存前のデータ取得
            $primary_key = $Model->getPrimaryKey();
            if ($entity->has($primary_key)) {
                $query = $Model->find()->where(array($primary_key => $entity->get($primary_key)))->contain([]);

                if (!$query->isEmpty()) {
                    $old = $query->first();
                    // グループ変更チェック
                    $_isGroupUpdated = false;
                    foreach ($group as $_col) {
                        if ($entity->has($primary_key) && $entity->has($_col)) {
                            if ($entity->get($_col) != $old->get($_col)) {
                                $_isGroupUpdated = true;
                                break;
                            }
                        }
                    }
                    if ($_isGroupUpdated) {
                        foreach ($group as $_col) {
                            if ($old->has($_col)) {
                                $this->_old_group_conditions[$_col] = $old->get($_col);
                                $this->_old_position = $old->get($field);
                            }
                        }
                        $entity->set($field, 0);
                    }
                }
            }
        }

        return true;
    }

    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options) {
        $Model = $event->getSubject();

        extract($this->settings[$Model->getAlias()]);

        // $Model->eventManager()->dispatch($event);
        // 他のafterSaveが効かないようにする
        // $Model->eventManager()->off('Model.afterSave');

        if ($entity->isNew() || (array_key_exists('second', $options) && $options['second'])) {
            if ($this->enablePosition($Model)) {
                $primary_key = $Model->getPrimaryKey();
                $position_field = $field;
                $cond = $this->groupConditions($Model, $entity->id);

                $r = false;

                $save = array();
                if (strtoupper($order) === 'DESC') {
                    $query = $Model->find()->where($cond)->contain($contain);
                    $count = $query->count();
                    $save[$position_field] = $count;
                    $cond = [$primary_key => $entity->id];
                } else {
                    // pr(var_dump($entity));
                    $save[] = new QueryExpression($position_field . ' = ' . $position_field . ' + 1');
                }
                if ($cond) {
                    $r = $Model->updateAll($save, $cond);
                } else {
                    $r = $Model->updateAll($save, $cond);
                }
            }
        } else {
            if ($this->enablePosition($Model) && !empty($group) && !empty($this->_old_group_conditions)) {
                $position_field = $field;
                // 保存前のグループの並び順
                $this->_old_group_conditions[$position_field . ' >'] = $this->_old_position;
                $expression = new QueryExpression($position_field . ' = ' . $position_field . ' - 1');
                $Model->updateAll(
                    array($expression),
                    $this->_old_group_conditions
                );
                // 保存後のグループの並び順
                $options['second'] = 'on';
                return $this->afterSave($event, $entity, $options);
            }
        }
    }

    public function beforeDelete(Event $event, EntityInterface $entity, ArrayObject $options) {
        $Model = $event->getSubject();
        extract($this->settings[$Model->getAlias()]);
        if ($this->enablePosition($Model)) {
            $r = $this->movePosition($entity->id, 'bottom');
        }
        return true;
    }

    /**
     * グループ設定ありの並び順変更の有無
     * @param  Model  $Model [description]
     * @return [type]        [description]
     */
    public function enableGroupMove(Table $Model) {
        extract($this->settings[$Model->getAlias()]);
        return $groupMove;
    }

    /**
     * 並び替えの有無
     *
     * */
    public function enablePosition(Table $Model) {
        extract($this->settings[$Model->getAlias()]);

        $columns = $Model->getSchema()->columns();

        return ($field && in_array($field, $columns));
    }

    /**
     * 並び順グループ設定
     *
     * */
    public function groupConditions(Table $Model, $id) {
        $model_name = $Model->getAlias();

        extract($this->settings[$model_name]);
        $cond = array();

        if ($group && $id) {
            $group = (array) $group;

            $_cond = array($model_name . '.' . $Model->getPrimaryKey() => $id);
            $query = $Model->find()->where($_cond)->contain([]);

            if (isset($custom_group)) {
                return $custom_group($query);
            }

            if (!$query->isEmpty()) {
                $data = $query->first()->toArray();
                foreach ($group as $column) {
                    if (isset($data[$column])) {
                        $cond[$model_name . '.' . $column] = $data[$column];
                    }
                }
            }
        }

        return $cond;
    }

    /**
     * The display position of data is changed.
     * 並び順を変更する
     *
     * @since 13/02/07 11:37
     * @param  Integer    $id  primary key
     * @param  String    $dir Moving direction
     *                   [top, bottom, up, down]
     * @return bool
     */
    public function movePosition($id, $dir, $options = array()) {
        $Model = $this->_table;

        extract($this->settings[$Model->getAlias()]);

        if (!$this->enablePosition($Model)) {
            return false;
        }

        $conditions = $this->groupConditions($Model, $id);

        $model_name = $Model->getAlias();
        $position_field = $field;
        $primary_key = $Model->getPrimaryKey();
        if ($Model->exists([$primary_key => $id])) {
            $data = $Model->get($id)->toArray();
            $position = $data[$field];

            if ($dir === 'top') {
                $expression = new QueryExpression($position_field . ' = ' . $position_field . ' + 1');
                $Model->updateAll(array($expression), array_merge(array($position_field . ' < ' => $position), $conditions));
                $Model->updateAll(array($field => 1), array($primary_key => $id));
            } elseif ($dir === 'bottom') {
                $query = $Model->find()->where($conditions)->contain($contain);
                $count = $query->count();

                $expression = new QueryExpression($position_field . ' = ' . $position_field . ' - 1');
                $Model->updateAll(array($expression), array_merge(array($position_field . ' >' => $position), $conditions));
                $Model->updateAll(array($field => $count), array($primary_key => $id));
            } elseif ($dir === 'up') {
                $position = $data[$field];
                if (1 < $position) {
                    $expression = new QueryExpression($position_field . ' = ' . $position_field . ' + 1');
                    $Model->updateAll(array($expression), array_merge(array($position_field => $position - 1), $conditions));
                    $Model->updateAll(array($field => $position - 1), array($primary_key => $id));
                }
            } elseif ($dir === 'down') {
                $query = $Model->find()->where($conditions)->contain($contain);
                $count = $query->count();

                $position = $data[$field];
                if ($position < $count) {
                    $expression = new QueryExpression($position_field . ' = ' . $position_field . ' - 1');
                    $Model->updateAll(array($expression), array_merge(array($position_field => $position + 1), $conditions));
                    $Model->updateAll(array($field => $position + 1), array($primary_key => $id));
                }
            } else {
                return false;
            }

            return true;
        }
    }

    /**
     * 並び替えを再設定
     * */
    public function resetPosition(array $conditions = array()) {
        $Model = $this->_table;

        extract($this->settings[$Model->getAlias()]);
        if ($this->enablePosition($Model)) {
            $model_name = $Model->getAlias();
            $position_field = $field;
            $primary_key = $Model->primaryKey;
            $conditions = $this->groupConditions($Model, $id);

            $position = 1;
            $query = $Model->find()->where($conditions)->contain($contain)->order(array($position_field => $order));
            $data = $query->all()->toArray();

            foreach ($data as $key => $value) {
                if (!empty($value[$Model->getPrimaryKey()])) {
                    $conditions[$primary_key] = $value[$Model->primaryKey];
                    $Model->updateAll(array($position_field => $position), $conditions);
                    ++$position;
                }
            }
        }
    }
}
