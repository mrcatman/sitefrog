<?php

namespace Sitefrog\View\Components;

use Sitefrog\View\Component;
use Illuminate\View\View;

class WidgetWrapper extends Component
{
    public function __construct(
        private View $content,
        private ?string $title = null,
    ) {}

    public function render(): View
    {
        return view("sitefrog::components.widget-wrapper", [
            'title' => $this->title,
            'content' => $this->content->render()
        ]);
    }

}
