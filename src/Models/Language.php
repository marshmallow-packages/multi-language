<?php

namespace Marshmallow\MultiLanguage\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
	public function getRouteKeyName()
	{
	    return 'code';
	}
}
