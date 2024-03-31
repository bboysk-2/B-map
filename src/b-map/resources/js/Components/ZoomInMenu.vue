<script setup>
import { defineProps, ref, reactive } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    spotId: Number,
});


const isMenuOpen = reactive({
    [props.spotId]:false
});

const toggleMenu = (e, spotId) => {
    e.preventDefault();
    isMenuOpen[spotId] = !isMenuOpen[spotId];
};
</script>

<template>
    <div v-if="isMenuOpen[spotId]" @click="toggleMenu($event,spotId)" class="fixed top-0 left-0 w-full h-full z-20"></div>

    <div class="absolute top-3 right-3 w-12 h-5 bg-white rounded-lg flex items-center justify-center"
    @click="toggleMenu($event,spotId)">
        <p class="text-xl text-center ">・・・</p>
    </div>

    <div v-if="isMenuOpen[spotId]" class="zoomIn absolute bg-white w-32 h-20 top-9 right-3 rounded-lg z-30">
        <ul>
            <Link :href="route('spots.edit',{spot:spotId})">
                <li class="py-2 px-3 border-b border-slate-300">編集</li>
            </Link>
            
            <li class="py-2 px-3">削除</li>
        </ul>
    </div>
</template>
