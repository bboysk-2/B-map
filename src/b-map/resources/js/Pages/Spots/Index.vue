<script setup>
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';
import TextInput from '@/Components/TextInput.vue';
import { defineProps } from 'vue';
import Layout from '@/Layouts/Layout.vue';
import SpotCard from '@/Components/SpotCard.vue';
import SearchSpotCard from '@/Components/SearchSpotCard.vue';


const props = defineProps({
    success: String,
    spots: Object,
});

const currentPage = ref('list');

const searchWord = ref('');

const responseSpots = ref([]);

const searchFlag = ref(false);

const searchSpot = async () => {
    try {
        const response = await axios.get('/search', { params: { keyword: searchWord.value } });
        responseSpots.value = response.data;
        searchFlag.value = true;
    } catch (error) {
        console.error(error);
    }
};

const clearSearch = () => {
    searchFlag.value = false;
    searchWord.value = '';
};

</script>

<template>
    <Head title="スポット一覧" />

    <Layout :success="success">
        <div v-if="currentPage === 'list'">
            <div class="flex flex-col">
                <button @click="currentPage = 'map'" class="flex items-center w-32 bg-white ml-4 mb-7 border border-slate-300  rounded-lg">
                    <img src="/images/pin_icon.png">
                    <p>マップで表示</p>
                </button>
                
                <div class="flex h-12">
                    <label for="searchSpot" class="ml-4 pt-5">ワード検索</label>
                    <button @click="clearSearch" class="w-24 h-8 bg-white ml-auto mr-2 mb-7 border border-slate-300  rounded-lg">
                        <p>検索をクリア</p>
                    </button>
                </div>
                
                <div class="flex mb-4">
                    <TextInput id="searchSpot" v-model="searchWord" type="text" class="ml-1 w-full" placeholder="駅・体育館"/>
                    <button @click="searchSpot" class="flex justify-center items-center bg-gray-300 w-12 h-12">
                        <img src="/images/search_spot_icon.png">
                    </button>
                </div>
                    
                <SpotCard v-if="!searchFlag" :spots="spots" />     

                <SearchSpotCard v-if="searchFlag" :responseSpots="responseSpots" />
            </div>            
        </div>
                    
    </Layout>
</template>