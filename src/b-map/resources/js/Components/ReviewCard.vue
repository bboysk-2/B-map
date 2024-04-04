<script setup>
import { defineProps, ref, onMounted } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { Link } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import ReviewCardMenu from '@/Components/ReviewCardMenu.vue';

const props = defineProps({
    reviews: Object,
});
</script>

<template>
    <div v-for="review in reviews" :key="review.id" class="relative bg-white border-2  min-h-20 w-hull p-1 mt-3 mb-5 rounded-lg shadow-3xl"> 
        <Link :href="route('spots.show', review.spot.id)">
            <div class="flex items-center">
                <h3 class="ml-2 font-bold">{{ review.spot.name }}</h3>
                <p class="ml-auto mr-1 text-sm">{{ dayjs(review.updated_at).format('YYYY/MM/DD') }}</p>
            </div>
        
            <span class="ml-2 mr-1">{{ review.rating }}</span>
            
            <template v-for="star in 5" :key="star">
                <FontAwesomeIcon
                    v-if="star <= review.rating"
                    :icon="['fas', 'star']"
                    style="color: #FFD43B;" />
                <FontAwesomeIcon
                    v-else
                    :icon="['fas', 'star']" 
                    style="color: #a3a3a3;" />
            </template>
            <p class="px-2 w-full">{{ review.comment }}</p>   

            <ReviewCardMenu :reviewId="review.id" />
        </Link>
    </div>
</template>