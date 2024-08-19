<?

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaketsTable extends Migration
{
    public function up()
    {
        Schema::create('pakets', function (Blueprint $table) {
            $table->id();
            $table->string('paket1'); // Nama Paket
            $table->integer('duration_hours'); // Durasi dalam jam
            $table->integer('duration_minutes')->default(0); // Durasi dalam menit
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pakets');
    }
}
