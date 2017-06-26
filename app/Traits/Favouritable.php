<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Favourite;

trait Favouritable
{

    public static function bootFavouritable()
    {
        static::deleting(function($model) {
            $model->favourites->each->delete();
        });
    }

    public function favourites()
    {
    	return $this->morphMany(Favourite::class, 'favourited');
    }

    public function favourite()
    {
        $attributes = ['user_id' => auth()->id()];

        if (!$this->favourites()->where($attributes)->exists()) {
            return $this->favourites()->create($attributes);
        }

    }

    public function isFavourited()
    {
        return (!! $this->favourites->where('user_id', auth()->id())->count());
    }

    public function getIsFavouritedAttribute()
    {
        return $this->isFavourited();
    }

    public function getFavouritesCountAttribute()
    {
        return $this->favourites->count();
    }

    public function unfavourite()	
    {
        $attributes = ['user_id' => auth()->id()];

        //doing this because we are using record activity trait on reply
        //which listens to the delete event and delete all the associated activity  

        // $this->favourites()->where($attributes)->get()->each(function($favourite) {
        //     $favourite->delete();
        // });

        //this is the shorthand for the above method.
        //also known as higher order collection

        $this->favourites()->where($attributes)->get()->each->delete();

    }
}