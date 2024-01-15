<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */

    // $dontReport: 一個陣列，列出不應該被報告的例外類型。
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */

    // $dontFlash: 一個陣列，列出在驗證例外時永遠不應該被「閃回」（flash）的輸入（input）名稱
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {

        // 註冊一個回調函數，當例外被報告時，此回調函數會被呼叫
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    // 覆寫
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response('授權失敗', 401);
    }
}
