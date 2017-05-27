<?php

namespace App\Traits;

use App\Activity;

trait RecordActivity
{

	protected static function bootRecordActivity()
	{
		if(auth()->guest()) return ;
		
		foreach(static::getActivitiesToRecord() as $event) {
	        static::created(function($model) use ($event) {
	            $model->recordActivityCreation($event);
	        });			
		}

        static::deleting(function($model) {
            $model->activity()->delete();
        });

	}

	protected static function getActivitiesToRecord()
	{
		return ['created'];
	}

    protected function recordActivityCreation($event)
    {
    	$this->activity()->create([
            'type' => $this->getActivitytype($event),
            'user_id' => auth()->id()
    	]);
    }

    protected function activity()
    {
    	return $this->morphMany('App\Activity', 'subject');
    }

    protected function getActivitytype($event)	
    {
    	$type = strtolower((new \ReflectionClass($this))->getShortName());

    	return "{$type}_{$event}";
    }
}