<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id(); // id: int, primary key, auto increment
                $table->string('name')->unique(); // name: varchar(255), unique, not null
                $table->text('description')->nullable(); // description: text, nullable
                $table->string('image_path')->nullable(); // image: varchar(255), nullable
                $table->timestamps(); // created_at & updated_at
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
