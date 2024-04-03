<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import BlueButton from '@/Components/Button.vue';
import { defineProps } from 'vue';
import axios from 'axios';
import SpotImageUpdate from '@/Components/SpotImagesUpdate.vue';
import TextInput from '@/Components/TextInput.vue';
import Layout from '@/Layouts/Layout.vue';

const props = defineProps({
    errors: Object,
    error: String,
    spot: Object,
});


const form = useForm({
    // patch,putメソッドでファイルの送信ができないため、methodフィールドを疑似的にpatchにする
    _method: 'patch',
    spot_images: [],
    deleted_images: [],
    spot_name: props.spot.name,
    address: props.spot.address,
    latitude: props.spot.latitude,
    longitude: props.spot.longitude,
    category: props.spot.category,
    space: props.spot.space,
    floor_material: props.spot.floor_material,
    slipping: props.spot.slipping,
    usage_fee: props.spot.usage_fee,
    available_times: props.spot.available_times,
    volume: props.spot.volume,
    reservation: props.spot.reservation,
    remarks: props.spot.remarks,
});

// 子コンポーネントに渡してプレビュー表示する用
const currentImages = [];

props.spot.spot_images.forEach(spot => {
    currentImages.push(spot.image);
    form.spot_images.push(spot);
});


const addFormImages = (event) => {
    const files = Array.from(event.target.files).slice(0, 4 - form.spot_images.length);
    files.forEach(file => {
        form.spot_images.push(file);
    });
};

const removeFormImage = (index) => {
    // 画像が既存のものか新規追加のものかを判別し、削除する画像をdeleted_imagesに追加
    if (form.spot_images[index].id) {
        form.deleted_images.push(form.spot_images[index]);
    }

    form.spot_images.splice(index, 1);
};

const getGeocode = async (address) => {
  try {
    const response = await axios.get(`/api/geocode/?address=${address}`);
    return response.data; 
  } catch (error) {
    console.error('ジオコーディングAPIエラー:', error);
  }
};

// ジオコーディングを実行し、フォーム送信
const handleSubmit = async () => {
  try {
    const geocodeData = await getGeocode(form.address);
    form.latitude = geocodeData.latitude;
    form.longitude = geocodeData.longitude;
  } catch {
    form.address = ''; // エラー時、サーバー側のバリデーションに引っかけるため空にする
  }
//　patch,putメソッドでファイルの送信ができないため、便宜上postメソッドを使用（上でmethodフィールドをpatchにしているためpatchとして扱われる）
  form.post(route('spots.update', {spot:props.spot.id}));
};
</script>

