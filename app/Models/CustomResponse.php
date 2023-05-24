<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomResponse extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'message',
        'data',
    ];

    public function  __construct() {
        $this->SetError();
    }

    public function SetError($message = null) {
        $this->status = 'error';
        $this->message = (is_null($message) || empty(trim($message))) ? 'error' : $message;
        $this->data = null;
    }

    public function SetOk($data = null, $message = null) {
        $this->status = 'ok';
        $this->message = (is_null($message) || empty(trim($message))) ? 'ok' : $message;
        $this->data = $data;
    }

    public function SetNotFound() {
        $this->status = 'not_found';
        $this->message = 'Not Found';
        $this->data = null;
    }
}
