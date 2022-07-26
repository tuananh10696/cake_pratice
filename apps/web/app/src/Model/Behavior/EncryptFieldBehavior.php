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
use Cake\Event\EventManager;
use Cake\Utility\Security;

/**
 * 暗号化するカラムの型はvarbinaryにすること。
 */

class EncryptFieldBehavior extends Behavior {
    protected $_defaultConfig = array(
        'fields' => [],
    );

    public function initialize(array $config) {
        $Model = $this->getTable();
        $this->settings[$Model->getAlias()] = $config + $this->_defaultConfig;
    }

    public function beforeSave($event, $entity, $options) {
        $Model = $event->getSubject();
        $security_key = Security::salt();
        $fields = $this->settings[$Model->getAlias()]['fields'];

        foreach ($fields as $fieldName) {
            if ($entity->has($fieldName)) {
                $expr = $Model->query()->newExpr("AES_ENCRYPT('" . $entity[$fieldName] . "', '" . $security_key . "')");
                $entity->set($fieldName, $expr);
            }
        }
        return true;
    }

    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary) {
        $Model = $event->getSubject();
        $security_key = Security::salt();
        $fields = $this->settings[$Model->getAlias()]['fields'];
        $this->fields = $fields;
        $this->modelName = $Model->getAlias();
        // select
        // $select = array_combine($Model->schema()->columns(),$Model->schema()->columns());
        $select = array_map(function ($row) {
            return $this->modelName . '.' . $row;
        }, $Model->schema()->columns());

        foreach ($fields as $col) {
            if ($primary) {
                $select[$col] = 'CONVERT(AES_DECRYPT(' . $Model->getAlias() . '.' . $col . ', "' . $security_key . '") using utf8)';
            } else {
                // アソシエーション先の場合
                $select[$Model->getAlias() . '__' . $col] = 'CONVERT(AES_DECRYPT(' . $Model->getAlias() . '.' . $col . ', "' . $security_key . '") using utf8)';
            }
        }
        $query->select($select);

        return $query->formatResults(function (\Cake\Collection\CollectionInterface $results) {
            return $results->map(function ($row) {
                // AfterFindと同等
                foreach ($this->fields as $col) {
                    if (!empty($row->{$col})) {
                        $row->{$col} = stream_get_contents($row->{$col});
                    }
                }
                return $row;
            });
        });
    }
}
