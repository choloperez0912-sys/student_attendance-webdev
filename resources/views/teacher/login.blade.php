<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teacher login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">

<main class="mx-auto flex min-h-screen w-full max-w-sm flex-col justify-center gap-5 px-4 py-10">

    <header class="text-center">
        <h1 class="text-2xl font-semibold">Teacher login</h1>
    </header>

    <form method="POST" action="{{ route('teacher.login') }}" novalidate
          class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        @csrf

        @error('login')
            <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700" role="alert">
                {{ $message }}
            </div>
        @enderror

        @if (session('status'))
            <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-700" role="status">
                {{ session('status') }}
            </div>
        @endif

        <div>
            <label for="email" class="block text-sm font-medium">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                   class="mt-1 block w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-2
                          {{ $errors->has('email') ? 'border-red-500 focus:ring-red-500' : 'border-slate-300 focus:ring-blue-600' }}">
            <p class="mt-1 min-h-5 text-sm text-red-600">@error('email') {{ $message }} @enderror</p>
        </div>

        <div class="mt-2">
            <label for="password" class="block text-sm font-medium">Password</label>
            <input type="password" id="password" name="password" required
                   class="mt-1 block w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-2
                          {{ $errors->has('password') ? 'border-red-500 focus:ring-red-500' : 'border-slate-300 focus:ring-blue-600' }}">
            <p class="mt-1 min-h-5 text-sm text-red-600">@error('password') {{ $message }} @enderror</p>
        </div>

        <label class="mt-2 flex items-center gap-2 text-sm">
            <input type="checkbox" name="remember" value="1" class="rounded border-slate-300">
            Keep me logged in
        </label>

        <button type="submit"
                class="mt-4 w-full rounded-md bg-blue-700 px-4 py-2.5 font-medium text-white hover:bg-blue-800
                       focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
            Log in
        </button>
    </form>

    <a href="{{ route('attendance.index') }}" class="text-center text-sm text-slate-600 hover:underline">
        Back to attendance
    </a>

</main>

</body>
</html>