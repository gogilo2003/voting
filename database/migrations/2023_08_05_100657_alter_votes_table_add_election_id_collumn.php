<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            //
            $table->foreignId('election_id')
                ->nullable()
                ->after('user_id')
                ->comment('foreign key referencing elections table');
            $table->foreign('election_id')->references('id')->on('elections');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropForeign(['election_id']);
            $table->dropColumn('election_id');
        });
    }
};
