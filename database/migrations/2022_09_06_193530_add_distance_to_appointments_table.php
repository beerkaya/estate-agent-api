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
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('distance')->nullable();
            $table->string('time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('appointments', 'distance')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->dropColumn('distance');
            });
        }

        if (Schema::hasColumn('appointments', 'time')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->dropColumn('time');
            });
        }
    }
};
