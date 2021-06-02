<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
   // (P/L17)で編集
        protected $guarded = array('id');
        
        public static $rules = array (
            'news_id' => 'required',
            'edited_at' => 'required',
         );
}