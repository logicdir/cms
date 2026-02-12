<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';

const props = defineProps({
    users: Object,
    roles: Array,
    filters: Object,
});

const search = ref(props.filters.search);
const role = ref(props.filters.role);
const status = ref(props.filters.status);
const selected = ref([]);

watch([search, role, status], debounce(() => {
    router.get(route('admin.users.index'), {
        search: search.value,
        role: role.value,
        status: status.value
    }, { preserveState: true, replace: true });
}, 300));

const toggleSelectAll = (e) => {
    if (e.target.checked) {
        selected.value = props.users.data.map(u => u.id);
    } else {
        selected.value = [];
    }
};

const deleteUser = (id) => {
    if (confirm('Are you sure you want to delete this user?')) {
        router.delete(route('admin.users.destroy', id));
    }
};

const bulkDelete = () => {
    if (confirm(`Are you sure you want to delete ${selected.value.length} selected users?`)) {
        router.post(route('admin.users.bulk-destroy'), { ids: selected.value }, {
            onSuccess: () => selected.value = []
        });
    }
};

const getStatusColor = (status) => {
    return {
        'active': 'bg-green-100 text-green-800',
        'inactive': 'bg-gray-100 text-gray-800',
        'banned': 'bg-red-100 text-red-800'
    }[status] || 'bg-gray-100 text-gray-800';
};
</script>

<template>
    <Head title="Users Management" />

    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Users</h1>
            <Link :href="route('admin.users.create')" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-all font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Add User
            </Link>
        </div>

        <!-- Filters & Actions -->
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm mb-6 flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-4 flex-1">
                <div class="relative min-w-[240px]">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </span>
                    <input v-model="search" type="text" placeholder="Search by name or email..." class="pl-10 block w-full border-slate-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
                
                <select v-model="role" class="border-slate-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option :value="undefined">All Roles</option>
                    <option v-for="r in roles" :key="r.id" :value="r.slug">{{ r.name }}</option>
                </select>

                <select v-model="status" class="border-slate-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option :value="undefined">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="banned">Banned</option>
                </select>
            </div>

            <div v-if="selected.length > 0">
                <button @click="bulkDelete" class="bg-red-50 text-red-600 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-red-100 transition-all">
                    Delete Selected ({{ selected.length }})
                </button>
            </div>
        </div>

        <!-- User Table -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left"><input type="checkbox" @change="toggleSelectAll" class="rounded border-slate-300 text-indigo-600" /></th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Roles</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr v-for="user in users.data" :key="user.id" class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4"><input v-model="selected" type="checkbox" :value="user.id" class="rounded border-slate-300 text-indigo-600" /></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <img :src="user.avatar_url || `https://ui-avatars.com/api/?name=${user.name}&background=6366f1&color=fff`" class="w-10 h-10 rounded-full mr-3 border-2 border-white shadow-sm" />
                                <div>
                                    <div class="text-sm font-bold text-slate-900">{{ user.name }}</div>
                                    <div class="text-xs text-slate-500">{{ user.email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                <span v-for="role in user.roles" :key="role.id" class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 uppercase">
                                    {{ role.name }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span :class="['px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide', getStatusColor(user.status)]">
                                {{ user.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500">{{ new Date(user.created_at).toLocaleDateString() }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <Link :href="route('admin.users.edit', user.id)" class="text-indigo-600 hover:text-indigo-900 font-bold text-xs uppercase">Edit</Link>
                            <button @click="deleteUser(user.id)" class="text-red-500 hover:text-red-800 font-bold text-xs uppercase">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
