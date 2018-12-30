<?php

namespace Knowfox\Crud\Models;

interface Setting
{
    public function upgradeSchema();
    public function get($name);
}