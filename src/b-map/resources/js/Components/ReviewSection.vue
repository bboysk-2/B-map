<script setup>
import { useForm } from '@inertiajs/vue3';
import BlueButton from '@/Components/Button.vue';
import { onMounted, defineProps, computed, ref} from 'vue';
import dayjs from 'dayjs';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'


const props = defineProps({
    success: String,
    spot: Object,
});

const currentReviews = ref({});

const currentPage = ref(1);

const totalPages = computed(() => {
    return Math.ceil(props.spot.reviews.length / 5);
});

onMounted(() => {
    if (props.success) {
        window.location.hash = '#reviewElement';
    }

    getReviews();
});

const getReviews = () => {
    currentReviews.value = props.spot.reviews.slice((currentPage.value - 1) * 5, currentPage.value * 5);
}

const changePage = (page) => {
    currentPage.value = page;
    getReviews();
}

const form = useForm({
    spot_name: '',
    spot_id: '',
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

        <div v-if="spot.reviews.length === 0" class="mx-2">
            <p>レビューはまだありません。</p>
        </div>

        <div v-for="review in currentReviews" :key="review.id" class="bg-white border-2  min-h-24 w-hull p-1 mt-1">
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

        
        <div v-if="totalPages > 1" class="mt-10 ml-3">
            <div class="flex flex-wrap -mb-1">                   
                <button v-if="currentPage !== 1" class="mr-1 mb-1 px-3 py-2 text-sm leading-4 border border-slate-400 rounded hover:bg-indigo-500"
                @click="changePage(currentPage - 1)">
                &laquo; 前
                </button>

                <p v-if="currentPage - 1 > 1" class="mb-1 mr-1 pt-3 font-bold ">. . .</p>
         
                <template v-for="page in totalPages">
                    <button v-if="page === currentPage || page === currentPage + 1 || page === currentPage - 1" class="mr-1 mb-1 px-3 py-2 text-sm leading-4 border border-slate-400 rounded hover:bg-indigo-500"
                    :class="{ 'bg-white': page === currentPage }"
                    @click="changePage(page)">
                    {{ page }}
                    </button>
                </template>

                <p v-if="currentPage + 1 < totalPages" class="mb-1 pt-3 font-bold ">. . .</p>

                <button v-if="currentPage < totalPages" class="ml-1 mb-1 px-3 py-2 text-sm leading-4 border border-slate-400 rounded hover:bg-indigo-500"
                @click="changePage(currentPage + 1)">
                次 &raquo;
                </button>
            </div>
        </div>

        
</template> 