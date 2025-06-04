<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE VIEW view_member_collector AS
            SELECT
                user_collector.name AS collector_name,
                user_member.name AS member_name,
                mk.tgl_penugasan AS tgl_penugasan
            FROM
                member_collector mk
            JOIN collectors ON mk.id_collector = collectors.id
            JOIN users user_collector ON collectors.id_user = user_collector.id
            JOIN members ON mk.id_member = members.id
            JOIN users user_member ON members.id_user = user_member.id;
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
