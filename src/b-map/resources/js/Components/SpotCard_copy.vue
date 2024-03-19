<script setup>
import { defineProps, computed, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'


const props = defineProps({
    spots: Object,
});

function reviewCount(index) {
    return props.spots.data[index].reviews.length;
};

function averageRating(index)  {
    if (!props.spots.data[index].reviews.length) return 0;
  
    const totalRating = props.spots.data[index].reviews.reduce((acc, review) => acc + review.rating, 0);
    let avg = totalRating / props.spots.data[index].reviews.length;
  
    // 四捨五入
    avg = Math.round(avg * 10) / 10;
  
    return avg;
};
</script>

<template>
    <div v-if="spots.data[0]">
        <div v-for="(spot, index) in spots.data" :key="spot.id">
            <Link :href="route('spots.show',{spot:spot.id})"> 
                <div class="bg-white w-full my-5 mx-auto py-2 border border-slate-300 rounded-lg shadow-3xl">
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
                        <p class="text-xl font-bold truncate">{{ spot.name }}</p>
                        <p>{{ spot.address }}</p>
                    </div>

                    <div class="flex items-center mx-6 text-sm">
                        <p class="mr-1">{{ averageRating(index) }}</p>
            
                        <template v-for="star in 5" :key="star">
                            <FontAwesomeIcon
                                v-if="star <= Math.floor(averageRating(index))"
                                :icon="['fas', 'star']"
                                style="color: #FFD43B;" />
                            <FontAwesomeIcon
                                v-else-if="star === Math.ceil(averageRating(index)) && averageRating(index) % 1 !== 0"
                                :icon="['fas', 'star-half-stroke']"
                                style="color: #FFD43B;" />
                            <FontAwesomeIcon
                                v-else
                                :icon="['fas', 'star']" 
                                style="color: #a3a3a3;" />
                        </template>
            
                        <p class="ml-1">({{ reviewCount(index) }}件のレビュー)</p>
                    </div>
                </div>
            </Link>   
        </div>           
    </div>

    <div v-else>
        <p class="mt-5 ml-2">該当するスポットがありません</p>
    </div>
</template>