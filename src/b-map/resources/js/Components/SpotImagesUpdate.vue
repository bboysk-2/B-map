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

if (props.spotImages) {
    props.spotImages.forEach(image => {
        previewImages.value.push(image);
    });
}

// プレビュー画像の追加、親コンポーネントへイベントオブジェクトの打ち上げ
const addSpotImages = (event) => {
    const files = Array.from(event.target.files).slice(0, 4 - previewImages.value.length);
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
        <div v-if="!previewImages.length" class="flex flex-col items-center justify-center m-auto h-56 w-full bg-gray-200">
            <img src="/images/camera_icon.png" class="h-24 w-24">
            <p class="font-bold mt-3">※上限四枚</p>
        </div>

        <input type="file" ref="fileInput" class="hidden" name="spot_images" @change="addSpotImages" accept="image/*" multiple>            
        <div v-for="(image, index) in previewImages" :key="index" class="mx-auto mb-4 justify-center w-full h-56 relative">
            <img :src="image" class="object-cover w-full h-full">
            <button @click="removeSpotImage(index)" class="absolute top-0 right-0 bg-gray-100 p-1">✖</button>
        </div>

        <div class="flex justify-center">
            <BlueButton @click="triggerFileInput" class="mt-9">画像を追加</BlueButton>
        </div>
    </div>
</template>