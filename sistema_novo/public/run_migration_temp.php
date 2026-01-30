<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

// Bootstrap Laravel Kernel to load Facades
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Running Migration manually...\n";

if (!Schema::hasColumn('editais', 'cidade_prova')) {
    Schema::table('editais', function (Blueprint $table) {
        $table->string('cidade_prova')->nullable()->after('texto_extraido');
        $table->string('instituicao_banca')->nullable()->after('cidade_prova');
        $table->string('ano_prova', 4)->nullable()->after('instituicao_banca');
    });
    echo "Columns added successfully!\n";
} else {
    echo "Columns already exist.\n";
}
