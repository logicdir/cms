<script setup>
import Layout from './Layout.vue';
</script>

<template>
    <Layout 
        :current-step="5" 
        title="Installation Complete" 
        description="LogicDir CMS has been successfully installed on your server."
    >
        <div class="text-center py-8">
            <div class="inline-flex h-24 w-24 items-center justify-center rounded-full bg-green-100 mb-8 border-4 border-white shadow-lg shadow-green-200">
                <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            
            <h3 class="text-2xl font-bold text-slate-900 mb-2">Congratulations!</h3>
            <p class="text-slate-500 mb-10 max-w-sm mx-auto leading-relaxed">
                Your CMS is ready to go. You can now login to the administration panel or view your homepage.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a
                    :href="route('admin.users.index')"
                    class="flex items-center justify-center px-6 py-4 border border-transparent text-base font-semibold rounded-2xl shadow-md text-white bg-slate-900 hover:bg-black transition-all"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Go to Admin Panel
                </a>
                <a
                    :href="route('home')"
                    class="flex items-center justify-center px-6 py-4 border border-slate-200 text-base font-semibold rounded-2xl shadow-sm text-slate-700 bg-white hover:bg-slate-50 transition-all"
                >
                    <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    View Homepage
                </a>
            </div>

            <div class="mt-12 p-6 bg-amber-50 rounded-2xl border border-amber-100 text-left">
                <h4 class="text-sm font-bold text-amber-900 mb-2 uppercase flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Security Recommended Actions
                </h4>
                <ul class="text-xs text-amber-800 space-y-2 list-disc ml-5">
                    <li>Delete the <code>app/Modules/Installer</code> directory.</li>
                    <li>Ensure the <code>.env</code> file is not web-accessible.</li>
                    <li>Change folder permissions of <code>storage</code> and <code>bootstrap/cache</code> to 755.</li>
                </ul>
            </div>
        </div>
    </Layout>
</template>
