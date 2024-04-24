<script setup>
import { defineProps, ref, onMounted, watchEffect } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const props = defineProps({
    spotId: Number,
    isFavorited: Boolean,
});

// ページリロード時にお気に入り状態を保持する。セッションに保存されるデータは文字列になってしまうため、Boolean型に変換
const sessionStorageValue = ref(sessionStorage.getItem(props.spotId) === "true" ? true : false);

const isFavorite = ref(sessionStorage.getItem(props.spotId) === null ? props.isFavorited : sessionStorageValue.value);

// お気に入り多重送信防止用
const isSubmitting = ref(false);

const toggleFavorite = async (event) => {
    event.preventDefault();
    event.stopPropagation();
    try {
        if (isSubmitting.value) return;

        if (isFavorite.value) {
            isSubmitting.value = true;
            await axios.delete(`/api/favorite/${props.spotId}`);
            isFavorite.value = false;
        } else {
            isSubmitting.value = true;
            await axios.post(`/api/favorite/${props.spotId}`);
            isFavorite.value = true;
        }
    } catch (error) {
        console.error(error);
    } finally {
        sessionStorage.setItem(props.spotId, isFavorite.value.toString());
        isSubmitting.value = false;
    }
};
</script>

<template>
    <div class="prevent-link flex justify-center items-center  rounded-full bg-white h-7 w-7">
        <button :disabled="isSubmitting" @click.stop="toggleFavorite" class="mt-1">
            <FontAwesomeIcon v-if="isFavorite" :icon="['fas', 'heart']" style="color: #fb6a6a;" />
            <FontAwesomeIcon v-else :icon="['far', 'heart']" />
        </button>
    </div>
</template>