<?php

namespace Gii\ModuleMedicService\Enums;

enum MedicServiceFlag: string{
    case OUTPATIENT      = 'OUTPATIENT';
    case MCU             = 'MCU';
    case INPATIENT       = 'INPATIENT';
    case VERLOS_KAMER    = 'VK';
    case OPERATING_ROOM  = 'OR'; //OPRASI
    case EMERGENCY_UNIT  = 'EU'; //UGD
    case ICU             = 'ICU';
    case NICU            = 'NICU';
    case LABORATORY      = 'LABORATORY';
    case RADIOLOGY       = 'RADIOLOGY';
    case ADMINISTRATION  = 'ADM';
    case PHARMACY        = 'PHARMACY';
    case OTHER           = 'OTHER';
    case MEDICAL_RECORD  = 'MR';
}