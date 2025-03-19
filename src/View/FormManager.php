<?php

namespace Sitefrog\View;
use Sitefrog\View\Form\Form;

class FormManager
{
    private $forms = [];

    public function register(Form $form)
    {
        $this->forms[$form->getName()] = $form;
    }

    public function get($name)
    {
        if (!isset($this->forms[$name])) {
            throw new \Exception("Form not found: $name");
        }
        return $this->forms[$name];
    }

}
