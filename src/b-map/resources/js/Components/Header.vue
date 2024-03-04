<script setup>
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { ref } from 'vue';

const isMenuOpen = ref(false);

function toggleMenu() {
  isMenuOpen.value = !isMenuOpen.value;
}

</script>

<template>
    <header class="flex justify-between items-center h-16 w-full bg-gray-200">
        <div class="flex items-center ml-4">
            <ApplicationLogo class="w-40 h-14" />
        </div>
        <div @click="toggleMenu"   class="flex items-center mr-4">
            <img src="/images/hamburge_menu.png" class="w-6 h-6 cursor-pointer" />        
        </div>       
    </header>

    <!-- メニュー展開時のメニュー領域外 -->
    <div v-if="isMenuOpen" @click="toggleMenu" class="fixed top-0 left-0 w-full h-full z-20 bg-black opacity-50"></div>

    <!-- 展開メニュー -->
    <nav :class="{ 'translate-x-full': !isMenuOpen, 'translate-x-0': isMenuOpen }" class="fixed top-0 right-0 h-full w-64 bg-black text-white transform transition-transform duration-300 z-20">
        <span @click="toggleMenu" class="text-white text-2xl/[56px] ml-4 cursor-pointer">✖</span>
        
        <Link v-if="!$page.props.auth.user" :href="route('login')" class="flex items-center px-4 py-4">
            <img src="/images/login_icon.png" class="w-5 h-5 mr-2" />
            ログイン
        </Link>
        <Link v-if="!$page.props.auth.user" :href="route('register')" class="flex items-center px-4 py-4">
            <img src="/images/person_icon.png" class="w-5 h-5 mr-2" />
            会員登録
        </Link>
        <Link v-else :href="route('logout')" method="post" class="flex items-center px-4 py-4 cursor-pointer">
            <img src="/images/logout_icon.png" class="w-5 h-5 mr-2" />
            ログアウト
        </Link>
        <Link :href="route('policy')" class="flex items-center px-4 py-4">
            <img src="/images/key_icon.png" class="w-5 h-5 mr-2" />
            プライバシーポリシー
        </Link>
        <Link :href="route('rules')" class="flex items-center px-4 py-4">
            <img src="/images/note_icon.png" class="w-5 h-5 mr-2" />
            利用規約
        </Link>
        <Link :href="route('contact')" class="flex items-center px-4 py-4">
            <img src="/images/male_icon.png" class="w-5 h-5 mr-2" />
            お問い合わせ
        </Link>
    </nav>
</template>