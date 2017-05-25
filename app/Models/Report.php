<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class Report extends Model
    {
        protected $table = 'reports';
        public $timestamps = false;
        
        protected $fillable = [
        'user_id',
        'attributes',
        'replies',
        'last_question'
        ];
    }