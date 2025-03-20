<?php

namespace Hanafalah\ModuleMedicService\Resources\MedicService;

use Illuminate\Http\Request;

class ShowMedicService extends ViewMedicService
{
    public function toArray(Request $request): array
    {
        $arr = [
            'service'   => $this->relationValidation('service', function () {
                return $this->service->toShowApi();
            })
        ];
        $arr = $this->mergeArray(parent::toArray($request), $arr);

        return $arr;
    }
}
