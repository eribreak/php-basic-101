<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

class MailsController extends Controller
{
    public function sendWelcomeMail(Todo $todo)
    {
        // Eager load the category relationship
        $todo->load('category');

        Mail::to('ngokcuaank@gmail.com')->send(new WelcomeMail($todo));

        return redirect()->route('todos.index')->with('success', 'Gửi email thành công: ' . $todo->title);
    }
}
