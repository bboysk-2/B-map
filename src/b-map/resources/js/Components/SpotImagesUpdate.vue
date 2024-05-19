<script setup>
import BlueButton from '@/Components/Button.vue';
import { ref, defineEmits } from 'vue';

const props = defineProps({
    spotImages: Array,
});

const emit = defineEmits([
    'update-spot-images', 'remove-spot-image'
]);

const previewImages = ref([]);

// 既存の画像がある場合、プレビュー画像に追加（edit時）
if (props.spotImages) {
    props.spotImages.forEach(image => {
        previewImages.value.push(image);
    });
}

// プレビュー画像の追加、親コンポーネントへイベントオブジェクトの打ち上げ
const addSpotImages = (event) => {
    const files = Array.from(event.target.files).slice(0, 8 - previewImages.value.length);
    files.forEach(file => {
        const url = URL.createObjectURL(file);
        previewImages.value.push(url);
    });
    emit('update-spot-images', event);
};

const fileInput = ref(null);

const triggerFileInput = () => {
  fileInput.value.click();
};

// プレビュー画像の削除、親コンポーネントへインデックス番号の打ち上げ
const removeSpotImage = (index) => {
    previewImages.value.splice(index, 1);
    emit('remove-spot-image', index);
};
</script>

<template>
    <div class="py-5">
        <div v-if="!previewImages.length" class="flex flex-col items-center justify-center m-auto h-56 w-full bg-gray-200 rounded-lg">
            <img src="/images/camera_icon.png" class="h-24 w-24">
        </div>

        <input type="file" ref="fileInput" class="hidden" name="spot_images" @change="addSpotImages" accept="image/*" multiple>       

        <!-- アップロード画像一枚時のレイアウト -->
        <div v-show="previewImages.length === 1" class="mx-auto justify-center w-full h-56 relative">
            <img :src="previewImages[0]" class="object-cover w-full h-full rounded-lg">

            <button @click="removeSpotImage(0)" class="absolute top-2 right-2 bg-gray-100 h-7 w-7 rounded-full">✖</button>
        </div>

        <!-- アップロード画像2枚以上の場合のレイアウト -->
        <div v-show="previewImages.length >= 2" class="grid grid-cols-2 gap-2">
            <div v-for="(image, index) in previewImages" :key="index" class="mx-auto justify-center w-full h-40 relative">
                <img :src="image" class="object-cover w-full h-full rounded-lg">

                <button @click="removeSpotImage(index)" class="absolute top-2 right-2 bg-gray-100 h-7 w-7 rounded-full">✖</button>
            </div>
        </div>

        <div class="flex justify-center">
            <BlueButton @click="triggerFileInput" class="mt-9">画像を追加</BlueButton>
        </div>
    </div>
</template>