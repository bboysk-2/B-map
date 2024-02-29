<script setup>
import { defineProps } from 'vue';
import { Link } from '@inertiajs/vue3';


const props = defineProps({
    spots: Object,
});
</script>

<template>
    <div v-for="spot in spots" :key="spot.id">
        <Link :href="route('spots.show',{spot:spot.id})"> 
            <div class="bg-white w-full my-3 mx-auto py-2 border border-slate-300 rounded-lg shadow-3xl">
                <!-- 画像がない場合 -->
                <div v-if="!spot.spot_images[0]?.image" class="w-full h-36 flex items-center justify-center my-2 px-5">
                    <div class="w-full h-36 bg-gray-300 flex flex-col justify-center items-center rounded-lg">
                        <img src="/images/no_image.png" class="mb-2">
                        <p class="text-lg font-bold">No image</p>
                    </div>
                </div>
                <!-- 画像がある場合 -->
                <div v-if="spot.spot_images[0]?.image" class="w-full h-36 bg-white flex items-center justify-center my-2 px-5">
                    <img :src="spot.spot_images[0].image" class="object-cover w-full h-full rounded-lg border border-slate-300">
                </div>
    
                <div class="mx-6">
                    <p class="text-xl font-bold">{{ spot.name }}</p>
                    <p>{{ spot.address }}</p>
                </div>
            </div>
        </Link>   
    </div>           
</template>