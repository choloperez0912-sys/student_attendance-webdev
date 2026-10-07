<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Students</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">

<div class="mx-auto w-full max-w-4xl px-4 py-8">

    {{-- ========== HEADER ========== --}}
    <header class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold">Students</h1>
            <p class="text-sm text-slate-500">Logged in as {{ auth()->user()->name }}</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('attendance.index') }}" class="text-sm text-slate-600 hover:underline">Attendance page</a>

            <form method="POST" action="{{ route('teacher.logout') }}">
                @csrf
                <button type="submit"
                        class="rounded-md border border-slate-300 bg-white px-3 py-1.5 text-sm hover:bg-slate-50">
                    Log out
                </button>
            </form>
        </div>
    </header>

    {{-- ========== STATUS MESSAGE ========== --}}
    @if (session('status'))
        <div class="mb-5 rounded-md border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-700" role="status">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid gap-6 md:grid-cols-[20rem_1fr]">

        {{-- ========== REGISTER A STUDENT ========== --}}
        <section class="self-start rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="mb-3 text-lg font-semibold">Register a student</h2>

            <form method="POST" action="{{ route('teacher.students.store') }}" enctype="multipart/form-data" novalidate>
                @csrf

                <div>
                    <label for="student_number" class="block text-sm font-medium">Student number</label>
                    <input type="text" id="student_number" name="student_number" value="{{ old('student_number') }}"
                           maxlength="20" required autocomplete="off" placeholder="2024-0003"
                           class="mt-1 block w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-2
                                  {{ $errors->has('student_number') ? 'border-red-500 focus:ring-red-500' : 'border-slate-300 focus:ring-blue-600' }}">
                    <p class="mt-1 min-h-5 text-sm text-red-600">@error('student_number') {{ $message }} @enderror</p>
                </div>

                <div class="mt-2">
                    <label for="name" class="block text-sm font-medium">Full name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           maxlength="100" required autocomplete="off" placeholder="Juan Dela Cruz"
                           class="mt-1 block w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-2
                                  {{ $errors->has('name') ? 'border-red-500 focus:ring-red-500' : 'border-slate-300 focus:ring-blue-600' }}">
                    <p class="mt-1 min-h-5 text-sm text-red-600">@error('name') {{ $message }} @enderror</p>
                </div>

                <div class="mt-2">
                    <label for="photo" class="block text-sm font-medium">Photo (JPG or PNG, up to 2 MB)</label>
                    <input type="file" id="photo" name="photo" accept="image/jpeg,image/png"
                           class="mt-1 block w-full text-sm file:mr-3 file:rounded-md file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm hover:file:bg-slate-200">
                    <p class="mt-1 min-h-5 text-sm text-red-600">@error('photo') {{ $message }} @enderror</p>
                    <img id="preview" alt="" class="mt-1 hidden h-28 w-28 rounded-md object-cover">
                </div>

                <button type="submit"
                        class="mt-3 w-full rounded-md bg-blue-700 px-4 py-2.5 font-medium text-white hover:bg-blue-800
                               focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                    Register student
                </button>
            </form>
        </section>

        {{-- ========== STUDENT LIST ========== --}}
        <section class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-3">
                <h2 class="text-lg font-semibold">Registered students ({{ $students->count() }})</h2>
            </div>

            @if ($students->isEmpty())
                <p class="px-5 py-8 text-center text-sm text-slate-500">No students yet. Register the first one.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="px-5 py-2 font-medium">Photo</th>
                                <th class="px-3 py-2 font-medium">Student number</th>
                                <th class="px-3 py-2 font-medium">Name</th>
                                <th class="px-3 py-2 font-medium">Today</th>
                                <th class="px-5 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($students as $student)
                                <tr>
                                    <td class="px-5 py-2">
                                        @if ($student->photo)
                                            <img src="{{ asset('storage/' . $student->photo) }}" alt=""
                                                 class="h-10 w-10 rounded object-cover">
                                        @else
                                            <div class="flex h-10 w-10 items-center justify-center rounded bg-slate-200 text-slate-500">
                                                {{ mb_strtoupper(mb_substr($student->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2">{{ $student->student_number }}</td>
                                    <td class="px-3 py-2">{{ $student->name }}</td>
                                    <td class="px-3 py-2">
                                        @if ($student->present_today)
                                            <span class="text-green-700">Present</span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-2 text-right">
                                        <form method="POST" action="{{ route('teacher.students.destroy', $student) }}"
                                              onsubmit="return confirm('Remove this student and all their attendance records?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:underline">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

    </div>
</div>

<script>
    // Show a preview of the chosen photo
    const photo   = document.getElementById('photo');
    const preview = document.getElementById('preview');

    photo.addEventListener('change', () => {
        const file = photo.files[0];
        if (!file) { preview.classList.add('hidden'); return; }
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
    });
</script>

</body>
</html>