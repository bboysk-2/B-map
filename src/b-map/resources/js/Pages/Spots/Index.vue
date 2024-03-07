<script setup>
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import TextInput from '@/Components/TextInput.vue';
import { defineProps, onMounted, ref } from 'vue';
import Layout from '@/Layouts/Layout.vue';
import SpotCard from '@/Components/SpotCard.vue';

const props = defineProps({
    success: String,
    spots: Object,
});

const currentPage = ref('list');

// ---------------これより以下ワード検索関連の変数・関数---------------
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

// ---------------これより以下マップ関連の変数・関数---------------
const apiKey = import.meta.env.VITE_GOOGLE_MAPS_API_KEY;

const map = ref(null);

const tokyo = {lat: 35.681167, lng: 139.767052};

//現在開かれているウインドウオブジェクト追跡用
let currentOpenInfoWindow = null;

onMounted(async () => {
    try {
        await loadGoogleMapsScript(apiKey);

        map.value = new google.maps.Map(document.getElementById('map'), {
        center: tokyo,
        zoom: 10,
        disableDefaultUI: true,
        clickableIcons: false,
    });
    } catch (error) {
        console.error('Google Maps APIの読み込みに失敗しました。', error);
    };

    props.spots.forEach(spot => {
        // 緯度経度を数値にキャスト（しないと型の不一致によるエラーが起きる）
        const lat = parseFloat(spot.latitude);
        const lng = parseFloat(spot.longitude);

        const windowImage = spot.spot_images[0]?.image ?? '/images/no_image_window.png';

        const marker = new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: map.value,
            icon: {
                url: '/images/marker_icon.png',
                scaledSize: new google.maps.Size(50, 50),}
        });

        const infowindow = new google.maps.InfoWindow({
            content:
           `<div>
                <img src="${windowImage}" class="w-28 h-20 object-cover rounded-lg border border-slate-300">
                <h3 class="font-bold mt-2">${spot.name}</h3>
                <a href="/spots/${spot.id}"><span class="underline underline-offset-4">詳細ページへ行く</span></a>
            </div>`,
        });

        marker.addListener('click', () => {
            if (currentOpenInfoWindow === null) {
                infowindow.open(map.value, marker);
                currentOpenInfoWindow = infowindow;
            } else {
                currentOpenInfoWindow.close();
                infowindow.open(map.value, marker);
                currentOpenInfoWindow = infowindow;
            }
            // ウインドウ外の領域をクリックした時に情報ウィンドウを閉じる
            google.maps.event.addListener(map.value, 'click', () => {
                infowindow.close();
                currentOpenInfoWindow = null;
            });
        });
    });
});

function loadGoogleMapsScript(apiKey) {
  return new Promise((resolve, reject) => {
    if (window.google && window.google.maps) {
      resolve();
      return;
    }

    const script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}`;
    script.async = true;
    document.head.appendChild(script);
    script.onload = () => resolve(window.google.maps);
    script.onerror = reject;
  });
}

function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            const pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude,
            };
            map.value.setCenter(pos);
        },        
        () => {
            alert('位置情報の取得に失敗しました。');
        });
    } else {
        // ブラウザがGeolocationをサポートしていない場合
        alert('お使いのブラウザでは位置情報の取得がサポートされていません。');
    }
}

const searchLocation = async () => {
    const response = await axios.get(`/api/geocode/?address=${searchWord.value}`);
    const lat = response.data.latitude;
    const lng = response.data.longitude;
    map.value.setCenter({ lat, lng });
};
</script>

<template>
    <Head title="スポット一覧" />

    <Layout :success="success" v-if="currentPage === 'list'">
        <!-- リスト表示画面 -->
        
            <div class="flex flex-col">
                <button @click="currentPage = 'map'" class="flex items-center w-32 h-9 bg-white ml-4 mb-7 border border-slate-300  rounded-lg">
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
                                   
                <!-- 検索時 -->
                <SpotCard v-if="searchFlag" :spots="responseSpots" />
                <!-- 検索時以外（全件表示） -->
                <SpotCard v-else :spots="spots" />
            </div>            
            
    </Layout>

    <!-- マップ表示画面 -->
    <div v-show="currentPage === 'map'" class="relative">
            <div class="flex flex-col w-screen absolute z-10">
                <div class="container mx-auto px-5 max-w-lg">
                    <button @click="currentPage = 'list'" class="flex items-center w-32 h-9 bg-white ml-4 my-7 border border-slate-300  rounded-lg">
                        <img src="/images/list_icon.png" class="px-2">
                        <p>リストで表示</p>
                    </button>

                    <div class="flex mb-4">
                        <TextInput v-model="searchWord" type="text" class="ml-1 w-full" placeholder="住所・施設名で検索"/>
                        <button @click="searchLocation" class="flex justify-center items-center bg-gray-300 w-12 h-12">
                            <img src="/images/search_spot_icon.png">
                        </button>
                    </div>
                </div>
            </div>

            <div id="map" class="w-screen h-screen relative"></div>

            <button @click="getCurrentLocation" class="absolute bottom-6 right-5 flex justify-center items-center w-24 h-9 bg-white border border-slate-300 rounded-lg z-10">
                <img src="/images/target_icon.png" class="w-6 h-6">
                <p class="ml-1">現在地</p>
            </button>
        </div>
</template>