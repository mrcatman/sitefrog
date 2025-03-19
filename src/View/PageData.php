<?php

namespace Sitefrog\View;

class PageData {

    private string $view;
    private array $params;

    public function setView(string $view)
    {
        $this->view = $view;
    }

    public function getView()
    {
        return $this->view;
    }

    public function setParams(array $params)
    {
        $this->params = $params;
    }

    public function getParams()
    {
        return $this->params;
    }

}
