<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePdfDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pdf_docs', function (Blueprint $table) {
            //
            $table->string('description')->after('filename');
            $table->string('hash')->after('description');
            $table->integer('size')->after('hash');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pdf_docs', function (Blueprint $table) {
            //
            $table->dropColumn(['description','hash','size']);
        });
    }
}
