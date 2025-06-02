<?php
namespace Sitefrog\View;

use Sitefrog\View\Components\Form\FormComponent;
use Sitefrog\View\Form\Form;
use Sitefrog\Facades\PageData;

class FormManager
{
    /**
     * @param Form[] $forms
     */
    private $forms = [];

    private $submitHandlers = [];

    public function register(Form $form)
    {
        $this->forms[$form->getName()] = $form;
        if (isset($this->submitHandlers[$form->getName()])) {
            $form->onSubmit($this->submitHandlers[$form->getName()]);
        }

        if ($form->hasSubmitHandler()) {
            $this->handleFormRequest($form);
        }
    }

    public function onSubmit(string $name, callable $handler)
    {
        if (!isset($this->forms[$name])) {
            $this->submitHandlers[$name] = $handler;
            return;
        }
        $form = $this->get($name);
        $form->onSubmit($handler);
        $this->handleFormRequest($form);
    }

    public function get(string $name)
    {
        if (!isset($this->forms[$name])) {
            throw new \Exception("Form not found: $name");
        }
        return $this->forms[$name];
    }

    protected function handleFormRequest(Form $form): void
    {
        if ($form->getName() != request()->form()) {
            return;
        }
        $formComponent = new FormComponent(
            form: $form,
        );

        $form->submit();
        PageData::setOverwriteResponse($formComponent->tryRender());
    }

}
