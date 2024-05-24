<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        DB::statement('
            ALTER TABLE course_students
            ADD CONSTRAINT check_student_role
            CHECK (EXISTS (
                SELECT 1
                FROM role_user
                JOIN roles ON role_user.role_id = roles.id
                WHERE role_user.user_id = course_students.student_id
                  AND roles.name = \'student\'
            ))
        ');
    }

    public function down()
    {
        DB::statement('ALTER TABLE course_students DROP CONSTRAINT check_student_role');
    }
};
