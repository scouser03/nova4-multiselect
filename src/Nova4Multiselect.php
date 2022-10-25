<?php

namespace Scouser03\Nova4Multiselect;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class Nova4Multiselect extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova4-multiselect';

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->label();
        
        $this->inputId();
    }


    public function model($model)
    {
        $query = $model::query()->get();
        return $this->withMeta(['data' => $query]);
    }

    public function query($query)
    {
        return $this->withMeta(['data' => $query]);
    }

    public function table($table)
    {
        return $this->withMeta(['table' => $table]);
    }

    public function indexUpdateable($status)
    {
        return $this->withMeta(['indexUpdateable' => $status]);
    }

    public function label($label = 'name')
    {
        return $this->withMeta(['label' => $label]);
    }

    public function inputId($id = 'id')
    {
        return $this->withMeta(['inputId' => $id]);
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $requestValue = $request[$requestAttribute];
        $tagNames = explode(',', $requestValue);
        $tagNames = array_filter($tagNames);

        $class = get_class($model);

        $class::saved(function ($model) use ($tagNames, $attribute) {
            $model->$attribute()->sync($tagNames);
        });
    }
}
