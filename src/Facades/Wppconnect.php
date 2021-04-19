<?php

declare(strict_types=1);

namespace WPPConnectTeam\Wppconnect\Facades;

use Illuminate\Support\Facades\Facade;

class Wppconnect extends Facade {

	protected static function getFacadeAccessor() {

        return 'WPPConnectTeam\Wppconnect\Wppconnect';

    }

}
