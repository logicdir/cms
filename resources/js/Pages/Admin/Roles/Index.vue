<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps({
    roles: Array,
    permissions: Object, // Grouped by module
});

const activeRole = ref(props.roles[0]);
const selectedPermissions = ref(activeRole.value.permissions.map(p => p.id));
const saving = ref(false);

const selectRole = (role) => {
    activeRole.value = role;
    selectedPermissions.value = role.permissions.map(p => p.id);
};

const togglePermission = (id) => {
    const index = selectedPermissions.value.indexOf(id);
    if (index > -1) {
        selectedPermissions.value.splice(index, 1);
    } else {
        selectedPermissions.value.push(id);
    }
};

const savePermissions = () => {
    saving.value = true;
    router.post(route('admin.roles.permissions', activeRole.value.id), {
        permissions: selectedPermissions.value
    }, {
        onSuccess: () => {
            saving.value = false;
            // Update the local list of permissions for the active role
            activeRole.value.permissions = selectedPermissions.value.map(id => {
                // Find permission object in one of the modules
                for (const module in props.permissions) {
                    const found = props.permissions[module].find(p => p.id === id);
                    if (found) return found;
                }
                return null;
            }).filter(p => p !== null);
        },
        onFinish: () => saving.value = false
    });
};

const isPermissionSelected = (id) => selectedPermissions.value.includes(id);

const isAllModuleSelected = (modulePermissions) => {
    return modulePermissions.every(p => isPermissionSelected(p.id));
};

const toggleModule = (modulePermissions) => {
    if (isAllModuleSelected(modulePermissions)) {
        // Remove all
        const idsToRemove = modulePermissions.map(p => p.id);
        selectedPermissions.value = selectedPermissions.value.filter(id => !idsToRemove.includes(id));
    } else {
        // Add all missing
        modulePermissions.forEach(p => {
            if (!isPermissionSelected(p.id)) selectedPermissions.value.push(p.id);
        });
    }
};
</script>

<template>
    <Head title="Roles & Permissions" />

    <div class="p-6">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-slate-900">Roles & Permissions</h1>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-400 uppercase">Selected Role:</span>
                <span class="px-3 py-1 bg-indigo-600 text-white rounded-lg text-xs font-bold uppercase tracking-wider shadow-md shadow-indigo-100 italic">
                    {{ activeRole.name }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Roles Sidebar -->
            <div class="lg:col-span-1 space-y-3">
                <button 
                    v-for="role in roles" 
                    :key="role.id"
                    @click="selectRole(role)"
                    :class="[
                        'w-full text-left p-5 rounded-2xl border transition-all relative overflow-hidden group',
                        activeRole.id === role.id 
                            ? 'bg-white border-indigo-600 shadow-xl shadow-indigo-50 translate-x-1' 
                            : 'bg-white border-slate-100 hover:border-slate-300 shadow-sm'
                    ]"
                >
                    <div v-if="activeRole.id === role.id" class="absolute top-0 left-0 w-1 h-full bg-indigo-600"></div>
                    <div class="flex items-center justify-between mb-1">
                        <span :class="['font-bold text-sm tracking-wide', activeRole.id === role.id ? 'text-indigo-600' : 'text-slate-800']">
                            {{ role.name }}
                        </span>
                        <span v-if="role.is_super_admin" class="text-[8px] bg-amber-100 text-amber-700 px-1 py-0.5 rounded font-black uppercase">Super</span>
                    </div>
                    <p class="text-xs text-slate-400 line-clamp-1 group-hover:text-slate-500">{{ role.description || 'No description provided' }}</p>
                </button>
            </div>

            <!-- Permissions Matrix -->
            <div class="lg:col-span-3 space-y-8">
                <div v-if="activeRole.is_super_admin" class="bg-amber-50 border border-amber-100 p-6 rounded-2xl flex items-center">
                    <svg class="w-8 h-8 text-amber-400 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <div>
                        <h4 class="text-amber-900 font-bold uppercase text-xs tracking-wider">Super Admin Override</h4>
                        <p class="text-amber-700 text-xs mt-1">This role has bypass-all capabilities. Manual permission assignment is restricted but visually suggested.</p>
                    </div>
                </div>

                <div v-for="(modulePermissions, module) in permissions" :key="module" class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden animate-in fade-in slide-in-from-bottom-2">
                    <div class="bg-slate-50/50 px-6 py-4 flex items-center justify-between border-b border-slate-100">
                        <h3 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em]">{{ module }} Module</h3>
                        <button @click="toggleModule(modulePermissions)" :disabled="activeRole.is_super_admin" class="text-[10px] font-bold text-indigo-600 uppercase hover:text-indigo-800 disabled:opacity-0 transition-all">
                            {{ isAllModuleSelected(modulePermissions) ? 'Deselect All' : 'Select All' }}
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-px bg-slate-100">
                        <div 
                            v-for="permission in modulePermissions" 
                            :key="permission.id"
                            @click="!activeRole.is_super_admin && togglePermission(permission.id)"
                            :class="[
                                'p-5 flex items-start group justify-between cursor-pointer transition-all bg-white hover:bg-slate-50',
                                activeRole.is_super_admin ? 'cursor-default' : ''
                            ]"
                        >
                            <div class="pr-8">
                                <div :class="['text-sm font-bold mb-0.5 transition-colors', isPermissionSelected(permission.id) || activeRole.is_super_admin ? 'text-indigo-600' : 'text-slate-700']">
                                    {{ permission.name }}
                                </div>
                                <div class="text-[11px] text-slate-400 group-hover:text-slate-500 leading-relaxed">{{ permission.description }}</div>
                            </div>
                            <div 
                                :class="[
                                    'w-5 h-5 rounded-md border-2 flex items-center justify-center transition-all',
                                    isPermissionSelected(permission.id) || activeRole.is_super_admin 
                                        ? 'bg-indigo-600 border-indigo-600 text-white' 
                                        : 'bg-white border-slate-200 shadow-inner'
                                ]"
                            >
                                <svg v-if="isPermissionSelected(permission.id) || activeRole.is_super_admin" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" /></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="!activeRole.is_super_admin" class="flex justify-end pt-4">
                    <button 
                        @click="savePermissions" 
                        :disabled="saving"
                        class="px-10 py-4 bg-indigo-600 text-white rounded-2xl font-bold text-xs tracking-widest uppercase shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95 disabled:opacity-50"
                    >
                        <span v-if="saving" class="inline-block animate-spin mr-2">◌</span>
                        {{ saving ? 'Syncing...' : 'Save Permission Matrix' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.animate-in {
    animation-duration: 0.4s;
}
</style>
