<?php

namespace App\Service\Midtrans;

class Midtrans {
    protected $serverKey;
    protected $isProduction;
    protected $isSanitized;
    protected $is3ds;

    public function __construct()
    {
        $this->serverKey = config("midtrans.server_key");
        $this->isProduction = config("midtrans.is_production");
        $this->isSanitized = config("midtrans.is_sanitized");
        $this->is3ds = config("midtrans.is3ds");

        $this->_configureMidtrans();
    }

    public function _configureMidrtrans()
    {
        config::$serverKey = $this->serverKey;
        config::$isProduction = $this->isProduction;
        config::$isSanitized = $this->isSanitized;
        config::$is3ds = $this->is3ds;
    }
}