<script setup>
import { ref } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
    roles: Array,
});

const isEdit = !!props.user;

const form = useForm({
    name: props.user?.name ?? '',
    email: props.user?.email ?? '',
    password: '',
    password_confirmation: '',
    status: props.user?.status ?? 'active',
    roles: props.user?.roles?.map(r => r.id) ?? [],
    avatar: null,
});

const avatarPreview = ref(props.user?.avatar_url ?? null);

const onAvatarChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.avatar = file;
        const reader = new FileReader();
        reader.onload = (e) => avatarPreview.value = e.target.result;
        reader.readAsDataURL(file);
    }
};

const submit = () => {
    if (isEdit) {
        // Use POST with _method=PUT for file upload compatibility
        form.transform(data => ({
            ...data,
            _method: 'PUT'
        })).post(route('admin.users.update', props.user.id));
    } else {
        form.post(route('admin.users.store'));
    }
};
</script>

<template>
    <Head :title="isEdit ? 'Edit User' : 'Create User'" />

    <div class="p-6 max-w-4xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-slate-900">{{ isEdit ? 'Edit User' : 'Add New User' }}</h1>
            <Link :href="route('admin.users.index')" class="text-sm font-bold text-indigo-600 uppercase tracking-wide flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Back to list
            </Link>
        </div>

        <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left: Avatar & Status -->
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-center">
                    <div class="relative inline-block mb-4">
                        <img :src="avatarPreview || `https://ui-avatars.com/api/?name=${form.name || 'User'}&background=6366f1&color=fff&size=128`" 
                             class="w-32 h-32 rounded-3xl object-cover border-4 border-slate-50 shadow-inner" />
                        <label class="absolute -bottom-2 -right-2 bg-white p-2 rounded-xl border border-slate-200 shadow-lg cursor-pointer hover:bg-slate-50 transition-all">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <input type="file" @change="onAvatarChange" class="hidden" accept="image/*" />
                        </label>
                    </div>
                    <p class="text-xs text-slate-500">Allowed JPG, GIF or PNG. Max size of 2MB</p>
                    <p v-if="form.errors.avatar" class="mt-2 text-xs text-red-500">{{ form.errors.avatar }}</p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Account Status</h3>
                    <div class="space-y-3">
                        <label v-for="s in ['active', 'inactive', 'banned']" :key="s" class="flex items-center p-3 rounded-xl border border-slate-50 cursor-pointer hover:bg-slate-50 transition-all">
                            <input type="radio" v-model="form.status" :value="s" class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500" />
                            <span class="ml-3 text-sm font-semibold capitalize text-slate-700">{{ s }}</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Right: Details & Roles -->
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm space-y-6">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-50 pb-4">Personal Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Full Name</label>
                            <input v-model="form.name" type="text" class="block w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="John Doe" />
                            <p v-if="form.errors.name" class="mt-1 text-xs text-red-500">{{ form.errors.name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Email Address</label>
                            <input v-model="form.email" type="email" class="block w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="john@example.com" />
                            <p v-if="form.errors.email" class="mt-1 text-xs text-red-500">{{ form.errors.email }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-50">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                            <input v-model="form.password" type="password" class="block w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm" />
                            <p v-if="form.errors.password" class="mt-1 text-xs text-red-500">{{ form.errors.password }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Confirm Password</label>
                            <input v-model="form.password_confirmation" type="password" class="block w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm" />
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-50 pb-4 mb-6">Assign Roles</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <label v-for="role in roles" :key="role.id" class="flex items-center p-4 rounded-xl border border-slate-100 cursor-pointer hover:bg-slate-50 hover:border-indigo-100 transition-all" 
                               :class="{'border-indigo-200 bg-indigo-50/50': form.roles.includes(role.id)}">
                            <input v-model="form.roles" type="checkbox" :value="role.id" class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500" />
                            <span class="ml-3 text-sm font-bold text-slate-700">{{ role.name }}</span>
                        </label>
                    </div>
                    <p v-if="form.errors.roles" class="mt-2 text-xs text-red-500">{{ form.errors.roles }}</p>
                </div>

                <div class="flex justify-end gap-3">
                    <Link :href="route('admin.users.index')" class="px-8 py-3 bg-slate-100 text-slate-700 rounded-xl font-bold text-sm tracking-wide uppercase hover:bg-slate-200 transition-all">Cancel</Link>
                    <button type="submit" :disabled="form.processing" class="px-10 py-3 bg-indigo-600 text-white rounded-xl font-bold text-sm tracking-wide uppercase shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all disabled:opacity-50">
                        {{ form.processing ? 'Saving...' : (isEdit ? 'Update User' : 'Create User') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>
