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

        $this->maxHeight();

        $this->width();
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

    public function mainTable($table, $id)
    {
        return $this->withMeta([
            'mainTable' => [
                'table' => $table,
                'id' => $id,
            ],
        ]);
    }
    public function maxHeight($number = 60)
    {
        return $this->withMeta(['maxHeight' => $number]);
    }

    public function width($number = 300)
    {
        return $this->withMeta(['width' => $number]);
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
