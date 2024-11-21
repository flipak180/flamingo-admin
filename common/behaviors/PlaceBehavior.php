<?php

namespace common\behaviors;

use common\models\Places\Place;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class PlaceBehavior extends Behavior
{
    /**
     * @var ActiveRecord the owner of this behavior
     */
    public $owner;

    /**
     * @var array
     */
    public $places = [];

    /**
     * @var
     */
    public $attribute;

    /**
     * @return void
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function events()
    {

        $events = [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
        ];

        return $events;

    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        return true;
    }

    /**
     * @return true
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function afterSave()
    {
        $places = $this->owner->{$this->attribute};

        if (!is_array($places)) {
            $places = [];
        }

        /** @var Place[] $currentPlaces */
        $currentPlaces = $this->owner->getPlaces()->all();

        foreach ($currentPlaces as $currentPlace) {
            if (!in_array($currentPlace->place_id, $places)) {
                $this->owner->unlink('places', $currentPlace, true);
            }
        }

        foreach ($places as $placeId) {
            $place = Place::findOne($placeId);
            $this->owner->link('places', $place);
        }

        return true;
    }

    /**
     * @return bool
     */
    public function afterFind()
    {
        foreach ($this->owner->getPlaces()->all() as $place) {
            $this->owner->places_field[] = $place->place_id;
        }
        return true;
    }

}
