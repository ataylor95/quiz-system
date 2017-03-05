<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('question_text');
            $table->enum('type', array('multi_choice', 'multi_select', 'number_range', 'boolean', 'text'));
            for ($i=1; $i<11; $i++) {
                //Start from 1 because it will make it more human friendly when adding a question
                $table->string('answer' . $i)->default('');
            }
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
        Schema::dropIfExists('questions');
    }
}
