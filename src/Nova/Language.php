<?php

namespace Marshmallow\MultiLanguage\Nova;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;

class Language extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Marshmallow\MultiLanguage\Models\Language';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'label';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'label',
    ];

    public static $group = 'Settings';

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Text::make('Code')->withMeta(['extraAttributes' => [
                'placeholder' => 'Eq: nl,en,de', ],
            ])->rules('required', 'min:2', 'max:2'),
            Text::make('Label')->withMeta(['extraAttributes' => [
                'placeholder' => 'Eq: Nederlands,English,Deutsch', ],
            ])->rules('required'),
            Image::make('Flag'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
