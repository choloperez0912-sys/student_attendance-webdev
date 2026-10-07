<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teacher login</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">

<main class="mx-auto flex min-h-screen w-full max-w-sm flex-col justify-center gap-5 px-4 py-10">

    <header class="text-center">
        <h1 class="text-2xl font-semibold">Teacher login</h1>
    </header>

    <form method="POST" action="<?php echo e(route('teacher.login')); ?>" novalidate
          class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <?php echo csrf_field(); ?>

        <?php $__errorArgs = ['login'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700" role="alert">
                <?php echo e($message); ?>

            </div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        <?php if(session('status')): ?>
            <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-700" role="status">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

        <div>
            <label for="email" class="block text-sm font-medium">Email</label>
            <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus
                   class="mt-1 block w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-2
                          <?php echo e($errors->has('email') ? 'border-red-500 focus:ring-red-500' : 'border-slate-300 focus:ring-blue-600'); ?>">
            <p class="mt-1 min-h-5 text-sm text-red-600"><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <?php echo e($message); ?> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></p>
        </div>

        <div class="mt-2">
            <label for="password" class="block text-sm font-medium">Password</label>
            <input type="password" id="password" name="password" required
                   class="mt-1 block w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-2
                          <?php echo e($errors->has('password') ? 'border-red-500 focus:ring-red-500' : 'border-slate-300 focus:ring-blue-600'); ?>">
            <p class="mt-1 min-h-5 text-sm text-red-600"><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <?php echo e($message); ?> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></p>
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

    <a href="<?php echo e(route('attendance.index')); ?>" class="text-center text-sm text-slate-600 hover:underline">
        Back to attendance
    </a>

</main>

</body>
</html><?php /**PATH D:\Lar5avel\attendance-tracker\resources\views/teacher/login.blade.php ENDPATH**/ ?>