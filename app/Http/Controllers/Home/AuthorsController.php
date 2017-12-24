<?php

namespace App\Http\Controllers\Home;
use App\Posts;
use App\Http\Controllers\Controller;
use \Symfony\Component\HttpFoundation\Request;
class AuthorsController extends HomeController
{


    /**
     * 获取作者与相关文章信息
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $aid        = $request->aid;
        $authorList = Posts::getAuthorList($aid, 6);
        return view('Themes/'.$this->theme.'Home/authors_detail', ['authorList' => $authorList]);
    }
    
}
