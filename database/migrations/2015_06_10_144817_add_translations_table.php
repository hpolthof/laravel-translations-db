<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTranslationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('translations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('locale');
			$table->string('group');
			$table->string('name');
			$table->text('value')->nullable();
			$table->timestamp('viewed_at')->nullable();
			$table->timestamps();

			$table->index(['locale', 'group']);
			$table->unique(['locale', 'group', 'name']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('translations');
	}

}
