<script setup>
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import TextInput from '@/Components/TextInput.vue';
import { defineProps, onMounted, ref, computed, watchEffect } from 'vue';
import Layout from '@/Layouts/Layout.vue';
import SpotCard from '@/Components/SpotCard.vue';
import Paginate from '@/Components/Paginate.vue';
import AsyncPaginate from '@/Components/AsyncPaginate.vue';

const props = defineProps({
    success: String,
    spots: Object,
    allSpots: Object,
    googleMapApiKey: String,
});

const currentPage = ref('list');

// ---------------これより以下ワード検索関連の変数・関数---------------
const searchWord = ref(sessionStorage.getItem('searchWord') || '');

const responseSpots = ref(JSON.parse(sessionStorage.getItem('responseSpots') || '[]'));

const searchFlag = ref(JSON.parse(sessionStorage.getItem('searchFlag') || false));

watchEffect(() => {
    sessionStorage.setItem('searchFlag', searchFlag.value);
    sessionStorage.setItem('searchWord', searchWord.value);
    // 検索結果をJSON 文字列としてセッションに保存（オブジェクトや配列はそのまま保存すると復元できない）
    sessionStorage.setItem('responseSpots', JSON.stringify(responseSpots.value));
});

// コンテンツ投稿後にリダイレクトされた場合は検索結果をクリア
if (props.success) {
    searchWord.value = '';
    responseSpots.value = [];
    searchFlag.value = false;
}

// 検索キーワードからパスを自動生成
const searchURL = computed(() => {
    return `/api/search/?keyword=${searchWord.value}`;
});

const searchSpot = async (url) => {
    try {
        const response = await axios.get(url);
        responseSpots.value = response.data;
        searchFlag.value = true;
        window.scroll({top: 0, behavior: 'auto'}); // 検索後にページトップに瞬時にスクロール
    } catch (error) {
        console.error(error);
    }
};

const clearSearch = () => {
    searchFlag.value = false;
    searchWord.value = '';
    responseSpots.value = [];
};

// ---------------これより以下マップ関連の変数・関数---------------
const apiKey = props.googleMapApiKey;

let map = null; // マップオブジェクト

const tokyo = {lat: 35.681167, lng: 139.767052};

const searchLocationWord =ref('');

let currentOpenInfoWindow = null; //現在開かれているウインドウオブジェクト

