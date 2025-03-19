<?php

namespace Sitefrog\View\Components;

use Sitefrog\View\Component;
use Illuminate\View\View;

class Error extends Component
{
    public function __construct(
        private \Exception $exception
    ) {}

    public function render(): View
    {
        return view('sitefrog::components.error', [
            'message' => $this->exception->getMessage()
        ]);
    }
}
