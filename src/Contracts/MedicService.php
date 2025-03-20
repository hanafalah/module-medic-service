<?php

namespace Gii\ModuleMedicService\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Zahzah\LaravelSupport\Contracts\DataManagement;

interface MedicService extends DataManagement{
    public function createPriceComponent($medicService, $service, $attributes);
    public function prepareUpdateMedicService(? array $attributes = null): Model;
    public function updateMedicService(): array;
    public function getMedicService(): mixed;
    public function showUsingRelation(): array;
    public function prepareShowMedicService(? Model $model = null, ? array $attributes = null);
    public function showMedicService(? Model $model = null): array;
    public function addOrChange(? array $attributes=[]): self;    
}