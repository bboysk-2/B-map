<script setup>
import { defineProps } from 'vue';
import Header from '@/Components/Header.vue';
import FooterMenu from '@/Components/FooterMenu.vue';

const props = defineProps({
    error: String,
    success: String,
});
</script>

<template>
    <body>
        <Header />
        <div class="min-h-screen" style="background-image: url('/images/background_image.png');">
            <!-- エラーもしくは処理が成功した際のフラッシュメッセージ表示領域 -->
            <div v-if="success" class="bg-blue-400 p-3 w-screen">
                <p class="text-white">{{ success }}</p>
            </div>
            <div v-if="error" class="bg-red-400 p-3 w-screen">
                <p class="text-white">{{ error }}</p>
            </div>
            
            <div class="container mx-auto px-5 mb-10">                
                <div class="flex justify-center align-center">
                    <div class="w-full max-w-sm mt-12 mb-24">
                        <slot></slot>
                    </div>
                </div>
            </div>
        </div>
        <FooterMenu />
    </body>
</template>