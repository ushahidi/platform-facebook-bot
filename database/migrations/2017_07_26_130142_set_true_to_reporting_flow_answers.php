<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetTrueToReportingFlowAnswers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Setting value true to all answers that are connected to the reporting-flow
        DB::table('answers')
            ->where('command', 'send')
            ->orWhere('command', 'add image')
            ->orWhere('command', 'location received')
            ->orWhere('command', 'image location')
            ->orWhere('command', 'image received')
            ->orWhere('command', 'add location')
            ->orWhere('command', 'submit')
            ->orWhere('command', 'send report')
            ->orWhere('command', 'continue')
            ->update(['reporting_flow'=> true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
     DB::table('answers')
            ->where('command', 'send')
            ->orWhere('command', 'add image')
            ->orWhere('command', 'location received')
            ->orWhere('command', 'image location')
            ->orWhere('command', 'image received')
            ->orWhere('command', 'add location')
            ->orWhere('command', 'submit')
            ->orWhere('command', 'send report')
            ->orWhere('command', 'continue')
            ->update(['reporting_flow'=> false]);
    }
}
