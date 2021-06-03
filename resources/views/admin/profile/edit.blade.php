@extends('layouts.profile')
@section('title', 'プロフィールの編集')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <h2>Myプロフィール編集</h2>
                <form action="{{ action('Admin\ProfileController@update') }}" method="post" enctype="multipart/form-data">
                    @if (count($errors) > 0)
                        <ul>
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="form-group row">
                        <label class="col-md-2" for="title">氏名（name）</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="title" value="{{ $profile_form->title }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2" for="body">性別（gender）</label>
                        <div class="col-md-10">
                           <div>
                              <input type="radio" id="男性 (Male)" name="gender" value="男性 (Male)" checked>
                              <label for="男性 (Male)">男性 (Male)</label>
                            </div>
                            
                            <div>
                              <input type="radio" id="女性 (Female)" name="gender" value="女性 (Female)">
                              <label for="女性 (Female)">女性 (Female)</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2" for="body">趣味（hobby）</label>
                        <div class="col-md-10">
                            <textarea class="form-control" name="body" rows="2">{{ $profile_form->body }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2" for="body">自己紹介（introduction）</label>
                        <div class="col-md-10">
                            <textarea class="form-control" name="body" rows="15">{{ $profile_form->body }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-10">
                            <input type="hidden" name="id" value="{{ $profile_form->id }}">
                            {{ csrf_field() }}
                            <input type="submit" class="btn btn-primary" value="更新">
                        </div>
                    </div>
                </form>
                <div class="row mt-5">
                    <div class="col-md-4 mx-auto">
                        <h2>編集履歴</h2>
                        <ul class="list-group">
                            @if ($profile_form->profile_histories != NULL)
                                @foreach ($profile_form->histories as $history)
                                    <li class="list-group-item">{{ $history->profile_edited_at }}</li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection