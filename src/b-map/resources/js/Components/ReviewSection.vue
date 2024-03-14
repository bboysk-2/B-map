<script setup>
import { useForm} from '@inertiajs/vue3';
import BlueButton from '@/Components/Button.vue';
import { onMounted, defineProps, computed} from 'vue';
import dayjs from 'dayjs';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'


const props = defineProps({
    success: String,
    reviews: Object,
    spot: Object,
});

const form = useForm({
    spot_name: '',
    spot_id: '',
}); 

onMounted(() => {
    if (props.success) {
        window.location.hash = '#reviewElement';
    }
});

const redirectToReviewPage = () => {
    form.spot_name = props.spot.name;
    form.spot_id = props.spot.id;
    form.get(route('reviews.create'));
};

</script>

<template>
    <div id="reviewElement" class="mx-2">
            <div v-if="success" class="bg-blue-400 p-3 w-full">
                <p class="text-white">{{ success }}</p>
            </div>

            <div class="flex mt-2">
                <BlueButton @click="redirectToReviewPage" class="ml-auto">
                    <p>レビューする</p>
                </BlueButton>
            </div>
            <h2 class="font-bold mb-3">直近のレビュー</h2>
        </div>

        <div v-if="reviews.data.length === 0" class="mx-2">
            <p>レビューはまだありません。</p>
        </div>

        <div v-for="review in reviews.data" :key="review.id" class="bg-white border-2  min-h-24 w-hull p-1 mt-1">
            <div class="flex items-center">
                <div class="flex justify-center items-center overflow-hidden rounded-full h-9 w-9 m-1">
                    <img :src="review.user.avatar ?? '/images/no_avatar.png'">
                </div>
                <h3>{{ review.user.name }}</h3>
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
        </div>
</template>