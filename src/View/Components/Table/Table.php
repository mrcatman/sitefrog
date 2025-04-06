<?php

namespace Sitefrog\View\Components\Table;

use Sitefrog\View\Component;

use Sitefrog\View\Table\Table as TableClass;

class Table extends Component
{
    public function __construct(
        public TableClass $table,
    ) {}

    public static function getTemplate(): string
    {
        return 'sitefrog::components.table';
    }

    public function beforeRender(): void
    {

        $this->table->fetchItems();
    }

}
