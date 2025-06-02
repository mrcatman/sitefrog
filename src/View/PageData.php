<?php

namespace Sitefrog\View;

use Sitefrog\Exceptions\OverwriteResponseException;
use Sitefrog\Traits\MagicGetSet;

class PageData {

    use MagicGetSet;
    private string $view;
    private array $params;

    private ?string $overwriteResponse = null;

    public function setOverwriteResponse($response)
    {
        $this->overwriteResponse = $response;
        throw new OverwriteResponseException($response);
    }

    public function getOverwriteResponse()
    {
        return $this->overwriteResponse;
    }


}
