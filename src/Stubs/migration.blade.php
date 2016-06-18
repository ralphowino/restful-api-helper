//This is the migration for the {{ ucwords($modelName) }} Model

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create{{ ucwords(str_plural($modelName)) }}Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('{{ strtolower(str_plural($modelName)) }}', function (Blueprint $table) {
            $table->increments('id');
        @if($archive)

            $table->softDeletes();
        @endif

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
        Schema::drop('{{ strtolower(str_plural($modelName)) }}');
    }
}
