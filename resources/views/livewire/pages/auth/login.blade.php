<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();

        $this->redirectIntended(
            default: route('dashboard', absolute: false),
            navigate: true
        );
    }
};
?>

<div class="min-h-screen w-full flex items-center justify-center px-4
            bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800">

    <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2
                rounded-3xl overflow-hidden shadow-[0_30px_80px_-20px_rgba(0,0,0,0.8)]
                bg-slate-900/80 backdrop-blur-xl border border-slate-700">

        {{-- LEFT BRAND --}}
        <div class="hidden lg:flex flex-col justify-between p-14
                    bg-gradient-to-br from-indigo-600 via-blue-600 to-violet-700 text-white">

            <div>
                <div class="flex items-center gap-3 mb-12">
                    <div class="p-3 bg-white/20 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 7l9-4 9 4-9 4-9-4z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 17l9 4 9-4" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 12l9 4 9-4" />
                        </svg>
                    </div>
                    <span class="text-2xl font-bold tracking-wide">
                        Determined Studio
                    </span>
                </div>

                <h1 class="text-4xl font-extrabold leading-tight mb-6">
                    Warehouse & Production<br>
                    Management System
                </h1>

                <p class="text-indigo-100 text-lg max-w-md">
                    Sistem terintegrasi untuk pengelolaan stok, material,
                    dan produksi secara cepat, rapi, dan terkontrol.
                </p>
            </div>

            <p class="text-sm text-indigo-200">
                © {{ date('Y') }} Determined Studio
            </p>
        </div>

        {{-- RIGHT FORM --}}
        <div class="flex items-center justify-center p-10 sm:p-14
                    bg-slate-950 text-slate-100">

            <div class="w-full max-w-md">

                <h2 class="text-3xl font-bold mb-2 tracking-tight">
                    Masuk ke Sistem
                </h2>
                <p class="text-slate-400 mb-8">
                    Silakan login menggunakan akun terdaftar
                </p>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form wire:submit="login" class="space-y-6">

                    {{-- EMAIL --}}
                    <div>
                        <label class="block text-sm font-medium mb-1 text-slate-300">
                            Email
                        </label>
                        <input
                            wire:model.defer="form.email"
                            type="email"
                            required
                            autofocus
                            class="w-full rounded-xl bg-slate-900 border border-slate-700
                                   px-4 py-3 text-slate-100
                                   focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="admin@company.com">
                        <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                    </div>

                    {{-- PASSWORD --}}
                    <div>
                        <label class="block text-sm font-medium mb-1 text-slate-300">
                            Password
                        </label>
                        <input
                            wire:model.defer="form.password"
                            type="password"
                            required
                            class="w-full rounded-xl bg-slate-900 border border-slate-700
                                   px-4 py-3 text-slate-100
                                   focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="••••••••">
                        <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
                    </div>

                    {{-- REMEMBER --}}
                    <div class="flex items-center justify-between text-sm text-slate-400">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" wire:model="form.remember"
                                   class="rounded border-slate-600 text-indigo-600">
                            Ingat saya
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-indigo-400 hover:underline">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    {{-- BUTTON --}}
                    <button
                        type="submit"
                        class="w-full py-3 rounded-xl font-semibold text-white
                               bg-gradient-to-r from-indigo-600 to-blue-600
                               hover:from-indigo-700 hover:to-blue-700
                               transition-all shadow-lg shadow-indigo-600/30">
                        Masuk Dashboard
                    </button>

                </form>
            </div>
        </div>

    </div>
</div>
