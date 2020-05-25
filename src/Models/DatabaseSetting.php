<?php

namespace Knowfox\Crud\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class DatabaseSetting extends Model implements Setting
{
    protected $table = 'settings';
    protected $fillable = ['name', 'value', 'field'];
    protected $casts = ['readonly' => 'boolean'];

    public function get($name) { return config('crud.' . $name); }

    public function upgradeSchema()
    {
        $app_version = config('app.version', '0.1');
        $schema_version = self::where('name', 'version')->pluck('value')->first();

        if (!$schema_version || version_compare($app_version, $schema_version) > 0) {
            Artisan::call('migrate', ['--force' => true]);
            self::updateOrCreate([
                'name' => 'version'
            ], [
                'value' => $app_version,
                'field' => 'simple',
            ]);
        }
    }

    public function getDecodedValueAttribute($value)
    {
        switch ($this->field) {
            case 'table':
                return json_decode($this->value);
            default:
                return $this->value;
        }
    }

    public function getPrettyValueAttribute($value)
    {
        switch ($this->field) {
            case 'table':
                $value = json_decode($this->value);
                return is_array($value) ? join(', ', $value) : null;
            default:
                return $this->value;
        }
    }

    public function getTranslatedNameAttribute($value)
    {
        return __('settings.' . $this->name);
    }

    public function getTranslatedFieldAttribute($value)
    {
        return __('settings.' . $this->field);
    }
}
