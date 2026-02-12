@extends('themes::rafa.layouts.app')

@section('title', 'Welcome to Rafa Theme')

@section('content')
<div class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Background Decoration -->
    <div class="absolute inset-0 z-0">
        <div class="absolute top-1/4 -left-20 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 -right-20 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative z-10 text-center px-4">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 mb-6">
            <span class="w-2 h-2 rounded-full bg-indigo-500 animate-ping"></span>
            <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">Active Theme: Rafa</span>
        </div>
        
        <h1 class="text-5xl md:text-7xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-600 dark:from-white dark:to-slate-400 mb-6 tracking-tight">
            The Future of CMS <br/> is Here.
        </h1>
        
        <p class="max-w-2xl mx-auto text-lg text-slate-600 dark:text-slate-400 mb-10 leading-relaxed">
            Experience the next generation of content management with a theme that prioritizes speed, aesthetics, and professional performance. Built with Laravel 12 and Tailwind CSS.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="/admin/login" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-indigo-600 text-white font-bold shadow-xl shadow-indigo-600/20 hover:bg-indigo-500 transition-all hover:-translate-y-1">
                Admin Panel
            </a>
            <a href="#" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-bold border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
                Documentation
            </a>
        </div>
    </div>
</div>
@endsection
