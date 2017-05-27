<?php

namespace App\Filters;

use Illuminate\Http\Request;

class Filters
{
	protected $request, $builder;

	protected $filters = [];

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	protected function getFilters()
	{
		return $this->request->intersect($this->filters);
	}

	public function apply($builder)
	{
		$this->builder = $builder;

		foreach($this->getFilters() as $filter => $value) {
			if(method_exists($this, $filter)) {
				$this->$filter($value);
			}
		}

		return $builder;

	}	
}