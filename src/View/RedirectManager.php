<?php

namespace Sitefrog\View;

class RedirectManager
{
    private string | null $url = null;

    public function get()
    {
        return $this->url;
    }

    public function set($url = null)
    {
        $this->url = $url;
    }

}
