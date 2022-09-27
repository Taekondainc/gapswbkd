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
        Schema::create('webpage', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->string('linkname');
            $table->string('linkaddress');
            $table->string('image');
            $table->string('sprecialdata');
            $table->string('pagename');
            $table->string('grid');
            $table->string('ip'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
