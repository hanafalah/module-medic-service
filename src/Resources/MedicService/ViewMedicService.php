<?php

namespace Gii\ModuleMedicService\Resources\MedicService;

use Illuminate\Http\Request;
use Zahzah\LaravelSupport\Resources\ApiResource;

class ViewMedicService extends ApiResource{
    public function toArray(Request $request): array
    {
        $arr = [
            'id'        => $this->id,
            'parent_id' => $this->parent_id,
            'name'      => $this->name,
            'flag'      => $this->flag,
            'status'    => $this->status,
            'service'   => $this->relationValidation('service',function(){
                return $this->service->toViewApi();
            })
        ];
        
        return $arr;
    }
}