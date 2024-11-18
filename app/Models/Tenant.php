<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sprout\Contracts\TenantHasResources;
use Sprout\Database\Eloquent\Concerns\IsTenant;

class Tenant extends Model implements \Sprout\Contracts\Tenant, TenantHasResources
{
    use IsTenant;

    protected $primaryKey = "id";

    protected $keyType = "string";

    public function getTenantIdentifierName(): string
    {
        return 'path';
    }

    public function getTenantResourceKey(): string
    {
        return $this->getAttribute($this->getTenantResourceKeyName());
    }

    public function getTenantResourceKeyName(): string
    {
        return 'id';
    }
}
