<?php

namespace Sitefrog\View\Form\Builder;
use Illuminate\Support\Collection;

class FormBuilder
{
    private Collection $fieldBuilders;
    public function __construct()
    {
        $this->fieldBuilders = new Collection();
    }


    public function registerFieldBuilder($fieldBuilder)
    {
        $this->fieldBuilders[$fieldBuilder::getName()] = $fieldBuilder;
    }

    public function getFieldBuilders()
    {
        return $this->fieldBuilders;
    }

    public function get()
    {

    }

}
