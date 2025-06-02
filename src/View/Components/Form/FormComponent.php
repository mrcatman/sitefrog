<?php

namespace Sitefrog\View\Components\Form;

use Illuminate\Support\Collection;
use Sitefrog\Facades\FormManager;
use Sitefrog\View\Component;

use Sitefrog\View\Form\Form;

class FormComponent extends Component
{
    public function __construct(
        public ?Form $form = null,
        public ?string $name = null,
        public array | Collection | null $fields = [],
        public array | Collection | null $config = [],
    ) {}

    public static function getTemplate(): string {
        return 'sitefrog::components.form';
    }

    public function getChildren(): array | Collection | null
    {
        return $this->form->getFields();
    }

    public function beforeRender(): void
    {
        if (!$this->form) {
            $this->form = new Form($this->name, $this->fields, $this->config);
            FormManager::register($this->form);
        }
    }

}
