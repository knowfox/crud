<?php

namespace Knowfox\Crud\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['name', 'value', 'field'];
    protected $casts = ['readonly' => 'boolean'];

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
                return join(', ', json_decode($this->value));
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
