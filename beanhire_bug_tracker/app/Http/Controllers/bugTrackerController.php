<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;

class bugTrackerController extends Controller
{
    /**
     *
     *
     */
    public function addBug()
    {
        $lastBugId = \App\Models\Bug::latest()->first();
        if ($lastBugId == null) {
            $currentBugId = 'Bug-01';
        } else {
            $currentBugId = 'Bug-'.
        }
        dd($lastBugId);
        return view('addBug');
    }
}
