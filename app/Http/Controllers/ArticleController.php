<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;

class ArticleController extends Controller
{
    public function index()
    {
        return View::make('form');
    }

    public function show($articleId)
    {
        return 'Article id is'. $articleId;
    }
}
