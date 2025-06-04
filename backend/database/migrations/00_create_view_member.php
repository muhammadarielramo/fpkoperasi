<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE VIEW view_member_user AS
            SELECT
                users.email AS email,
                users.username AS username,
                users.name AS name,
                users.phone_number AS phone_number,
                members.nik AS nik,
                members.bod AS bod,
                members.address AS address,
                members.foto_ktp as ktp
            FROM users
            INNER JOIN members on users.id = members.id_user
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS view_member_user");
    }
};
