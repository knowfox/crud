<?php

use Knowfox\Crud\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!method_exists(Setting::class, 'create')) {
            return;
        }

        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('value');
            $table->string('field');
            $table->boolean('readonly')->default(0);
            $table->timestamps();
        });

        Setting::create([
            'name' => 'setting_types',
            'value' => '["simple","table"]',
            'field' => 'table',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
