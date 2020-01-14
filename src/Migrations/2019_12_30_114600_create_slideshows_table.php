<?php

use Illuminate\Support\Facades\Schema;
use Core\Foundation\Database\OpxBlueprint;
use Core\Foundation\Database\OpxMigration;

class CreateSlideshowsTable extends OpxMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $this->schema->create('slideshows', static function (OpxBlueprint $table) {

            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('alias')->nullable();
            $table->string('class')->nullable();
            $table->integer('image_size')->nullable()->default(600);
            $table->integer('image_quality')->nullable()->default(65);

            $table->data();

            $table->template();
            $table->template('child_template');
            $table->layout();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('slideshows');
    }
}
