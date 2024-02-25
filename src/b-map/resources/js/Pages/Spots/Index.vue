<script setup>
import { Head } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import FooterMenu from '@/Components/FooterMenu.vue';
import { ref } from 'vue';
import axios from 'axios';
import TextInput from '@/Components/TextInput.vue';
import { defineProps, onMounted } from 'vue';

const props = defineProps({
    message: String,
    spots: Object,
});

onMounted(() => {
    // 一番最初の投稿の一枚目の画像を取得
    console.log(props.spots[0].spot_images[0].image);
    console.log(props.message);
});

</script>

<template>
  
</template>