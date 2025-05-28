<?php

namespace Sitefrog\View;
use Illuminate\Foundation\Application;
use Sitefrog\Repositories\DefaultRepository;
use Sitefrog\Repositories\Repository;

class RepositoryManager
{
    private $repositories;

    public function __construct(
        private Application $app
    )
    {
    }

    public function register($resource, $repository)
    {
        $this->repositories[$resource] = $this->app->make($repository);
    }

    public function registerDefault($resource, array $params)
    {
        $repository = new DefaultRepository();
        $repository->initialize($resource, $params);
        $this->repositories[$resource] = $repository;
    }


    public function getFor($resource): Repository
    {
        if (!isset($this->repositories[$resource])) {
            throw new \Exception("Repository for $resource not found");
        }
        return $this->repositories[$resource];
    }


}
