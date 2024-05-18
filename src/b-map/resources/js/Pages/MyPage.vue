<script setup>
import { Head, Link } from '@inertiajs/vue3';
import BlueButton from '@/Components/Button.vue';
import SpotCard from '@/Components/SpotCard.vue';
import DeleteFlash from '@/Components/DeleteFlash.vue';
import ReviewCard from '@/Components/ReviewCard.vue';
import Layout from '@/Layouts/Layout.vue';
import { ref, reactive, onMounted, watchEffect } from 'vue';

const props = defineProps({
    avatarPath: String,
    posts: Object,
    favorites: Object,
    reviews: Object,
    error: String,
    deleteId: Number,
});

const currentTab = ref(sessionStorage.getItem('currentTab') || 'posts');

const selectedTabIndex = ref(sessionStorage.getItem('selectedTabIndex') || 0);

watchEffect(() => {
    sessionStorage.setItem('currentTab', currentTab.value);
    sessionStorage.setItem('selectedTabIndex', selectedTabIndex.value);
});

const underlineStyle = reactive({
    width: '0px',
    left: '0px',
    'z-index': '10',
});

onMounted(() => {
    const tabElements = document.querySelectorAll('.tab');
    updateUnderline(tabElements[selectedTabIndex.value]);
});

const selectTab = (index) => {
    selectedTabIndex.value = index;
    const tabElements = document.querySelectorAll('.tab');
    updateUnderline(tabElements[index]);
}

const updateUnderline = (tabElement) => {
    underlineStyle.width = `${tabElement.offsetWidth}px`;
    underlineStyle.left = `${tabElement.offsetLeft}px`;
}
</script>

<template>
    <Head title="マイページ" />

    <Layout :error="error">
        <DeleteFlash :deleteId="deleteId" />

        <div class="flex"> 
            <div class="w-20 h-20 rounded-full overflow-hidden flex items-center justify-center ml-4">
                <img :src="avatarPath" class="object-cover w-full h-full">
            </div>
            
            <Link :href="route('profile.edit')">
                <div class="ml-8">
                    <p class="text-xl font-bold">{{ $page.props.auth.user.name }}さん</p>

                    <BlueButton class="mt-2 py-1">編集</BlueButton>
                </div>  
            </Link>   
        </div> 
        
        <div class="mt-10">
            <div class="relative flex justify-center">
                <button @click="currentTab = 'posts', selectTab(0)" class="tab"><img src="/images/paper_and_pen_icon.png" class="mx-auto w-6 h-6"></button>

                <div class="underline" :style="underlineStyle"></div>
                
                <button @click="currentTab = 'favorites', selectTab(1)" class="tab"><img src="/images/heart_icon.png" class="mx-auto w-6 h-6"></button>

                <div class="underline" :style="underlineStyle"></div>
                
                <button @click="currentTab = 'reviews', selectTab(2)" class="tab"><img src="/images/reviews_icon.png" class="mx-auto w-9 h-10"></button>

                <div class="underline" :style="underlineStyle"></div>
  
                <div class="absolute bottom-0 w-full border-t-2 border-gray-400"></div>
            </div>

            <div class="mt-3">
                <div v-show="currentTab === 'posts'" class="content">
                    <p class="ml-4">投稿:{{ posts.length }}件</p>

                    <SpotCard :spots="posts" :canEdit="true"/>

                    <p v-if="!posts">投稿はありません</p>  
                </div>

                <div v-show="currentTab === 'favorites'" class="content">
                    <p v-if="favorites.length" class="ml-4">お気に入り:{{ favorites.length }}件</p>

                    <p v-else>お気に入りはありません</p>

                    <SpotCard :spots="favorites" />   
                </div>
  
                <div v-show="currentTab === 'reviews'" class="content">
                    <p v-if="reviews.length" class="ml-4">レビュー:{{ reviews.length }}件</p>

                    <p v-else>レビューはありません</p>

                    <ReviewCard :reviews="reviews" /> 
                </div>
            </div>
        </div>
    </Layout>
</template>

<style scoped>
.tab {
    width: 33.3%;
}

.underline {
    position: absolute;
    bottom: 0;
    height: 2px;
    background-color: blue;
    transition: all 0.5s ease;
}
</style>