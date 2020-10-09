<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use App\Http\Resources\Article as ArticleResource;

class ArticleController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $articles = Article::paginate(15);
	  return ArticleResource::collection($articles);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $article = $request->isMethod('put') ? Article::findOrFail($request->article_id) : new Article;

    $article->id = $request->input('article_id');
    $article->title = $request->input('title');
    $article->body = $request->input('body');

    if($article->save()) {
      return new ArticleResource($article);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Article  $article
   * @return \Illuminate\Http\Response
   */
  public function show(Article $article)
  {
    return new ArticleResource($article);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Article  $article
   * @return \Illuminate\Http\Response
   */
  public function edit(Article $article)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Article  $article
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Article $article)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Article  $article
   * @return \Illuminate\Http\Response
   */
  public function destroy(Article $article)
  {
    if($article->delete()) {
      return new ArticleResource($article);
    }
  }
}
