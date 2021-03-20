<?php

namespace App\Http\Controllers;

use App\Events\ChatEvent;
use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('chat');
  }

  public function fetchAllMessages()
  {
    return Chat::with('user')->get();
  }

  public function sendMessage(Request $request)
  {
    $chat = auth()->user()->messages()->create([
      'message' => $request->message
    ]);

    broadcast(new ChatEvent($chat->load('user')))->toOthers();

    return ['status' => 'success'];
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
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Chat  $chat
   * @return \Illuminate\Http\Response
   */
  public function show(Chat $chat)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Chat  $chat
   * @return \Illuminate\Http\Response
   */
  public function edit(Chat $chat)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Chat  $chat
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Chat $chat)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Chat  $chat
   * @return \Illuminate\Http\Response
   */
  public function destroy(Chat $chat)
  {
    //
  }
}