// Google Maps APIのスクリプトを非同期で読み込む
const loadGoogleMapsScript = (apiKey) => {
  return new Promise((resolve, reject) => {
    if (window.google?.maps) {
      resolve();
      return;
    }

    const script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}`;
    script.async = true;
    document.head.appendChild(script);
    script.onload = () => resolve();
    script.onerror = reject;
  });
}

onMounted(async () => {
    try {
        await loadGoogleMapsScript(apiKey);
        // マップの初期化
        map = new google.maps.Map(document.getElementById('map'), {
        center: tokyo,
        zoom: 12,
        disableDefaultUI: true, // デフォルトのUIコントロールを無効化
        clickableIcons: false, // アイコンのクリックを無効化
    });
    } catch (error) {
        console.error('Google Maps APIの読み込みに失敗しました。', error);
    };

    props.allSpots.forEach(spot => {
        // 緯度経度を数値にキャスト（しないと型の不一致によるエラーが起きる）
        const lat = parseFloat(spot.latitude);
        const lng = parseFloat(spot.longitude);

        // オプショナルチェーンを用いてspot_imagesプロパティがそのスポットに存在するか確認
        const windowImage = spot.spot_images[0]?.image ?? '/images/no_image_window.png';

        const marker = new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: map,
            icon: {
                url: '/images/marker_icon.png',
                scaledSize: new google.maps.Size(50, 50),}
        });

        const infowindow = new google.maps.InfoWindow({
            content:
           `<div class="max-w-32">
                <img src="${windowImage}" class="w-28 h-20 object-cover rounded-lg border border-slate-300">
                <h3 class="font-bold mt-2 w-full truncate">${spot.name}</h3>
                <a href="/spots/${spot.id}"><span class="underline underline-offset-4">詳細ページへ行く</span></a>
            </div>`,
        });

        marker.addListener('click', () => {
            if (currentOpenInfoWindow === null) {
                infowindow.open(map, marker);
                currentOpenInfoWindow = infowindow;
            } else {
                currentOpenInfoWindow.close();
                infowindow.open(map, marker);
                currentOpenInfoWindow = infowindow;
            }
            // ウインドウ外の領域をクリックした時に情報ウィンドウを閉じる
            google.maps.event.addListener(map, 'click', () => {
                infowindow.close();
                currentOpenInfoWindow = null;
            });
        });
    });
});

const getCurrentLocation = () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            const pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude,
            };
            map.setCenter(pos);
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
    const response = await axios.get(`/api/geocode/?address=${searchLocationWord.value}`);
    const lat = response.data.latitude;
    const lng = response.data.longitude;
    map.setCenter({ lat, lng });
};
</script>

<template>
    <Head title="スポット一覧" />

    <Layout :success="success" v-if="currentPage === 'list'">
        <!-- リスト表示画面 -->
        <div>
            <button @click="currentPage = 'map'" class="flex items-center h-9 bg-white ml-2 pl-1 pr-4 mb-7 border border-slate-300  rounded-lg">
                <img src="/images/pin_icon.png">
                <p>マップで表示</p>
            </button>
            
            <div class="flex h-12">
                <label for="searchSpot" class="ml-4 pt-5">ワード検索</label>

                <button @click="clearSearch" class="h-8 bg-white ml-auto mr-2 mb-7 px-2 border border-slate-300  rounded-lg">
                    <p>検索をクリア</p>
                </button>
            </div>
            
            <div class="flex mb-4">
                <TextInput id="searchSpot" v-model="searchWord" type="text" class="w-full" placeholder="駅・体育館"/>

                <button @click="searchSpot(searchURL)" class="flex justify-center items-center bg-gray-300 w-12 h-12">
                    <img src="/images/search_spot_icon.png">
                </button>
            </div>
                               
            <!-- 通常時（全件表示） -->
            <SpotCard v-if="!searchFlag" :spots="spots.data" class="content" />
            <Paginate v-if="!searchFlag" :links="spots.links" />
            <!-- 検索時 -->
            <SpotCard v-if="searchFlag" :spots="responseSpots.data" class="content" />
            <AsyncPaginate v-if="searchFlag" :links="responseSpots.links" @searchSpot="searchSpot"/>
        </div>            
    </Layout>

    <!-- マップ表示画面 -->
    <div v-show="currentPage === 'map'" class="relative">
        <div class="flex flex-col w-screen absolute z-10">
            <div class="container mx-auto px-5 max-w-lg">
                <div class="flex items-center">
                    <button @click="currentPage = 'list'" class="flex items-center h-9 bg-white pl-1 pr-4 my-7 border border-slate-300  rounded-lg">
                    <img src="/images/list_icon.png" class="px-2">
                    <p>リストで表示</p>
                </button>

                <button @click="getCurrentLocation" class="flex justify-center items-center h-9 bg-white ml-auto px-2 border border-slate-300 rounded-lg z-10">
                    <img src="/images/target_icon.png" class="w-6 h-6 mr-1">
                    <p>現在地</p>
                </button>
                </div>
                

                <div class="relative flex mb-4">
                    <TextInput v-model="searchLocationWord" type="text" class="ml-1 w-full" placeholder="指定の住所・施設に移動"/>

                    <button @click="searchLocation" class="flex justify-center items-center bg-gray-300 w-12 h-12">
                        <img src="/images/search_spot_icon.png">
                    </button>

                    <button @click="searchLocationWord = ''" class="absolute flex justify-center items-center top-3 right-14 bg-gray-300 w-5 h-5 rounded-full">
                        <span class="text-sm">✖</span>
                    </button>
                </div>
            </div>
        </div>

        <div id="map" class="w-screen h-screen"></div>
    </div>
</template>