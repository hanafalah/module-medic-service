<?php

namespace Hanafalah\ModuleMedicService\Schemas;

use Hanafalah\ModuleMedicService\Contracts;
use Hanafalah\ModuleMedicService\Enums\MedicServiceStatus;
use Hanafalah\ModuleMedicService\Resources\MedicService\{ViewMedicService, ShowMedicService};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Hanafalah\LaravelSupport\Supports\PackageManagement;
use Hanafalah\ModuleTransaction\Contracts\PriceComponent;

class MedicService extends PackageManagement implements Contracts\MedicService
{
    protected array $__guard   = ['id', 'name', 'flag'];
    protected array $__add     = ['name', 'parent_id', 'flag', 'status'];
    protected string $__entity = 'MedicService';
    public static $medic_service_model;

    protected array $__resources = [
        'view' => ViewMedicService::class,
        'show' => ShowMedicService::class
    ];

    public function createPriceComponent($medicService, $service, $attributes)
    {
        $price_component_schema = $this->schemaContract('price_component');
        return $price_component_schema->prepareStorePriceComponent([
            'model_id'          => $medicService->getKey(),
            'model_type'        => $medicService->getMorphClass(),
            'service_id'        => $service->getKey(),
            'tariff_components' => $attributes['tariff_components']
        ]);
    }

    public function prepareUpdateMedicService(?array $attributes = null): Model
    {
        $attributes ??= \request()->all();

        if (!isset($attributes['id'])) throw new \Exception('MedicService id is required');
        $service = $this->ServiceModel()->findOrFail($attributes['id']);
        $service->status      = $attributes['status'];

        $medicService         = $service->reference;
        $medicService->status = $attributes['status'];

        $medicService->save();
        $service->save();

        if (isset($attributes['tariff_components']) && count($attributes['tariff_components']) > 0) {
            $this->createPriceComponent($medicService, $service, $attributes);
        } else {
            $service->priceComponents()->delete();
        }
        return static::$medic_service_model = $medicService;
    }

    public function updateMedicService(): array
    {
        return $this->transaction(function () {
            return $this->showMedicService($this->prepareUpdateMedicService());
        });
    }

    public function getMedicService(): mixed
    {
        return static::$medic_service_model;
    }

    public function showUsingRelation(): array
    {
        return ['service.priceComponents.tariffComponent'];
    }

    public function prepareShowMedicService(?Model $model = null, ?array $attributes = null)
    {
        $attributes ??= request()->all();

        $model ??= $this->getMedicService();
        if (!isset($model)) {
            $id = $attributes['id'] ?? null;
            if (!isset($id)) throw new \Exception('MedicService id is required');

            $model = $this->MedicServiceModel()->with($this->showUsingRelation())->findOrFail($id);
        } else {
            $model->load($this->showUsingRelation());
        }
        return static::$medic_service_model = $model;
    }

    public function showMedicService(?Model $model = null): array
    {
        return $this->transforming($this->__resources['show'], function () use ($model) {
            return $this->prepareShowMedicService($model);
        });
    }

    public function addOrChange(?array $attributes = []): self
    {
        $medic_service = $this->updateOrCreate($attributes);
        if (!isset($medic_service->service)) {
            $parent    = $medic_service->parent;
            if (isset($parent)) {
                if (!isset($parent->service)) {
                    $parent_service = $parent->service()->updateOrCreate([
                        'name'      => $parent->name,
                    ], [
                        'status' => MedicServiceStatus::ACTIVE->value
                    ]);
                } else {
                    $parent_service = $parent->service;
                }
                $parent_id = $parent_service->getKey();
            } else {
                $parent_id = null;
            }

            $medic_service->service()->updateOrCreate([
                'parent_id' => $parent_id ?? null,
                'name'      => $medic_service->name,
            ], [
                'status' => MedicServiceStatus::ACTIVE->value
            ]);
        }
        return $this;
    }

    protected function medicService($conditionals = []): Builder
    {
        return $this->MedicServiceModel()->with(['service.priceComponents.tariffComponent'])->conditionals($conditionals)->orderBy('name', 'asc');
    }
}
