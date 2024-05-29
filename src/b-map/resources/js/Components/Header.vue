<script setup>
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { ref } from 'vue';

const isMenuOpen = ref(false);

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
}

const logout = () => {
    isMenuOpen.value = false;
    sessionStorage.clear();
}

</script>

<template>
    <header class="flex justify-between items-center h-16 w-full bg-gray-200">
        <div class="flex items-center ml-2">
            <ApplicationLogo class="w-24 h-10 mr-2" />
        </div>

        <div @click="toggleMenu" class="flex items-center mr-4">
            <img src="/images/hamburge_menu.png" class="w-6 h-6 cursor-pointer" />        
        </div>       
    </header>

    <!-- メニュー展開時のメニュー領域外 -->
    <div v-if="isMenuOpen" @click="toggleMenu" class="fixed top-0 left-0 w-full h-full z-20 bg-black opacity-50"></div>

    <!-- 展開メニュー -->
    <nav :class="{ 'translate-x-full': !isMenuOpen, 'translate-x-0': isMenuOpen }" class="fixed top-0 right-0 h-full w-64 bg-black text-white transform transition-transform duration-300 z-20">
        <span @click="toggleMenu" class="text-white text-2xl/[56px] ml-4 cursor-pointer">✖</span>
        
        <ul>
            <li>
                <Link v-if="!$page.props.auth.user" :href="route('login')" class="inline-flex px-4 py-4">
                    <img src="/images/login_icon.png" class="w-5 h-5 mr-2" />
                    ログイン
                </Link>
            </li>

            <li>
                <Link v-if="!$page.props.auth.user" :href="route('register')" class="inline-flex px-4 py-4">
                    <img src="/images/person_icon.png" class="w-5 h-5 mr-2" />
                    会員登録
                </Link>
            </li>

            <li>
                <Link v-if="$page.props.auth.user" @click="logout" :href="route('logout')" method="post" as="button" class="inline-flex px-4 py-4">
                    <img src="/images/logout_icon.png" class="w-5 h-5 mr-2" />
                    ログアウト
                </Link>
            </li>

            <li>
                <Link :href="route('policy')" class="inline-flex px-4 py-4">
                    <img src="/images/key_icon.png" class="w-5 h-5 mr-2" />
                    プライバシーポリシー
                </Link>        
            </li>

            <li>
                <Link :href="route('rules')" class="inline-flex px-4 py-4">
                    <img src="/images/note_icon.png" class="w-5 h-5 mr-2" />
                    利用規約
                </Link>
            </li>

            <li>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSe_043wa6GU0ZCvxDfg7ENreF-VYAVpwYIOuZsgMhhxYBLL0g/viewform?usp=sf_link" target="_blank" class="inline-flex px-4 py-4">
                    <img src="/images/male_icon.png" class="w-5 h-5 mr-2" />
                    お問い合わせ
                </a>
            </li>
        </ul>
    </nav>
</template>