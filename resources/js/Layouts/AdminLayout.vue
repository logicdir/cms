<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { 
    HomeIcon, 
    DocumentTextIcon, 
    NewspaperIcon, 
    ChatBubbleBottomCenterTextIcon, 
    PhotoIcon, 
    MegaphoneIcon, 
    BriefcaseIcon, 
    ChatBubbleLeftRightIcon, 
    EnvelopeIcon, 
    QuestionMarkCircleIcon, 
    PaperAirplaneIcon, 
    FolderIcon, 
    PaintBrushIcon, 
    PuzzlePieceIcon, 
    WrenchScrewdriverIcon, 
    Cog6ToothIcon, 
    ShieldCheckIcon,
    ChevronDownIcon,
    ChevronRightIcon,
    SunIcon,
    MoonIcon,
    TagIcon,
    ChartBarIcon,
    StarIcon,
    WrenchIcon,
    CubeIcon,
    ListBulletIcon,
    SwatchIcon,
    AdjustmentsHorizontalIcon,
    RectangleGroupIcon,
    CommandLineIcon,
    CheckCircleIcon,
    PlusCircleIcon,
    Squares2X2Icon
} from '@heroicons/vue/24/outline';

const page = usePage();
const user = page.props.auth?.user;

const sidebarOpened = ref(false);
const sidebarCollapsed = ref(false);

const navigationGroups = [
    {
        name: 'Main',
        items: [
            { name: 'Dashboard', href: route('admin.dashboard'), icon: HomeIcon, active: route().current('admin.dashboard') },
        ]
    },
    {
        name: 'Communications',
        items: [
            { 
                name: 'Blog', 
                href: '#', 
                icon: NewspaperIcon, 
                active: false,
                children: [
                    { name: 'Posts', href: '#', icon: DocumentTextIcon, active: false },
                    { name: 'Categories', href: '#', icon: FolderIcon, active: false },
                    { name: 'Tags', href: '#', icon: TagIcon, active: false },
                    { name: 'Reports', href: '#', icon: ChartBarIcon, active: false },
                ]
            },
            { name: 'Testimonials', href: '#', icon: StarIcon, active: false },
            { name: 'Galleries', href: '#', icon: PhotoIcon, active: false },
            { name: 'Announcements', href: '#', icon: MegaphoneIcon, active: false },
            { 
                name: 'Portfolio', 
                href: '#', 
                icon: BriefcaseIcon, 
                active: false,
                children: [
                    { name: 'Projects', href: '#', icon: FolderIcon, active: false },
                    { name: 'Service Categories', href: '#', icon: TagIcon, active: false },
                    { name: 'Services', href: '#', icon: WrenchIcon, active: false },
                    { name: 'Packages', href: '#', icon: CubeIcon, active: false },
                ]
            },
            { name: 'Comments', href: '#', icon: ChatBubbleLeftRightIcon, active: false },
        ]
    },
    {
        name: 'Connect',
        items: [
            { 
                name: 'Contact', 
                href: '#', 
                icon: EnvelopeIcon, 
                active: false,
                children: [
                    { name: 'Contacts', href: '#', icon: Squares2X2Icon, active: false },
                    { name: 'Custom Fields', href: '#', icon: CubeIcon, active: false },
                ]
            },
            { 
                name: 'FAQs', 
                href: '#', 
                icon: QuestionMarkCircleIcon, 
                active: false,
                children: [
                    { name: 'FAQs', href: '#', icon: ListBulletIcon, active: false },
                    { name: 'Categories', href: '#', icon: FolderIcon, active: false },
                ]
            },
            { name: 'Newsletters', href: '#', icon: PaperAirplaneIcon, active: false },
            { name: 'Media', href: '#', icon: FolderIcon, active: false },
        ]
    },
    {
        name: 'System & Tools',
        items: [
            { 
                name: 'Appearance', 
                href: '#', 
                icon: PaintBrushIcon, 
                active: false,
                children: [
                    { name: 'Themes', href: route('admin.appearance.themes.index'), icon: SwatchIcon, active: route().current('admin.appearance.themes.*') },
                    { name: 'Menus', href: '#', icon: AdjustmentsHorizontalIcon, active: false },
                    { name: 'Widgets', href: '#', icon: RectangleGroupIcon, active: false },
                    { name: 'Theme Options', href: '#', icon: ListBulletIcon, active: false },
                    { name: 'Custom CSS', href: '#', icon: CommandLineIcon, active: false },
                    { name: 'Custom JS', href: '#', icon: CommandLineIcon, active: false },
                    { name: 'Custom HTML', href: '#', icon: CommandLineIcon, active: false },
                    { name: 'Robots.txt Editor', href: '#', icon: DocumentTextIcon, active: false },
                ]
            },
            { 
                name: 'Plugins', 
                href: '#', 
                icon: PuzzlePieceIcon, 
                active: false,
                children: [
                    { name: 'Installed Plugins', href: '#', icon: CheckCircleIcon, active: false },
                    { name: 'Add New Plugin', href: '#', icon: PlusCircleIcon, active: false },
                ]
            },
            { 
                name: 'Tools', 
                href: '#', 
                icon: WrenchScrewdriverIcon, 
                active: false,
                children: [
                    { name: 'Export/Import Data', href: '#', icon: CubeIcon, active: false },
                ]
            },
            { 
                name: 'Settings', 
                href: route('admin.settings.index'), 
                icon: Cog6ToothIcon, 
                active: route().current('admin.settings.*'),
                children: [
                    { name: 'General', href: route('admin.settings.general'), active: route().current('admin.settings.general') },
                    { name: 'SEO Settings', href: '#', active: false },
                    { name: 'Mail Settings', href: '#', active: false },
                ]
            },
            { name: 'Platform Admin', href: route('admin.platform.index'), icon: ShieldCheckIcon, active: route().current('admin.platform.*') },
        ]
    }
];

