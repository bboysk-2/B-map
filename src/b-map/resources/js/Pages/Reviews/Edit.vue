<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import BlueButton from '@/Components/Button.vue';
import Layout from '@/Layouts/Layout.vue';

const props = defineProps({
  errors: Object,
  error: String,
  review: Object
});

const rating = ref(props.review.rating);

const comment = ref(props.review.comment);

const submitReview = () => {
  sessionStorage.removeItem('successReviewMessage');
  router.patch(`/reviews/${props.review.id}`, {
    rating: rating.value,
    comment: comment.value,
  }, {
    onSuccess: () => {
      rating.value = 0;
      comment.value = '';
    }
  });
};
</script>

<template>
    <Head title="レビュー編集" />

    <Layout :error="error">
        <div class="flex items-center">
            <img src="/images/marker_icon.png" class="h-12 w-8 mx-1 pt-4">

            <h1 class="text-xl font-bold pt-4">{{ review.spot.name }}</h1>   
        </div>
          
        <div class="border-t-2 border-gray-400 mt-2 mb-4"></div>
  
        <h2 class="text-lg font-bold ml-7 my-6">編集する</h2>
        
        <p class="ml-7">評価</p>

        <!-- デフォルトのフォーム送信動作を無効化してカスタムメソッドを実行させる -->
        <form @submit.prevent="submitReview">
            <div class="ml-6 mt-2">
                <span v-for="n in 5" :key="n" class="star cursor-pointer text-gray-500 text-4xl ml-1" @click="rating = n" :class="{ 'active': n <= rating }">&#9733;</span>
            </div>

            <p v-if="errors.rating" class="text-red-500 ml-6 mt-1">※{{ errors.rating }}</p>
            
            <div class="flex flex-col">
                <textarea v-model="comment" placeholder="レビューコメント（任意）" class="mt-8 min-h-32"></textarea>

                <p v-if="errors.comment" class="text-red-500 mt-2 m-auto">※{{ errors.comment }}</p>

                <BlueButton type="submit" class="w-40 m-auto mt-14">レビューを更新</BlueButton>
                
                <p v-if="errors.spot_id" class="text-red-500 mt-5 m-auto">※{{ errors.spot_id }}</p>
            </div>
        </form>
    </Layout>
</template>

<style scoped>
.star {
  -webkit-text-stroke: 1px black;
}

.active {
  color: yellow;
}
</style>
