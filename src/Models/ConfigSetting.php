<?php

namespace Knowfox\Crud\Models;

class ConfigSetting implements Setting
{
    public function upgradeSchema() {}
    public function get($name) { return config('crud.' . $name); }
}