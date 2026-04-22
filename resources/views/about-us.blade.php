<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ \App\Support\PageTitle::format(__('About Us')) }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-black text-zinc-100">
    <main class="relative z-10 bg-black py-16 px-6 md:px-10">
        <div class="mx-auto max-w-6xl">
            <div class="mb-10">
                <a
                    href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-[0.25em] text-zinc-400 hover:text-emerald-400 transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    {{ __('Back') }}
                </a>
            </div>

            <div class="text-center mb-14">
                <p class="text-[10px] font-black uppercase tracking-[0.45em] text-emerald-400/85 mb-4">{{ __('About Us') }}</p>
                <h1 class="text-3xl md:text-5xl font-black uppercase italic tracking-tight text-white">
                    {{ __('Meet The Developers') }}
                </h1>
                <p class="mt-5 text-zinc-400 text-sm md:text-base max-w-2xl mx-auto leading-relaxed">
                    {{ __('Project details and team story will be added here.') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <article class="group rounded-3xl border border-zinc-800 bg-zinc-950/80 p-6 md:p-8 transition-all duration-500 hover:border-emerald-500/40 hover:shadow-[0_0_40px_rgba(16,185,129,0.12)]">
                    <div class="relative aspect-square w-full overflow-hidden rounded-2xl border border-zinc-800 bg-zinc-900">
                        <img
                            src="{{ route('landing.dev-image', ['developer' => 'koraz']) }}"
                            alt="Koraz Kamaran"
                            class="h-full w-full object-cover grayscale brightness-50 contrast-125 saturate-0 transition-all duration-700 ease-out"
                            loading="lazy"
                        >
                        <div class="pointer-events-none absolute inset-0 bg-black/35"></div>
                    </div>
                    <div class="mt-6">
                        <div class="flex flex-wrap items-center gap-3">
                            <h2 class="text-xl md:text-2xl font-black uppercase italic tracking-tight text-white">Koraz Kamaran</h2>
                            <a
                                href="https://github.com/KorazKXalkon"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[0.2em] text-zinc-300 transition-colors hover:text-emerald-400"
                                aria-label="Koraz Kamaran GitHub"
                            >
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.009-.866-.014-1.699-2.782.605-3.369-1.343-3.369-1.343-.454-1.157-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.004.071 1.532 1.033 1.532 1.033.892 1.53 2.341 1.088 2.91.832.091-.647.349-1.088.636-1.338-2.221-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.03-2.688-.104-.254-.447-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0 1 12 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.748-1.027 2.748-1.027.546 1.379.203 2.397.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.338 4.695-4.566 4.943.359.31.678.92.678 1.855 0 1.34-.012 2.421-.012 2.75 0 .268.18.58.688.481A10.02 10.02 0 0 0 22 12.017C22 6.484 17.523 2 12 2Z"/>
                                </svg>
                                GitHub
                            </a>
                        </div>
                        <p class="mt-2 text-[10px] font-black uppercase tracking-[0.3em] text-emerald-400/80">{{ __('Developer') }}</p>
                        <p class="mt-4 text-zinc-400 text-sm leading-relaxed">
                            {{ __('I am currently studying Software Engineering at Salahaddin University in Kurdistan, Iraq. Over the course of my studies, I have built a strong foundation in networking, cybersecurity, and frontend development, while also diving deeper into backend development with Laravel and PHP.') }}
                        </p>
                        <ul class="mt-4 space-y-2 text-zinc-400 text-sm leading-relaxed list-disc pl-5">
                            <li>{{ __('Backend Development (Laravel): Environment setup, database configuration, migrations, pivot tables, and Eloquent relationships.') }}</li>
                            <li>{{ __('Troubleshooting & System Maintenance: Methodical problem-solving for environment errors, schema mismatches, and workflow optimization.') }}</li>
                            <li>{{ __('Frontend Best Practices: Accessibility, Blade refactoring, and exploring frameworks like Tailwind, Bootstrap, and Bulma.') }}</li>
                            <li>{{ __('Technical Support & QA: Maintaining structured systems, diagnosing issues, and ensuring reliability.') }}</li>
                            <li>{{ __('Business & Data Analysis: Using tools like Excel for reporting, conditional formatting, and translating business needs into technical solutions.') }}</li>
                        </ul>
                    </div>
                </article>

                <article class="group rounded-3xl border border-zinc-800 bg-zinc-950/80 p-6 md:p-8 transition-all duration-500 hover:border-cyan-500/40 hover:shadow-[0_0_40px_rgba(34,211,238,0.12)]">
                    <div class="relative aspect-square w-full overflow-hidden rounded-2xl border border-zinc-800 bg-zinc-900">
                        <img
                            src="{{ route('landing.dev-image', ['developer' => 'abdullah']) }}"
                            alt="Abdullah Mohammed"
                            class="h-full w-full object-cover grayscale brightness-50 contrast-125 saturate-0 transition-all duration-700 ease-out"
                            loading="lazy"
                        >
                        <div class="pointer-events-none absolute inset-0 bg-black/35"></div>
                    </div>
                    <div class="mt-6">
                        <div class="flex flex-wrap items-center gap-3">
                            <h2 class="text-xl md:text-2xl font-black uppercase italic tracking-tight text-white">Abdullah Mohammed</h2>
                            <a
                                href="https://github.com/Mana007777"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[0.2em] text-zinc-300 transition-colors hover:text-cyan-400"
                                aria-label="Abdullah Mohammed GitHub"
                            >
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.009-.866-.014-1.699-2.782.605-3.369-1.343-3.369-1.343-.454-1.157-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.004.071 1.532 1.033 1.532 1.033.892 1.53 2.341 1.088 2.91.832.091-.647.349-1.088.636-1.338-2.221-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.03-2.688-.104-.254-.447-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0 1 12 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.748-1.027 2.748-1.027.546 1.379.203 2.397.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.338 4.695-4.566 4.943.359.31.678.92.678 1.855 0 1.34-.012 2.421-.012 2.75 0 .268.18.58.688.481A10.02 10.02 0 0 0 22 12.017C22 6.484 17.523 2 12 2Z"/>
                                </svg>
                                GitHub
                            </a>
                        </div>
                        <p class="mt-2 text-[10px] font-black uppercase tracking-[0.3em] text-cyan-400/80">{{ __('Developer') }}</p>
                        <p class="mt-4 text-zinc-400 text-sm leading-relaxed">
                            {{ __('I am a Software Engineering student with skills in full-stack web development, programming, and cybersecurity fundamentals. I work with backend technologies like Laravel and PHP, focusing on building structured applications, managing databases, and handling relationships and migrations.') }}
                        </p>
                        <p class="mt-4 text-zinc-400 text-sm leading-relaxed">
                            {{ __('On the frontend, I use HTML, CSS, JavaScript, and Tailwind CSS to create responsive and user-friendly interfaces with clean design and good structure.') }}
                        </p>
                        <p class="mt-4 text-zinc-400 text-sm leading-relaxed">
                            {{ __('I also have programming experience in Java, PHP, and SQL, along with knowledge of data structures and object-oriented programming. I enjoy solving problems and writing efficient, well-organized code.') }}
                        </p>
                        <p class="mt-4 text-zinc-400 text-sm leading-relaxed">
                            {{ __('In addition, I have basic cybersecurity and networking knowledge, including working with Linux systems, troubleshooting environments, managing ports and services, and understanding how systems communicate and stay secure.') }}
                        </p>
                        <p class="mt-4 text-zinc-400 text-sm leading-relaxed">
                            {{ __('Overall, I focus on combining backend, frontend, and system-level skills to build practical and reliable software solutions.') }}
                        </p>
                    </div>
                </article>
            </div>
        </div>
    </main>
</body>
</html>
