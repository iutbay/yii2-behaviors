<?php

namespace iutbay\yii2behaviors;

use Yii;
use yii\base\Event;
use yii\db\ActiveRecord;

/**
 * RelatedAttributeBehavior.
 * 
 * @property ActiveRecord $owner
 */
class RelatedAttributeBehavior extends \yii\base\Behavior
{

    public $relations = [];

    private $_relations = [];
    private $_relatedAttributes;

    /**
     * Make [[$attributes]] writeable
     */
    public function __set($name, $value)
    {
        $this->setRelatedAttribute($name, $value);
    }

    /**
     * Make [[$attributes]] readable
     * @inheritdoc
     */
    public function __get($name)
    {
        return $this->getRelatedAttribute($name);
    }

    /**
     * Expose [[$attributes]] writable
     * @inheritdoc
     */
    public function canSetProperty($name, $checkVars = true)
    {
        return $this->isRelatedAttribute($name) || parent::canSetProperty($name, $checkVars);
    }

    /**
     * Expose [[$attributes]] readable
     * @inheritdoc
     */
    public function canGetProperty($name, $checkVars = true)
    {
        return $this->isRelatedAttribute($name) || parent::canGetProperty($name, $checkVars);
    }

    /**
     * @return boolean
     */
    private function isRelatedAttribute($name)
    {
        $relatedAttributes = $this->getRelatedAttributes();
        return isset($relatedAttributes[$name]);
    }

    /**
     * Set related attribute
     */
    private function setRelatedAttribute($name, $value)
    {
        $relatedAttributes = $this->getRelatedAttributes();
        $relation = $this->getRelation($relatedAttributes[$name]['relation'], $relatedAttributes[$name]['class']);
        $relation->$name = $value;
    }

    /**
     * Get related attribute
     * @return mixed
     */
    private function getRelatedAttribute($name)
    {
        $relatedAttributes = $this->getRelatedAttributes();
        $relation = $this->getRelation($relatedAttributes[$name]['relation'], $relatedAttributes[$name]['class']);
        return $relation->$name;
    }

    /**
     * @param string $name
     * @param string $class
     * @return ActiveRecord
     */
    private function getRelation($name, $class)
    {
        $relation = $this->owner->__get($name);
        if ($relation === null) {
            $relation = new $class;
            $this->owner->populateRelation($name, $relation);
        }
        $this->_relations[$name] = $relation;
        return $relation;
    }

    /**
     * @return array
     */
    public function getRelatedAttributes()
    {
        if (!isset($this->_relatedAttributes)) {
            $relatedAttributes = [];

            foreach ($this->relations as $key => $r) {
                $getRelation = 'get' . $key;
                if (!method_exists($this->owner, $getRelation))
                    throw new \yii\base\InvalidConfigException("Relation {$key} not found.");

                /* @var $activeQuery \yii\db\ActiveQuery */
                $activeQuery = $this->owner->$getRelation();

                if (is_array($r['attributes'])) {
                    foreach ($r['attributes'] as $a) {
                        $relatedAttributes[$a] = [
                            'relation' => $key,
                            'class' => $activeQuery->modelClass,
                        ];
                    }
                } else if ($r['attributes'] == '*') {
                    /* @var $relationModel ActiveRecord */
                    $relationModel = new $activeQuery->modelClass;
                    $exclude = array_merge($this->owner->attributes(), $relationModel->primaryKey());
                    foreach ($relationModel->attributes as $a => $value) {
                        if (!in_array($a, $exclude)) {
                            $relatedAttributes[$a] = [
                                'relation' => $key,
                                'class' => $activeQuery->modelClass,
                            ];
                        }
                    }
                }
            }

            $this->_relatedAttributes = $relatedAttributes;
        }

        return $this->_relatedAttributes;
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
        ];
    }

    /**
     * @param Event $event
     */
    public function afterInsert($event)
    {
        $this->saveRelations();
    }

    /**
     * @param Event $event
     */
    public function afterUpdate($event)
    {
        $this->saveRelations();
    }

    /**
     * Save relations
     */
    private function saveRelations()
    {
        foreach ($this->_relations as $key => $relation) {
            if ($relation->isNewRecord) {
                $this->owner->link($key, $relation);
            } else {
                $relation->save();
            }
        }
    }

}
