<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Validator;

class NewsController extends Controller
{
	public function index()
	{
		$crit = array('status' => 1);
    	$datanewsfeed = DB::table('newsfeed')->where($crit)->get();

    	$content = array(
    		'datanewsfeed' => $datanewsfeed
    	);
		return view('news/index', $content);
	}
}