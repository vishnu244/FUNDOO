<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\notes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


class cacheController extends Controller
{
    public function getUser()
    {
        $user = Cache::remember('users',10, function(){
            return User::all();
        });
        return $user;
    }

    public function getNotes()
    {
        $notes = Cache::remember('notes',10, function(){
            return Notes::all();
        });
        return $notes;
    }

    public function getLable()
    {
        $lable = Cache::remember('lable',10, function(){
            return Notes::all();
        });
        return $lable;
    }

    public function getJoinNotesLable()
    {
        $lable = Cache::remember('lable',10, function()
        {
            return DB::table('notes')->join('lables', 'notes.id','=','lables.lable_id')->select('notes.*','lables.lable')->get();
        });
        return $lable;
    }
}
