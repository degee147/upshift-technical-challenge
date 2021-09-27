<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class CreateGigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gigs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies');
            $table->string('name');
            $table->text('description');
            $table->dateTime('timestamp_start');
            $table->dateTime('timestamp_end');
            $table->integer('number_of_positions');
            $table->double('pay_per_hour', 8, 2);
            $table->boolean('posted')->default(0);
            $table->enum('status', ['Not started', 'Started', 'Finished']);
            $table->timestamps();
        });

        // Because Laravel doesn't support full text search migration
        DB::statement('ALTER TABLE `posts` ADD FULLTEXT INDEX post_title_index (title)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gigs');
    }
}
