<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;

class ArticleController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $articles = Article::orderBy('name', 'desc')->get();
    return view('articles.index')->with('articles', $articles);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $article = new Article();
    //dd($article);
    return view('articles.create')->with(compact('article'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\StoreArticleRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreArticleRequest $request)
  {
    $article = new Article;
    $article->name = $request->input('name');
    $article->data = json_encode($request->input('data')) ?? '[]';
    //dd($request,$article);
    $article->save();
    return redirect(route('articles.show'))->with('success', 'Article Created');
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Article  $article
   * @return \Illuminate\Http\Response
   */
  public function show(Article $article)
  {
    $article->data = json_decode($article->data);
    return view('articles.show')->with(compact('article'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Article  $article
   * @return \Illuminate\Http\Response
   */
  public function edit(Article $article)
  {
    $article->data = json_decode($article->data);
    //dd($article->data);
    return view('articles.edit')->with(compact('article'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\UpdateArticleRequest  $request
   * @param  \App\Models\Article  $article
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateArticleRequest $request, Article $article)
  {
    $data = $request->input('data');
    $last = $data[array_key_last($data)];
    //dd($last);
    if (!$last['link'] && !$last['price']) array_pop($data);
    $article->data = json_encode($data) ?? '[]';
    //dd($article,$request);
    $article->save();
    return redirect(route('articles.show'))->with('success', 'Article Updated');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Article  $article
   * @return \Illuminate\Http\Response
   */
  public function destroy(Article $article)
  {
    $article->delete();
    return redirect(route('articles.index'))->with('success', 'Article removed');
  }
}
