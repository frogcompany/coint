<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>COINT</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->

    <link href="/css/style.css" rel="stylesheet" />
    </head>
    <body class="antialiased">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
            @if (Route::has('login'))
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

<div>
    <a href="/login/google">Google</a>
</div>

<div class="Form">
  <div class="Form-Item">
    <p class="Form-Item-Label">
      <span class="Form-Item-Label-Required">必須</span>会社名
    </p>
    <input type="text" class="Form-Item-Input" placeholder="例）株式会社令和">
  </div>
  <div class="Form-Item">
    <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>氏名</p>
    <input type="text" class="Form-Item-Input" placeholder="例）山田太郎">
  </div>
  <div class="Form-Item">
    <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>電話番号</p>
    <input type="text" class="Form-Item-Input" placeholder="例）000-0000-0000">
  </div>
  <div class="Form-Item">
    <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>メールアドレス</p>
    <input type="email" class="Form-Item-Input" placeholder="例）example@gmail.com">
  </div>
  <div class="Form-Item">
    <p class="Form-Item-Label isMsg"><span class="Form-Item-Label-Required">必須</span>お問い合わせ内容</p>
    <textarea class="Form-Item-Textarea"></textarea>
  </div>
  <input type="submit" class="Form-Btn" value="送信する">
</div>






    </body>
</html>
