<?php

namespace Gii\ModuleMedicService\Concerns;

trait HasService{
    public function initializeHasService(){
        $this->ServiceModel()::setIdentityFlags($this->__flags_Service);
    }
    
    public function service(){return $this->morphOneModel('Service','reference');}

}
