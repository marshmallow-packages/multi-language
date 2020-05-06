<?php

namespace Marshmallow\MultiLanguage\Traits;

use Illuminate\Support\Facades\App;

trait TranslatableRoute
{
	public function route ()
	{
		return '/' . $this->getLocaleString() . $this->routePrefix() . $this->getModelUrl();
	}

	protected function getModelUrl ()
	{
		return $this->slug;
	}

	protected function routePrefix ()
	{
		return '';
	}

	protected function getLocaleString ()
	{
		return App::getLocale();
	}
}