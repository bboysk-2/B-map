<script setup>
import { defineProps, ref, reactive } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
    reviewId: Number,
});

const form = useForm({});

const submitDelete = () => {
    sessionStorage.removeItem('deleteId');
    form.delete(route('reviews.destroy', props.reviewId), {
        preserveScroll: true
    });
};

const confirmingSpotDeletion = ref(false);

const confirmSpotDeletion = (e) => {
    e.preventDefault();
    confirmingSpotDeletion.value = true;
};

const isMenuOpen = reactive({
    [props.reviewId]:false
});

const closeModal = () => {
    confirmingSpotDeletion.value = false;
    isMenuOpen[props.reviewId] = false;
};

const toggleMenu = (e) => {
    e.preventDefault(); // メニューを開いたとき親コンポーネントにイベントが伝播しページ遷移しないようにする
    isMenuOpen[props.reviewId] = !isMenuOpen[props.reviewId];
};
</script>

<template>
    <div v-if="isMenuOpen[reviewId]" @click="toggleMenu($event)" class="fixed top-0 left-0 w-full h-full z-20"></div>

    <button class="h-5 bg-white border-2 rounded-lg flex items-center justify-center ml-auto mr-1 px-1"
    @click="toggleMenu($event)">
        <p class="text-xl">・・・</p>
    </button>

    <div v-if="isMenuOpen[reviewId]" class="zoomIn absolute bg-white border-2 w-32 h-20 bottom-6 right-1 rounded-lg z-30">
        <ul>
            <Link :href="route('reviews.edit', reviewId)">
                <li class="py-2 px-3 border-b border-slate-300">編集</li>
            </Link>
            
            <li @click="confirmSpotDeletion" class="py-2 px-3">削除</li>
        </ul>
    </div>

    <Modal :show="confirmingSpotDeletion" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                レビューを本当に削除しますか？
            </h2>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal" class="mr-3"> キャンセル </SecondaryButton>

                <DangerButton @click="submitDelete" :disabled="form.processing">
                    削除
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>
