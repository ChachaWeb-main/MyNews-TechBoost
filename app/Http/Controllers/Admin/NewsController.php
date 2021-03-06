<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// 以下を追記することでNews Modelが扱えるようになる(P/L 14)
use App\News;

// 以下を追記(P/L17)
use App\History;

use Carbon\Carbon;
// 以下を追記(P/L Heroku)
use Storage;

class NewsController extends Controller
{
    // 以下を追記
    public function add(){
        return view('admin.news.create');
    }
    
    // 以下を追記(P/L 13)
    public function create(Request $request){
        // 以下を追記、Varidationを行う(P/L 14)
        $this->validate($request, News::$rules);
        
        $news = new News;
        $form = $request->all();
        
        // フォームから画像が送信されてきたら、保存して、$news->image_pathに画像のパスを保存する
        if (isset($form['image'])) {
        $path = Storage::disk('s3')->putFile('/',$form['image'],'public'); // P/L Herokuで変更
        $news->image_path = Storage::disk('s3')->url($path); // P/L Herokuで変更
      } else {
          $news->image_path = null;
      }
        
        // フォームから送信されてきた_tokenを削除する
        unset($form['_token']);
        // フォームから送信されてきたimageを削除する
        unset($form['image']);
        
        // データベースに保存する
        $news->fill($form);
        $news->save();
        
        // admin/news/createにリダイレクトする
        return redirect('admin/news/create');
    }
    
    // 以下を追記(P/L15)
    public function index(Request $request) {
        $cond_title = $request -> cond_title;
        if ($cond_title != '') {
            // 検索されたら検索結果を取得する
            $posts = News::where('title', $cond_title) -> get();
        } else {
            // それ以外はすべてのニュースを取得する
            $posts = News::all();
        }
        return view('admin.news.index', ['posts' => $posts, 'cond_title' => $cond_title]);
    }
    
    // 以下を追記(P/L16)
    public function edit(Request $request)
  {
      // News Modelからデータを取得する
      $news = News::find($request -> id);
      if (empty($news)) {
        abort(404);    
      }
      return view('admin.news.edit', ['news_form' => $news]);
  }

  public function update(Request $request)
  {
      // Validationをかける
      $this -> validate($request, News::$rules);
      // News Modelからデータを取得する
      $news = News::find($request -> id);
      // 送信されてきたフォームデータを格納する
      $news_form = $request -> all();
// ここから   
      if ($request->remove == 'true') {
          $news_form['image_path'] = null;
      } elseif ($request->file('image')) {
        $path = Storage::disk('s3')->putFile('/',$news_form['image'],'public'); // P/L Herokuで変更
        $news->image_path = Storage::disk('s3')->url($path); // P/L Herokuで変更
      } else {
          $news_form['image_path'] = $news->image_path;
      }

      unset($news_form['image']);
      unset($news_form['remove']);
// ここまでがないと画像変更時にエラーになってしまう！
      unset($news_form['_token']);
      // 該当するデータを上書きして保存する
      $news -> fill($news_form) -> save();
      
      // 以下を追記(P/L17)
      $history = new History;
      $history -> news_id = $news -> id;
      $history -> edited_at = Carbon::now();
      $history -> save();
      
      return redirect('admin/news');
  }
  
  public function delete(Request $request) {
    // 該当するNews Modelを取得する
    $news = News::find($request -> id);
    // 削除する
    $news -> delete();
    return redirect('admin/news');
  }
  
}
