<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // e.g. "US Dollar"
            $table->string('code', 10)->unique(); // e.g. "USD"
            $table->string('symbol', 10);     // e.g. "$"
            $table->decimal('exchange_rate', 15, 6); // rate relative to your base currency
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
 
        // Seed default currency (your base — change to match your app's base)
        DB::table('currencies')->insert([
            ['name' => 'Jordanian Dinar', 'code' => 'JOD', 'symbol' => 'JD',  'exchange_rate' => 1.000000, 'is_default' => true,  'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'US Dollar',       'code' => 'USD', 'symbol' => '$',   'exchange_rate' => 1.410000, 'is_default' => false, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Euro',            'code' => 'EUR', 'symbol' => '€',   'exchange_rate' => 1.300000, 'is_default' => false, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Saudi Riyal',     'code' => 'SAR', 'symbol' => '﷼',   'exchange_rate' => 5.290000, 'is_default' => false, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'UAE Dirham',      'code' => 'AED', 'symbol' => 'د.إ', 'exchange_rate' => 5.180000, 'is_default' => false, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
};
