<?php

namespace App\Filters;

use App\User;

class ThreadFilters extends Filters
{
	protected $filters = ['by', 'popular', 'unanswered'];

	//Filters the query by given username

	public function by($username)
	{
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
	}

    /**
     * @return mixed
     */
    public function popular()
	{

		$this->builder->getQuery()->orders = [];

		return $this->builder->orderBy('reply_count', 'desc');
	}

	public function unanswered()
    {
        return $this->builder->where('reply_count', 0);
    }
}
