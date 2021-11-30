<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DevDojo\Chatter\Models\Models;
use Modules\Companies\Models\Company;
use Modules\Events\Models\Event;
use Modules\News\Models\News;
use Modules\Publications\Models\Publication;
use Modules\Users\Models\User;
use Modules\Languages\Models\Translation;
use Modules\Tenders\Models\Tender;
use Modules\Projects\Models\Project;
use Modules\Courses\Models\Course;


class HomeController extends BaseController
{


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $latest_news = News::orderBy('created_at', 'desc')->limit(2)->get();
        return view('home', compact('latest_news'));
    }
}
