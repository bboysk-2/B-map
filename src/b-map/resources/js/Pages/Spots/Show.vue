<script setup>
import { Head } from '@inertiajs/vue3';
import { onMounted, ref, defineProps, computed } from 'vue';
import Layout from '@/Layouts/Layout.vue';
import SlideImages from '@/Components/SlideImages.vue';
import Favorite from '@/Components/Favorite.vue';
import ReviewSection from '@/Components/ReviewSection.vue';
import dayjs from 'dayjs';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'


const props = defineProps({
    errors: Object,
    success: String,
    error: String,
    spot: Object,
    successReviewMessage: String,
    googleMapApiKey: String,
});

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

// -----------------これより以下レビュー関連の変数・関数-----------------

const reviewCount = computed(() => props.spot.reviews.length);

const averageRating = computed(() => {
    if (!props.spot.reviews.length) return 0;
  
    const totalRating = props.spot.reviews.reduce((acc, review) => acc + review.rating, 0);
    let avg = totalRating / props.spot.reviews.length;
  
    // 四捨五入
    avg = Math.round(avg * 10) / 10;
  
    return avg;
});
    
// -----------------これより以下Google Maps関連の変数・関数-----------------

const apiKey = props.googleMapApiKey;

const map = ref(null);

const lat = parseFloat(props.spot.latitude);
const lng = parseFloat(props.spot.longitude);

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

    <Layout :success="success" :error="error">
        <div class="flex items-center">
            <img src="/images/marker_icon.png" class="h-12 w-8 mx-1 pt-4">
            <h1 class="text-xl font-bold pt-4 w-7/12">{{ spot.name }}</h1>

            <div class="ml-auto text-sm min-w-20">
                <p>最終更新日</p>
                <p>{{ dayjs(spot.updated_at).format('YYYY/MM/DD') }}</p>
            </div>
        </div>

        <div class="flex items-center mt-1">
            <p class="ml-3 mr-1">{{ averageRating }}</p>

            <template v-for="star in 5" :key="star">
                <FontAwesomeIcon
                    v-if="star <= Math.floor(averageRating)"
                    :icon="['fas', 'star']"
                    style="color: #FFD43B;" />
                <FontAwesomeIcon
                    v-else-if="star === Math.ceil(averageRating) && averageRating % 1 !== 0"
                    :icon="['fas', 'star-half-stroke']"
                    style="color: #FFD43B;" />
                <FontAwesomeIcon
                    v-else
                    :icon="['fas', 'star']" 
                    style="color: #a3a3a3;" />
            </template>

            <p class="ml-1">({{ reviewCount }}件のレビュー)</p>
        </div>
        
        <div class="border-t-2 border-gray-400 mt-2 mb-4"></div>
        
        <div class="relative">
            <SlideImages :spot="spot" />
            <Favorite v-if="$page.props.auth.user" :spotId="spot.id" :isFavorited="spot.isFavorite" class="absolute bottom-3 left-3" />
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

        <ReviewSection :successReviewMessage="successReviewMessage" :spot="spot" />
    </Layout>
</template>