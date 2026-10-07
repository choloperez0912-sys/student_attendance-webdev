<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Attendance</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">

<main class="mx-auto flex min-h-screen w-full max-w-md flex-col justify-center gap-5 px-4 py-10">

    {{-- ========== HEADER ========== --}}
    <header class="text-center">
        <h1 class="text-2xl font-semibold">Student attendance</h1>
        <p id="clock" class="mt-1 text-sm text-slate-500">&nbsp;</p>
    </header>

    {{-- ========== RESULT (visible for 5 seconds after a successful check-in) ========== --}}
    @if (session('student'))
        @php $s = session('student'); @endphp

        <section id="result" class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm" role="status">
            <div class="flex items-center gap-4 p-5">
                @if ($s['photo_url'])
                    <img src="{{ $s['photo_url'] }}" alt="Photo of {{ $s['name'] }}"
                         class="h-28 w-28 shrink-0 rounded-md object-cover">
                @else
                    <div class="flex h-28 w-28 shrink-0 items-center justify-center rounded-md bg-slate-200 text-3xl font-semibold text-slate-500">
                        {{ mb_strtoupper(mb_substr($s['name'], 0, 1)) }}
                    </div>
                @endif

                <div class="min-w-0">
                    <p class="truncate text-lg font-semibold">{{ $s['name'] }}</p>
                    <p class="text-sm text-slate-600">{{ $s['student_number'] }}</p>
                    <p class="mt-2 text-sm text-slate-600">
                        Time in: {{ \Carbon\Carbon::parse($s['time_in'])->format('h:i A') }}
                    </p>
                    <p class="mt-1 text-sm font-medium {{ $s['already'] ? 'text-amber-700' : 'text-green-700' }}">
                        {{ $s['already'] ? 'Already checked in today' : 'Attendance recorded' }}
                    </p>
                </div>
            </div>

            {{-- Countdown bar: shrinks over 5 seconds --}}
            <div class="h-1 bg-slate-100">
                <div id="bar" class="h-full w-full bg-blue-700 transition-[width] duration-[5000ms] ease-linear"></div>
            </div>
        </section>
    @endif

    {{-- ========== FORM ========== --}}
    <form id="attendance-form" method="POST" action="{{ route('attendance.store') }}"
          class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm" novalidate>
        @csrf

        {{-- Wrong number/name combination, or too many attempts --}}
        @error('login')
            <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700" role="alert">
                {{ $message }}
            </div>
        @enderror

        <div>
            <label for="student_number" class="block text-sm font-medium">Student number</label>
            <input type="text" id="student_number" name="student_number"
                   value="{{ old('student_number') }}"
                   maxlength="20" required autofocus autocomplete="off"
                   placeholder="2024-0001"
                   aria-invalid="{{ $errors->has('student_number') ? 'true' : 'false' }}"
                   class="mt-1 block w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-2
                          {{ $errors->has('student_number') ? 'border-red-500 focus:ring-red-500' : 'border-slate-300 focus:ring-blue-600' }}">
            <p id="student_number_error" class="mt-1 min-h-5 text-sm text-red-600">
                @error('student_number') {{ $message }} @enderror
            </p>
        </div>

        <div class="mt-2">
            <label for="name" class="block text-sm font-medium">Full name</label>
            <input type="text" id="name" name="name"
                   value="{{ old('name') }}"
                   maxlength="100" required autocomplete="off"
                   placeholder="Juan Dela Cruz"
                   aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                   class="mt-1 block w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-2
                          {{ $errors->has('name') ? 'border-red-500 focus:ring-red-500' : 'border-slate-300 focus:ring-blue-600' }}">
            <p id="name_error" class="mt-1 min-h-5 text-sm text-red-600">
                @error('name') {{ $message }} @enderror
            </p>
        </div>

        <button type="submit" id="submit-btn"
                class="mt-3 w-full rounded-md bg-blue-700 px-4 py-2.5 font-medium text-white hover:bg-blue-800
                       focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2
                       disabled:cursor-not-allowed disabled:opacity-60">
            Check in
        </button>
    </form>
        {{-- ========== TEACHER LOGIN BUTTON ========== --}}
    <a href="{{ route('login') }}"
       class="block rounded-md border border-slate-300 bg-white px-4 py-2.5 text-center text-sm font-medium text-slate-700
              hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
        Teacher login
    </a>

</main>

<script>
    const form    = document.getElementById('attendance-form');
    const number  = document.getElementById('student_number');
    const nameIn  = document.getElementById('name');
    const btn     = document.getElementById('submit-btn');

    // ---------- Live clock ----------
    const clock = document.getElementById('clock');
    function tick() {
        clock.textContent = new Date().toLocaleString('en-PH', {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
            hour: 'numeric', minute: '2-digit', second: '2-digit'
        });
    }
    tick();
    setInterval(tick, 1000);

    // ---------- Client-side validation (mirrors the server rules) ----------
    const rules = {
        student_number: {
            el: number,
            check(v) {
                if (!v) return 'Enter your student number.';
                if (v.length > 20) return 'Student number is too long (max 20 characters).';
                if (!/^[A-Za-z0-9\-]+$/.test(v)) return 'Student number may only contain letters, numbers and dashes.';
                return '';
            }
        },
        name: {
            el: nameIn,
            check(v) {
                if (!v) return 'Enter your full name.';
                if (v.length < 2) return 'Name is too short.';
                if (v.length > 100) return 'Name is too long (max 100 characters).';
                if (!/^[\p{L}\s.'\-]+$/u.test(v)) return 'Name may only contain letters, spaces, periods, apostrophes and hyphens.';
                return '';
            }
        }
    };

    function showError(field, message) {
        const el = rules[field].el;
        document.getElementById(field + '_error').textContent = message;
        el.setAttribute('aria-invalid', message ? 'true' : 'false');
        el.classList.toggle('border-red-500', !!message);
        el.classList.toggle('focus:ring-red-500', !!message);
        el.classList.toggle('border-slate-300', !message);
        el.classList.toggle('focus:ring-blue-600', !message);
    }

    function validateField(field) {
        const msg = rules[field].check(rules[field].el.value.trim());
        showError(field, msg);
        return msg === '';
    }

    // Validate when leaving a field, and clear the error while typing
    Object.keys(rules).forEach(field => {
        rules[field].el.addEventListener('blur', () => validateField(field));
        rules[field].el.addEventListener('input', () => {
            if (rules[field].el.getAttribute('aria-invalid') === 'true') validateField(field);
        });
    });

    form.addEventListener('submit', function (e) {
        const results = Object.keys(rules).map(validateField);
        if (results.includes(false)) {
            e.preventDefault();
            const firstBad = Object.keys(rules).find(f => rules[f].el.getAttribute('aria-invalid') === 'true');
            rules[firstBad].el.focus();
            return;
        }
        btn.disabled = true;           // prevent double submit
        btn.textContent = 'Checking...';
    });

    // Re-enable the button if the user comes back with the browser's back button
    window.addEventListener('pageshow', () => {
        btn.disabled = false;
        btn.textContent = 'Check in';
    });

    // ---------- Result card: hide after 5 seconds ----------
    const result = document.getElementById('result');
    if (result) {
        const bar = document.getElementById('bar');
        // two frames so the browser registers the starting width before animating
        requestAnimationFrame(() => requestAnimationFrame(() => { bar.style.width = '0%'; }));

        setTimeout(() => {
            result.remove();
            form.reset();
            number.focus();
        }, 5000);
    }
</script>

</body>
</html>