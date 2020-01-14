<?php

use Illuminate\Support\Facades\Schema;
use Core\Foundation\Database\OpxBlueprint;
use Core\Foundation\Database\OpxMigration;

class CreateSlidesTable extends OpxMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $this->schema->create('slides', static function (OpxBlueprint $table) {

            $table->increments('id');
            $table->parentId('slideshow_id');
            $table->order();

            $table->string('title')->nullable();
            $table->image();
            $table->content();
            $table->string('link')->nullable();
            $table->string('link_caption')->nullable();

            $table->boolean('new_window')->nullable()->default(false);

            $table->boolean('invert_color')->nullable()->default(false);
            $table->boolean('invert_orientation')->nullable()->default(false);

            $table->publication();

            $table->data();

            $table->template();

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
        Schema::drop('slides');
    }
}
