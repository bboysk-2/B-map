<script setup>
import { defineProps, ref, reactive } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
    spotId: Number,
});

const form = useForm({});

const submitDelete = () => {
    sessionStorage.removeItem('deleteId');
    form.delete(route('spots.destroy', props.spotId), {
        preserveScroll: true
    });
};

const confirmingSpotDeletion = ref(false);

const confirmSpotDeletion = (e) => {
    e.preventDefault();
    confirmingSpotDeletion.value = true;
};

const isMenuOpen = reactive({
    [props.spotId]:false
});

const closeModal = () => {
    confirmingSpotDeletion.value = false;
    isMenuOpen[props.spotId] = false;
};

const toggleMenu = (e, spotId) => {
    e.preventDefault();
    isMenuOpen[spotId] = !isMenuOpen[spotId];
};


</script>

<template>
    <div v-if="isMenuOpen[spotId]" @click="toggleMenu($event,spotId)" class="fixed top-0 left-0 w-full h-full z-20"></div>

    <div class="absolute top-3 right-3 w-12 h-5 bg-white rounded-lg flex items-center justify-center"
    @click="toggleMenu($event,spotId)">
        <p class="text-xl text-center ">・・・</p>
    </div>

    <div v-if="isMenuOpen[spotId]" class="zoomIn absolute bg-white w-32 h-20 top-9 right-3 rounded-lg z-30">
        <ul>
            <Link :href="route('spots.edit',{spot:spotId})">
                <li class="py-2 px-3 border-b border-slate-300">編集</li>
            </Link>
            
            <li @click="confirmSpotDeletion" class="py-2 px-3">削除</li>
        </ul>
    </div>

    <Modal :show="confirmingSpotDeletion" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                スポットを本当に削除しますか？
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
