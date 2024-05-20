<script setup>
import { defineProps, computed, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import Favorite from '@/Components/Favorite.vue';
import SpotCardMenu from '@/Components/SpotCardMenu.vue';


const props = defineProps({
    spots: Object,
    canEdit: Boolean,
});

const reviewCount = (index) => {
    return props.spots[index].reviews.length;
};

const averageRating = (index) => {
    if (!props.spots[index].reviews.length) return 0;
  
    const totalRating = props.spots[index].reviews.reduce((acc, review) => acc + review.rating, 0);
    let avg = totalRating / props.spots[index].reviews.length;
  
    // 四捨五入
    avg = Math.round(avg * 10) / 10;
  
    return avg;
};
</script>

<template>
    <div v-if="props.spots[0]">
        <div v-for="(spot, index) in spots" :key="spot.id">
            <Link :href="route('spots.show',{spot:spot.id})"> 
                <div class="bg-white w-full mt-3 mb-5 mx-auto py-2 border border-slate-300 rounded-lg shadow-3xl">
                    <div class="w-full flex items-center justify-center my-2 px-5">
                        <div class="relative w-full h-48 flex flex-col justify-center items-center rounded-lg">
                            <img :src="spot.spot_images[0]?.image ?? '/images/no_image_show.png'" class="object-cover w-full h-full rounded-lg">

                            <Favorite v-if="$page.props.auth.user" :spotId="spot.id" :isFavorited="spot.isFavorite" class="absolute bottom-3 right-3"/>

                            <SpotCardMenu v-if="canEdit" :spotId="spot.id" /> 
                        </div>
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
</template>