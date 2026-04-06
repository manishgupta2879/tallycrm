<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('distributors', function (Blueprint $table) {
            $table->string('userid',50)->nullable()->after('tally_serial');
            $table->string('authcode',50)->nullable()->after('userid');
            $table->string('authcode2',50)->nullable()->after('authcode');
        });
    }

    public function down()
    {
        Schema::table('distributors', function (Blueprint $table) {
            $table->dropColumn(['userid', 'authcode', 'authcode2']);
        });
    }
};