const openSubmenus = ref({});

const toggleSubmenu = (name) => {
    openSubmenus.value[name] = !openSubmenus.value[name];
};

// Auto-open active groups
navigationGroups.forEach(group => {
    group.items.forEach(item => {
        if (item.active && item.children) {
            openSubmenus.value[item.name] = true;
        }
    });
});

const toggleSidebar = () => {
    sidebarCollapsed.value = !sidebarCollapsed.value;
};

// Theme Toggle Logic
const isDark = ref(true); // Default to dark since current design is dark

// Initialize theme from localStorage or system preference
const initTheme = () => {
    const savedTheme = localStorage.getItem('admin_theme');
    if (savedTheme) {
        isDark.value = savedTheme === 'dark';
    } else {
        isDark.value = window.matchMedia('(prefers-color-scheme: dark)').matches;
    }
    updateThemeClass();
};

const updateThemeClass = () => {
    if (isDark.value) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('admin_theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('admin_theme', 'light');
    }
};

const toggleTheme = () => {
    isDark.value = !isDark.value;
    updateThemeClass();
};

// Call init on mount
import { onMounted } from 'vue';
onMounted(() => {
    initTheme();
});

const logout = () => {
    // Implement logout logic if needed, usually via a form post
};
</script>

<template>
    <div class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-200 font-outfit selection:bg-indigo-500/30 transition-colors duration-300">
        <!-- Sidebar -->
        <aside 
            class="fixed inset-y-0 left-0 z-50 bg-white/80 dark:bg-slate-900/50 backdrop-blur-xl border-r border-slate-200 dark:border-slate-800 transition-all duration-300 ease-in-out lg:translate-x-0"
            :class="[
                sidebarOpened ? 'translate-x-0' : '-translate-x-full',
                sidebarCollapsed ? 'w-20' : 'w-64'
            ]"
        >
            <div class="h-full flex flex-col">
                <!-- Brand and Toggle -->
                <div class="p-6 flex items-center gap-3" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="w-8 h-8 flex-shrink-0 rounded-lg bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center shadow-lg shadow-indigo-500/20 transition-all duration-300">
                            <span class="text-white font-bold text-sm">L</span>
                        </div>
                        <span v-if="!sidebarCollapsed" class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-600 dark:from-white dark:to-slate-400 truncate transition-all duration-300">LogicDir</span>
                    </div>
                </div>

                <!-- Nav -->
                <nav class="flex-1 px-4 space-y-8 overflow-y-auto custom-scrollbar pt-4 pb-8">
                    <div v-for="group in navigationGroups" :key="group.name" class="space-y-4">
                        <h3 
                            v-if="!sidebarCollapsed" 
                            class="px-4 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-600/80 transition-opacity duration-300"
                        >
                            {{ group.name }}
                        </h3>
                        <div v-else class="h-px bg-slate-200 dark:bg-slate-800/50 mx-2"></div>
                        
                        <div class="space-y-1">
                            <div v-for="item in group.items" :key="item.name" class="relative group/tooltip">
                                <!-- Parent Item -->
                                <component 
                                    :is="item.children ? 'button' : Link"
                                    :href="item.children ? undefined : item.href"
                                    @click="item.children ? toggleSubmenu(item.name) : undefined"
                                    class="w-full flex items-center rounded-xl transition-all duration-200 group/item"
                                    :class="[
                                        item.active 
                                            ? 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 shadow-[inset_0_1px_0_0_rgba(255,255,255,0.05)]' 
                                            : 'hover:bg-slate-100 dark:hover:bg-slate-800/50 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200',
                                        sidebarCollapsed ? 'justify-center p-2.5' : 'justify-between px-4 py-2.5'
                                    ]"
                                    :title="sidebarCollapsed ? item.name : ''"
                                >
                                    <div class="flex items-center" :class="sidebarCollapsed ? '' : 'gap-3'">
                                        <component 
                                            :is="item.icon" 
                                            class="w-5 h-5 transition-colors"
                                            :class="item.active ? 'text-indigo-500 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500 group-hover/item:text-slate-600 dark:group-hover/item:text-slate-300'"
                                        />
                                        <span v-if="!sidebarCollapsed" class="font-medium text-[13px] whitespace-nowrap">{{ item.name }}</span>
                                    </div>
                                    
                                    <component 
                                        v-if="item.children && !sidebarCollapsed"
                                        :is="openSubmenus[item.name] ? ChevronDownIcon : ChevronRightIcon"
                                        class="w-3.5 h-3.5 text-slate-400 dark:text-slate-600 group-hover/item:text-slate-600 dark:group-hover/item:text-slate-400 transition-colors"
                                    />
                                </component>

                                <!-- Submenu -->
                                <div 
                                    v-if="item.children && !sidebarCollapsed && openSubmenus[item.name]" 
                                    class="mt-1 ml-4 pl-4 border-l border-slate-200 dark:border-slate-800/50 space-y-1"
                                >
                                    <Link 
                                        v-for="sub in item.children" 
                                        :key="sub.name"
                                        :href="sub.href"
                                        class="flex items-center gap-3 px-4 py-2 rounded-lg text-xs font-medium transition-colors"
                                        :class="sub.active 
                                            ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-500/5 font-bold shadow-sm' 
                                            : 'text-slate-500 dark:text-slate-500 hover:text-slate-900 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/30'"
                                    >
                                        <component v-if="sub.icon" :is="sub.icon" class="w-3.5 h-3.5 opacity-60" />
                                        {{ sub.name }}
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main 
            class="min-h-screen flex flex-col transition-all duration-300 ease-in-out"
            :class="sidebarCollapsed ? 'lg:pl-20' : 'lg:pl-64'"
        >
            <!-- Header -->
            <header class="sticky top-0 z-40 h-20 bg-white/80 dark:bg-slate-950/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800/50 flex items-center justify-between px-8 transition-colors duration-300">
                <div class="flex items-center gap-8 flex-1">
                    <div class="flex items-center gap-4">
                        <!-- Mobile Menu Toggle -->
                        <button @click="sidebarOpened = !sidebarOpened" class="lg:hidden text-slate-500 dark:text-slate-400 p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                        </button>

                        <!-- Desktop Toggle (Burger) -->
                        <button 
                            @click="toggleSidebar" 
                            class="hidden lg:flex p-2 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition-colors"
                            :title="sidebarCollapsed ? 'Expand Sidebar' : 'Collapse Sidebar'"
                        >
                            <svg class="w-6 h-6 transition-transform duration-300" :class="sidebarCollapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                        </button>
                    </div>

                    <!-- Search -->
                    <div class="hidden md:flex items-center w-full max-w-md">
                        <div class="relative w-full group">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 dark:text-slate-500 group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </span>
                            <input 
                                type="text" 
                                placeholder="Search" 
                                class="w-full pl-9 pr-24 py-2 bg-slate-100 dark:bg-slate-900/50 border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-300 rounded-xl focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm"
                            />
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-[10px] font-medium text-slate-400 dark:text-slate-600 border border-slate-200 dark:border-slate-800 px-1.5 py-0.5 rounded-md bg-white dark:bg-slate-950/50">ctrl/cmd + k</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Utilities -->
                <div class="flex items-center gap-6">
                    <!-- View Website Button -->
                    <a :href="route('home')" target="_blank" class="hidden sm:flex items-center gap-2 px-4 py-2 border border-slate-200 dark:border-slate-700 hover:border-slate-400 dark:hover:border-slate-500 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 transition-all bg-white dark:bg-slate-900/30 hover:bg-slate-50 dark:hover:bg-slate-800 shadow-sm border-b-2 border-slate-200 dark:border-slate-800">
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-600 dark:group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9h18" /></svg>
                        View website
                    </a>

                    <!-- Theme Toggle -->
                    <button 
                        @click="toggleTheme"
                        class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white transition-all shadow-sm border border-slate-200 dark:border-slate-800"
                        :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
                    >
                        <component :is="isDark ? MoonIcon : SunIcon" class="w-5 h-5" />
                    </button>

                    <!-- Notifications -->
                    <button class="relative p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white transition-all shadow-sm border border-slate-200 dark:border-slate-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-indigo-600 border-2 border-white dark:border-slate-950 text-[8px] font-bold text-white rounded-full flex items-center justify-center">0</span>
                    </button>

                    <!-- User Profile -->
                    <div class="flex items-center gap-3 pl-4 border-l border-slate-200 dark:border-slate-800/50">
                        <img :src="user?.avatar_url" class="w-10 h-10 rounded-xl object-cover border border-slate-200 dark:border-slate-800 shadow-lg" alt="">
                        <div class="hidden lg:block min-w-0">
                            <p class="text-sm font-bold text-slate-900 dark:text-white truncate leading-tight">{{ user?.name }}</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500 truncate mt-0.5">{{ user?.email }}</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Body -->
            <div class="p-6 lg:p-10 flex-1 bg-[radial-gradient(circle_at_top_right,rgba(99,102,241,0.03),transparent_50%)] dark:bg-[radial-gradient(circle_at_top_right,rgba(99,102,241,0.05),transparent_50%)] transition-colors duration-300">
                <slot />
            </div>

            <!-- Footer -->
            <footer class="mt-auto px-8 py-6 border-t border-slate-200 dark:border-slate-800/50 flex flex-col sm:flex-row items-center justify-between gap-4 transition-colors">
                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-500 font-medium">
                    <span>&copy; {{ new Date().getFullYear() }} LogicDir.</span>
                    <span class="hidden sm:inline text-slate-300 dark:text-slate-800">•</span>
                    <span>All rights reserved.</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm transition-all duration-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                        <span class="text-[10px] font-bold text-slate-600 dark:text-slate-400">v1.0.0</span>
                    </div>
                </div>
            </footer>
        </main>
    </div>
</template>

<style scoped>
/* Scoped styles for micro-animations */
.font-outfit { font-family: 'Outfit', sans-serif; }

.custom-scrollbar::-webkit-scrollbar {
    display: none;
}
.custom-scrollbar {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
}
</style>
