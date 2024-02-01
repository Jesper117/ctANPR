<?php

class PlateService
{
    private function getSidecodeLicenseplate($licensePlate) {
        $arrSC = array();
        $scUitz = "";
        $licensePlate = strtoupper(str_replace('-', '', $licensePlate));
        $arrSC[0] = '/^[a-zA-Z]{2}\d{2}\d{2}$/'; // 1 XX-99-99
        $arrSC[1] = '/^\d{2}\d{2}[a-zA-Z]{2}$/'; // 2 99-99-XX
        $arrSC[2] = '/^\d{2}[a-zA-Z]{2}\d{2}$/'; // 3 99-XX-99
        $arrSC[3] = '/^[a-zA-Z]{2}\d{2}[a-zA-Z]{2}$/'; // 4 XX-99-XX
        $arrSC[4] = '/^[a-zA-Z]{2}[a-zA-Z]{2}\d{2}$/'; // 5 XX-XX-99
        $arrSC[5] = '/^\d{2}[a-zA-Z]{2}[a-zA-Z]{2}$/'; // 6 99-XX-XX
        $arrSC[6] = '/^\d{2}[a-zA-Z]{3}\d{1}$/'; // 7 99-XXX-9
        $arrSC[7] = '/^\d{1}[a-zA-Z]{3}\d{2}$/'; // 8 9-XXX-99
        $arrSC[8] = '/^[a-zA-Z]{2}\d{3}[a-zA-Z]{1}$/'; // 9 XX-999-X
        $arrSC[9] = '/^[a-zA-Z]{1}\d{3}[a-zA-Z]{2}$/'; // 10 X-999-XX
        $arrSC[10] = '/^[a-zA-Z]{3}\d{2}[a-zA-Z]{1}$/'; // 11 XXX-99-X
        $arrSC[11] = '/^[a-zA-Z]{1}\d{2}[a-zA-Z]{3}$/'; // 12 X-99-XXX
        $arrSC[12] = '/^\d{1}[a-zA-Z]{2}\d{3}$/'; // 13 9-XX-999
        $arrSC[13] = '/^\d{3}[a-zA-Z]{2}\d{1}$/'; // 14 999-XX-9

        // Diplomaten kentekens uitgezonderd
        $scUitz = '/^CD[ABFJNST]\d{1,3}$/'; // bijvoorbeeld: CDB1 or CDJ45
        for ($i = 0; $i < count($arrSC); $i++) {
            if (preg_match($arrSC[$i], $licensePlate)) {
                return $i + 1;
            }
        }
        if (preg_match($scUitz, $licensePlate)) {
            return 'CD';
        }
        return false;
    }

    public function FormatLicensePlate($licensePlate) {
        $sidecode = $this->getSidecodeLicenseplate($licensePlate);
        $licensePlate = strtoupper(str_replace('-', '', $licensePlate));
        if ($sidecode <= 6 && $sidecode > 0) {
            return substr($licensePlate, 0, 2) . '-' . substr($licensePlate, 2, 2) . '-' . substr($licensePlate, 4, 2);
        }
        if ($sidecode == 7 || $sidecode == 9) {
            return substr($licensePlate, 0, 2) . '-' . substr($licensePlate, 2, 3) . '-' . substr($licensePlate, 5, 1);
        }
        if ($sidecode == 8 || $sidecode == 10) {
            return substr($licensePlate, 0, 1) . '-' . substr($licensePlate, 1, 3) . '-' . substr($licensePlate, 4, 2);
        }
        if ($sidecode == 11 || $sidecode == 14) {
            return substr($licensePlate, 0, 3) . '-' . substr($licensePlate, 3, 2) . '-' . substr($licensePlate, 5, 1);
        }
        if ($sidecode == 12 || $sidecode == 13) {
            return substr($licensePlate, 0, 1) . '-' . substr($licensePlate, 1, 2) . '-' . substr($licensePlate, 3, 3);
        }

        return $licensePlate;
    }
}