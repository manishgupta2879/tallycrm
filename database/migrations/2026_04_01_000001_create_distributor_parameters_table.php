<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('distributor_parameters', function (Blueprint $table) {
            $table->id();
            $table->string('tallyserialno', 100)->index();
            $table->string('principalid');
            $table->string('distributorid');
            $table->string('distname');
            // Static parameter columns
            $table->string('p1', 100)->nullable();
            $table->string('p2', 100)->nullable();
            $table->string('p3', 100)->nullable();
            $table->string('p4', 100)->nullable();
            $table->string('p5', 100)->nullable();
            $table->string('p6', 100)->nullable();
            $table->string('p7', 100)->nullable();
            $table->string('p8', 100)->nullable();
            $table->string('p9', 100)->nullable();
            $table->string('p10', 100)->nullable();
            $table->timestamps();
            $table->unique([
                DB::raw('tallyserialno(50)'),
                DB::raw('principalid(50)'),
                DB::raw('distributorid(50)'),
                DB::raw('distname(50)'),
                DB::raw('p1(50)'),
                DB::raw('p2(50)'),
                DB::raw('p3(50)'),
                DB::raw('p4(50)'),
                DB::raw('p5(50)'),
                DB::raw('p6(50)'),
                DB::raw('p7(50)'),
                DB::raw('p8(50)'),
                DB::raw('p9(50)'),
                DB::raw('p10(50)')
            ], 'unique_param');
        });
    }

    public function down()
    {
        Schema::dropIfExists('distributor_parameters');
    }
};
