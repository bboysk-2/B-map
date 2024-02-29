<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import FooterMenu from '@/Components/FooterMenu.vue';
import BlueButton from '@/Components/Button.vue';
import Layout from '@/Layouts/Layout.vue';
import { ref } from 'vue';

defineProps({
    avatarPath: String,
});

const currentTab = ref('favorites');

</script>

<template>
    <Head title="マイページ" />

    <Layout>
        <div class="flex"> 
          <div class="w-20 h-20 rounded-full overflow-hidden flex items-center justify-center ml-4">
            <img :src="avatarPath" class="object-cover w-full h-full">
          </div>
          <Link :href="route('profile.edit')">
              <div class="ml-8">
                  <p class="text-lg">{{ $page.props.auth.user.name }}さん</p>
                  <BlueButton class="mt-2 py-1">編集</BlueButton>
              </div>  
          </Link>   
        </div> 
        
        <div class="mt-10 mx-5">
            <div class="tab-titles flex justify-center">
              <button :class="{ active: currentTab === 'favorites' }" @click="currentTab = 'favorites'">お気に入り</button>
              <button :class="{ active: currentTab === 'posts' }" @click="currentTab = 'posts'">投稿</button>
            </div>
            <div class="mt-7">
              <div v-if="currentTab === 'favorites'">
                <p>お気に入りはありません</p>
              </div>
              <div v-if="currentTab === 'posts'">
                <p>投稿はありません</p>
              </div>
            </div>
        </div>
    </Layout>
</template>

<style>
    .tab-titles button {
      padding: 10px 10px;
      margin: 0 10px;
      cursor: pointer;
      position: relative;
      width: 50%;
    }
    
    .tab-titles ::after {
      content: '';
      position: absolute;
      left: 50%;
      bottom: -3px; 
      width: 120px; 
      height: 2px; 
      background-color: darkgray;
      transform: translateX(-50%); 
    }
    .tab-titles .active::after {
      content: '';
      position: absolute;
      left: 50%;
      bottom: -3px; 
      width: 120px; 
      height: 2px; 
      background-color: deepskyblue;
      transform: translateX(-50%); 
    }
</style>