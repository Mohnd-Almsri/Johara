<?php

namespace App\Models\Contact;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{

    protected $table = 'contacts';
    protected $fillable = ['first_name', 'last_name', 'email', 'phone', 'message','is_read'];


}
