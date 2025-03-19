<?php

namespace Sitefrog\View\Form;

use Sitefrog\View\Component;
use Illuminate\View\View;
use Sitefrog\Traits\MagicGetSet;
use Sitefrog\View\Components\Form\FieldWrapper;

class Field extends Component
{
    use MagicGetSet;
    protected string $view;
    protected $shouldWrap = true;

    private array $errors = [];

    public function __construct(
        protected string $name,
        protected ?string $value = null,
        protected ?string $label = null,

        protected ?array $validationRules = []
    )
    {
    }

    public function hasErrors()
    {
        return count($this->errors) > 0;
    }

    public function render(): View
    {
        if ($this->shouldWrap) {
            return (new FieldWrapper($this))->render();
        }
        return $this->renderBase();
    }

    public function renderBase(): View
    {
        return view($this->view, [
            'field' => $this
        ]);
    }


}
