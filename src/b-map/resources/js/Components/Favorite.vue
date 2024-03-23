<script setup>
import { defineProps, ref, onMounted } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const props = defineProps({
    spotId: Number,
    isFavorited: Boolean,
});

const isFavorited = ref(props.isFavorited);

// お気に入り多重送信防止用
const isSubmitting = ref(false);

const toggleFavorite = async () => {
    try {
        if (isSubmitting.value) return;

        if (isFavorited.value) {
            isSubmitting.value = true;
            await axios.delete(`/api/favorite/${props.spotId}`);
            isFavorited.value = false;
        } else {
            isSubmitting.value = true;
            await axios.post(`/api/favorite/${props.spotId}`);
            isFavorited.value = true;
        }
    } catch (error) {
        console.error(error);
    } finally {
        isSubmitting.value = false;
    }
};
</script>

<template>
    <div class="flex justify-center items-center  rounded-full bg-white h-7 w-7">
        <button :disabled="isSubmitting" @click="toggleFavorite" class="mt-1">
            <FontAwesomeIcon v-if="isFavorited" :icon="['fas', 'heart']" style="color: #fb6a6a;" />
            <FontAwesomeIcon v-else :icon="['far', 'heart']" />
        </button>
    </div>
</template>