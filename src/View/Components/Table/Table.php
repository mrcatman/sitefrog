<?php

namespace Sitefrog\View\Components\Table;

use Sitefrog\View\Component;
use Illuminate\View\View;

use Sitefrog\View\Table\Table as TableClass;

class Table extends Component
{
    public function __construct(
        private TableClass $table,
    ) {}

    public function render(): View
    {
        $this->table->fetchItems();
        return view('sitefrog::components.table', [
            'table' => $this->table,
        ]);
    }


}
