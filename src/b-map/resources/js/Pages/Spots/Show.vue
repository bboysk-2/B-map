<script setup>
import { Head } from '@inertiajs/vue3';
import BlueButton from '@/Components/Button.vue';
import { onMounted, ref, defineProps, computed } from 'vue';
import Layout from '@/Layouts/Layout.vue';
import SlideImages from '@/Components/SlideImages.vue';
import dayjs from 'dayjs';


const props = defineProps({
    errors: Object,
    error: String,
    spot: Object,
});

const apiKey = import.meta.env.VITE_GOOGLE_MAPS_API_KEY;

const map = ref(null);

const lat = parseFloat(props.spot.latitude);
const lng = parseFloat(props.spot.longitude);
    
// スポットの詳細情報が全て空かどうかを判定するcomputedプロパティ
const isSpotDetailsEmpty = computed(() => {
    const details = [
        props.spot.category,
        props.spot.space,
        props.spot.floor_material,
        props.spot.slipping,
        props.spot.usage_fee,
        props.spot.available_times,
        props.spot.volume,
        props.spot.reservation,
        props.spot.remarks, 
    ];

    // details配列の要素が全て空またはnullであるかチェック
    return details.every(detail => !detail);
});

onMounted(async () => {
    // 緯度経度が取得できない場合早期リターン
    if (!lat || !lng) {
        return;
    }

    try {
        await loadGoogleMapsScript(apiKey);

        map.value = new google.maps.Map(document.getElementById('map'), {
        center: { lat: lat, lng: lng },
        zoom: 15,
        disableDefaultUI: true,
        clickableIcons: false,
    });
    } catch (error) {
        console.error('Google Maps APIの読み込みに失敗しました。', error);
    };

    const marker = new google.maps.Marker({
        position: { lat: lat, lng: lng },
        map: map.value,
        icon: {
            url: '/images/marker_icon.png',
            scaledSize: new google.maps.Size(50, 50),}
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

</script>

<template>
    <Head title="スポット詳細" />

    <Layout>
        <div class="flex items-center">
            <img src="/images/marker_icon.png" class="h-12 w-8 mx-1 pt-4">
            <h1 class="text-xl font-bold pt-4">{{ spot.name }}</h1>
            <div class="ml-auto text-sm">
                <p>最終更新日</p>
                <p>{{ dayjs(spot.updated_at).format('YYYY/MM/DD') }}</p>
            </div>
        </div>
        <h3 class="ml-10">レビュー<span class="text-yellow-400 ml-1">★★★★★</span></h3>

        <div class="border-t-2 border-gray-400 mt-2 mb-4"></div>
        
        <div class="relative">
            <SlideImages :spot="spot" />
            <div class="flex justify-center items-center absolute rounded-full bg-white h-7 w-7 bottom-3 left-3">
                <span class="text-red-400 text-lg">♥</span>
            </div>
        </div>
        
        <div class="border-t-2 border-gray-400  my-4"></div>

        <div class="mx-2">
            <h2 class="font-bold mb-3">スポット詳細</h2>
            <div class="flex items-center ml-2">
                <p v-if="isSpotDetailsEmpty">なし</p>
                <div>
                    <p v-if="spot.category">スポットカテゴリー</p>
                    <p v-if="spot.space">広さ（人数）</p>
                    <p v-if="spot.floor_material">床の素材</p>
                    <p v-if="spot.slipping">滑り具合</p>
                    <p v-if="spot.usage_fee">料金</p>
                    <p v-if="spot.available_times">利用可能な時間帯</p>
                    <p v-if="spot.volume">音出し</p>
                    <p v-if="spot.reservation">予約の有無</p>
                </div>
                <div class="ml-2">
                    <p v-if="spot.category">: {{ spot.category }}</p>
                    <p v-if="spot.space">: {{ spot.space }}</p>
                    <p v-if="spot.floor_material">: {{ spot.floor_material }}</p>
                    <p v-if="spot.slipping">: {{ spot.slipping }}</p>
                    <p v-if="spot.usage_fee">: {{ spot.usage_fee }}</p>
                    <p v-if="spot.available_times">: {{ spot.available_times }}</p>
                    <p v-if="spot.volume">: {{ spot.volume }}</p>
                    <p v-if="spot.reservation">: {{ spot.reservation }}</p>
                </div>
            </div>
            <div v-if="spot.remarks" class="mt-4">
                <p class="ml-2">備考</p>
                <div class="bg-white border-2  min-h-24 w-hull p-1 mt-1">
                    <p>{{ spot.remarks }}</p>
                </div>
            </div>
        </div>

        <div class="border-t-2 border-gray-400 my-4"></div>

        <div class="ml-2 mb-4">
            <h2 class="font-bold">住所</h2>
            <p>{{ spot.address }}</p>
        </div>
        <div v-if="spot.latitude && spot.longitude" class="h-64 w-hull">
            <div id="map" class="h-full w-full"></div>
        </div>
        <div v-else class="h-64 w-full">
            <img src="/images/no_map.png" class="object-cover h-full w-hull">
        </div>

        <div class="border-t-2 border-gray-400 my-4"></div>

        <div class="mx-2">
            <div class="flex mt-2">
                <BlueButton class="ml-auto">
                    <p>レビューする</p>
                </BlueButton>
            </div>
            <h2 class="font-bold mb-3">直近のレビュー</h2>
        </div>
        <div class="bg-white border-2  min-h-24 w-hull p-1 mt-1">
            <div class="flex items-center">
                <div class="flex justify-center items-center overflow-hidden rounded-full h-9 w-9 m-1">
                    <img src="/images/no_avatar.png">
                </div>
                <h3>ここにユーザーネーム</h3>
            </div>
            <p class="text-yellow-400 ml-2">★★★★★</p>
            <p class="mx-2">ここにレビュー内容</p>
        </div>
    </Layout>
</template>