<?php

namespace Gii\ModuleMedicService\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Gii\ModuleMedicService\Enums\MedicServiceStatus;
use Gii\ModuleMedicService\Resources\MedicService\{ViewMedicService, ShowMedicService};
use Gii\ModuleService\Concerns\HasService;
use Zahzah\LaravelHasProps\Concerns\HasProps;
use Zahzah\LaravelSupport\Models\BaseModel;

class MedicService extends BaseModel {
    use HasProps, SoftDeletes, HasService;

    protected $list                 = ['id','parent_id','name','flag','status'];
    protected $show                 = [];
    public static $__flags_service  = [];

    protected static function booted(): void{
        parent::booted();
        static::created(function($query){
            $parent    = $query->parent;
            $parent_id = null;
            if (isset($parent)) $parent_id = $parent->service->getKey();
            $service = $query->service()->updateOrCreate([
                'parent_id' => $parent_id,
                'name'      => $query->name,
            ],[
                'status' => MedicServiceStatus::ACTIVE->value
            ]);
        });
    }

    public function toViewApi(){
        return new ViewMedicService($this);
    }

    public function toShowApi(){
        return new ShowMedicService($this);
    }

    public function scopeActive($builder){
        return $builder->where('status',MedicServiceStatus::ACTIVE->value);
    }

    public function scopesetIdentityFlags($builder,array $flags){
        self::$__flags_service = $flags;
        return $builder->flagIn(self::$__flags_service);
    }

    public function service(){return $this->morphOneModel('Service','reference');}
    public function priceComponent(){return $this->morphOneModel('PriceComponent','model');}
    public function priceComponents(){return $this->morphManyModel('PriceComponent','model');}
}
