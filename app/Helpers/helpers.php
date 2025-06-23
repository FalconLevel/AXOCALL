<?php

use App\Helpers\FormatHelper;
use App\Helpers\ApiHelper;
use App\Helpers\KeysHelper;
use App\Helpers\ValidatorHelper;
use App\Helpers\GlobalHelper;
use App\Helpers\ResponseHelper;

function formatHelper() {
    return new FormatHelper();
}

function apiHelper() {
    return new ApiHelper();
}

function keysHelper() {
    return new KeysHelper();
}

function validatorHelper() {
    return new ValidatorHelper();
}

function globalHelper() {
    return new GlobalHelper();
}

function responseHelper() {
    return new ResponseHelper();
}

function logInfo(string $message) {
    return globalHelper()->logInfo($message);
}