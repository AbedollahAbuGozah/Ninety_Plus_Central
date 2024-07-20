<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Create the trigger for INSERT
        DB::unprepared('
            CREATE TRIGGER check_student_role_before_insert
            BEFORE INSERT ON course_students
            FOR EACH ROW
            BEGIN
                IF NOT EXISTS (
                    SELECT 1
                    FROM role_user
                    JOIN roles ON role_user.role_id = roles.id
                    WHERE role_user.user_id = NEW.student_id
                    AND roles.name = "student"
                ) THEN
                    SIGNAL SQLSTATE "45000"
                    SET MESSAGE_TEXT = "Student role constraint failed.";
                END IF;
            END
        ');

        // Create the trigger for UPDATE
        DB::unprepared('
            CREATE TRIGGER check_student_role_before_update
            BEFORE UPDATE ON course_students
            FOR EACH ROW
            BEGIN
                IF NOT EXISTS (
                    SELECT 1
                    FROM role_user
                    JOIN roles ON role_user.role_id = roles.id
                    WHERE role_user.user_id = NEW.student_id
                    AND roles.name = "student"
                ) THEN
                    SIGNAL SQLSTATE "45000"
                    SET MESSAGE_TEXT = "Student role constraint failed.";
                END IF;
            END
        ');
    }

    public function down()
    {
        // Drop the triggers
        DB::unprepared('DROP TRIGGER IF EXISTS check_student_role_before_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS check_student_role_before_update');
    }
};
