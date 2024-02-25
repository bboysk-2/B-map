<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <nav class="fixed bottom-0 left-0 right-0 w-full h-16 bg-gray-200 z-10">
        <div class="flex container mx-auto px-9 justify-center items-center h-full">
            <div class="flex justify-between items-center w-full max-w-sm ">
                <Link :href="route('home')" class="flex flex-col items-center">
                    <img src="/images/footer_home_icon.png" class="w-6 h-6" />
                    <p class="text-xs">ホーム</p>
                </Link>
                <Link :href="route('home')" class="flex flex-col items-center">
                    <img src="/images/footer_search_icon.png" class="w-6 h-6" />
                    <p class="text-xs">探す</p>
                </Link>
                <Link :href="route('mypage')" class="flex flex-col items-center">
                    <img src="/images/footer_person_icon.png" class="w-6 h-6" />
                    <p class="text-xs">マイページ</p>
                </Link>
                <Link :href="route('spots.create')" class="flex flex-col items-center">
                    <img src="/images/footer_add_icon.png" class="w-6 h-6" />
                    <p class="text-xs">投稿</p>
                </Link>
            </div>
        </div>
    </nav>
</template>