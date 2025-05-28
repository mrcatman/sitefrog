<?php

namespace Sitefrog\Repositories;

class DefaultRepository extends Repository {

   public function initialize(string $resource, array $params)
   {
       $this->resource = $resource;

       if (isset($params['permissions_prefix'])) {
           $this->permissions_prefix = $params['permissions_prefix'];
       }
   }

}
