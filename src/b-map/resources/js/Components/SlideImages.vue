<script setup>
import { ref, computed, reactive } from 'vue';
import OverViewModal from '@/Components/OverViewModal.vue';

const props = defineProps({
    spot: Object
});

const modal = ref(false);

const currentIndex = ref(1);

const slideStyle = reactive({
    transform: 'translateX(0px)',
});

const defaultImage = '/images/no_image_show.png';

const spotImages = computed(() => props.spot.spot_images.length ? props.spot.spot_images : [{ image: defaultImage }]);

const currentImage = computed(() => spotImages.value[currentIndex.value - 1].image);

const hasNext = computed(() => currentIndex.value < spotImages.value.length);

const hasPrevious = computed(() => currentIndex.value > 1);

const nextImage = () => {
    if (hasNext.value) {
        currentIndex.value++;
        const slideWidth = document.querySelector('.slide-wrapper');
        slideStyle.transform = `translateX(-${slideWidth.offsetWidth * (currentIndex.value - 1)}px)`;
    }
}

const previousImage = () => {
    if (hasPrevious.value) {
        currentIndex.value--;
        const slideWidth = document.querySelector('.slide-wrapper');
        slideStyle.transform = `translateX(-${slideWidth.offsetWidth * (currentIndex.value - 1)}px)`;
    }
}

const handleError = (event) => {
    // 画像のロードに失敗した場合、デフォルト画像を表示
    event.target.src = defaultImage;
}

const showOverView = () => {
    modal.value = true;
};

const closeModal = () => {
    modal.value = false;
};
</script>

<template>
    <div class="slide-wrapper relative w-full h-56">
        <template class="flex h-full w-full overflow-hidden">
            <div v-for="spotimage in spotImages" :style="slideStyle" class="h-full w-full flex-shrink-0 duration-500">
                <img :src="spotimage.image" @click="showOverView" class="object-cover w-full  h-full rounded-lg" @error="handleError">
            </div>
        </template>
        
        <div v-if="hasPrevious" @click="previousImage" class="flex justify-center items-center absolute top-24 left-1 w-9 h-9 cursor-pointer rounded-full bg-black opacity-70">
            <img src="/images/left_arrow_button.png" class="w-5 h-5">
        </div>

        <div v-if="hasNext" @click="nextImage" class="flex justify-center items-center absolute top-24 right-1 w-9 h-9 cursor-pointer rounded-full bg-black opacity-70">
            <img src="/images/right_arrow_button.png" class="w-5 h-5">
        </div>

        <div class="absolute top-4 right-4 text-white rounded-md bg-black opacity-70">{{ currentIndex }}/{{ spotImages.length }}</div>
    </div>

    <OverViewModal :show="modal" @close="closeModal">
        <div class="flex justify-center items-center w-full h-full">
            <img :src="currentImage" class="object-cover w-full h-full" @error="handleError">
        </div>
    </OverViewModal>
</template>
