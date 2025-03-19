<?php

namespace Sitefrog\View;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LayoutManager
{
    private ?string $default = null;

    public function getForRequest(Request $request)
    {
        return $this->default;
    }

    public function setDefault($path)
    {
        $this->default = $path;
    }

}