<template>
    <Head title="スポット編集" />

    <Layout :error="error">
        <h1 class="text-xl font-extrabold pl-4">スポット編集</h1>
        <div class="my-10">
            <label for="spot_name">スポット名<span class="text-red-500">（必須）</span></label>
            <TextInput
                id="spot_name"
                type="text"
                class="my-1 block w-full"
                v-model="form.spot_name"
                required
                autofocus
                autocomplete="spot_name"
            />
            <span v-if="errors.spot_name" class="text-red-500">※{{ errors.spot_name }}</span>
        </div>
        <div class="my-10">
            <label for="address">住所<span class="text-red-500">（必須）</span></label>
            <TextInput
                id="address"
                type="text"
                class="my-1 block w-full"
                v-model="form.address"
                required
                autocomplete="address"
            />
            <span v-if="errors.address" class="text-red-500">※{{ errors.address }}</span>
        </div>

        <div class="border-t-2 border-gray-400"></div>

        <h3 class="text-lg font-semibold my-5">詳細</h3>
        
        <div class="my-5">
            <label for="category">スポットカテゴリー</label>
            <select id="category" class="block w-3/4" v-model="form.category">
                <option value="" disabled selected>選択してください</option>
                <option value="駅・通路">駅・通路</option>
                <option value="公園">公園</option>
                <option value="スタジオ">スタジオ</option>
                <option value="公民館">公民館</option>
                <option value="体育館">体育館</option>
                <option value="学校">学校</option>
                <option value="その他">その他</option>
            </select>
        </div>
        <div class="my-5">
            <label for="space">広さ（人数）</label>
            <select id="space" class="block w-3/4" v-model="form.space">
                <option value="" disabled selected>選択してください</option>
                <option value="1人">1人</option>
                <option value="2人">2人</option>
                <option value="3~5人">3~5人</option>
                <option value="6~10人">6~10人</option>
                <option value="11~15人">11~15人</option>
                <option value="16~20人">16~20人</option>
                <option value="21~30人">21~30人</option>
                <option value="31~50人">31~50人</option>
                <option value="50~人">50~人</option>
            </select>
        </div>
        <div class="my-5">
            <label for="floor_material">床の素材</label>
            <select id="floor_material" class="block w-3/4" v-model="form.floor_material">
                <option value="" disabled selected>選択してください</option>
                <option value="フローリング（木）">フローリング（木）</option>
                <option value="リノリウム">リノリウム</option>
                <option value="コンクリート">コンクリート</option>
                <option value="その他">その他</option>
            </select>
        </div>
        <div class="my-5">
            <label>滑り具合</label>
            <div class="flex items-center">
                <input type="radio" name="slipping" value="悪い" v-model="form.slipping">
                <label class="ml-1 mr-2">悪い</label>
                <input type="radio" name="slipping" value="普通" v-model="form.slipping">
                <label class="ml-1 mr-2">普通</label>
                <input type="radio" name="slipping" value="良い" v-model="form.slipping">
                <label class="ml-1 mr-2">良い</label>
            </div>
        </div>  
        <div class="my-5">
            <label>料金</label>
            <div class="flex items-center">
                <input type="radio" name="usage_fee" value="無料" v-model="form.usage_fee">
                <label class="ml-1 mr-2">無料</label>
                <input type="radio" name="usage_fee" value="有料" v-model="form.usage_fee">
                <label class="ml-1 mr-2">有料</label>                               
            </div>
        </div>
        <div class="my-5">
            <label>利用可能な時間帯</label>
            <div class="flex items-center">
                <input type="radio" name="available_times" value="日中" v-model="form.available_times">
                <label class="ml-1 mr-2">日中</label>
                <input type="radio" name="available_times" value="夜間" v-model="form.available_times">
                <label class="ml-1 mr-2">夜間</label>                               
                <input type="radio" name="available_times" value="終日" v-model="form.available_times">
                <label class="ml-1 mr-2">終日</label>                               
                <input type="radio" name="available_times" value="不定" v-model="form.available_times">
                <label class="ml-1 mr-2">不定</label>                               
            </div>
        </div>
        <div class="my-5">
            <label>音出し</label>
            <div class="flex items-center">
                <input type="radio" name="volume" value="禁止" v-model="form.volume">
                <label class="ml-1 mr-2">禁止</label>
                <input type="radio" name="volume" value="可" v-model="form.volume">
                <label class="ml-1 mr-2">可</label>                                                
            </div>
        </div>
        <div class="my-5">
            <label>予約の有無</label>
            <div class="flex items-center">
                <input type="radio" name="reservation" value="なし" v-model="form.reservation">
                <label class="ml-1 mr-2">なし</label>
                <input type="radio" name="reservation" value="あり" v-model="form.reservation">
                <label class="ml-1 mr-2">あり</label>                                                
                <input type="radio" name="reservation" value="一部あり" v-model="form.reservation">
                <label class="ml-1 mr-2">一部あり</label>                                                
            </div>
        </div>
        <div class="my-5">
            <label for="remarks">備考</label>
            <textarea id="remarks" class="block w-full h-32 mt-1" v-model="form.remarks"></textarea>
        </div>
        <div class="border-t-2 border-gray-400 mt-7"></div>
        <h3 class="text-lg font-semibold mt-5">スポット画像</h3>
        <SpotImageUpdate :spotImages="currentImages" @update-spot-images="addFormImages" @remove-spot-image="removeFormImage" class="mb-4"/>
            
        <div class="border-t-2 border-gray-400"></div>
            
        <div class="flex justify-center">
            <BlueButton @click="handleSubmit" :disabled="form.processing" class="mt-9">上記の内容で更新</BlueButton>
        </div>         
    </Layout>                
    
</template>
