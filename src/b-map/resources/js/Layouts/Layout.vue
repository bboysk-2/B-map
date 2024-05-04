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
            <div v-if="success" class="bg-blue-400 p-3 w-screen">
                <p class="text-white">{{ success }}</p>
            </div>

            <div v-if="$page.props.flash.message" class="bg-blue-400 p-3 w-screen">
                <p class="text-white">{{ $page.props.flash.message }}</p>
            </div>

            <div v-if="error" class="bg-red-400 p-3 w-screen">
                <p class="text-white">{{ error }}</p>
            </div>
            
            <div class="container mx-auto px-2 mb-10">                
                <div class="flex justify-center align-center">
                    <div class="w-full max-w-sm mt-12 mb-16">
                        <slot></slot>
                    </div>
                </div>
            </div>
        </div>
        <FooterMenu />
    </body>
</template>