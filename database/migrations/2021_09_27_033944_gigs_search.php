<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class GigsSearch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `gigs` ADD FULLTEXT INDEX name_index (name)');
        DB::statement('ALTER TABLE `gigs` ADD FULLTEXT INDEX description_index (description)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gigs', function ($table) {
            $table->dropIndex('name_index');
            $table->dropIndex('description_index');
        });
    }
}
