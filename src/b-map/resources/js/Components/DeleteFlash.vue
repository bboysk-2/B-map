<script setup>
import { ref, watchEffect } from 'vue';

const props = defineProps({
    deleteId: Number,
});

// 新たに削除されたデータがあるかどうか判定
const isDeleted =ref(false);

// 削除処理後のprops.deletedIdの値の変化（新たにデータが削除されたかどうか）を監視。
watchEffect(() => {
    if(props.deleteId) {
        isDeleted.value = false;
        // ブラウザのリフレッシュレートに合わせてisDeletedをfalseからtrueにする。記述しないと連続で削除した場合に二回目以降フラッシュメッセージが表示されない
        requestAnimationFrame(() => {
                // 削除処理後のブラウザバック時のフラッシュメッセージ再表示防止策。削除済みidがセッションにある場合はfalse、ない場合はtrueとしてフラッシュメッセージを表示。
                isDeleted.value = (sessionStorage.getItem('deleteId') ? false : true);
        
                sessionStorage.setItem('deleteId', props.deleteId);        
        });
    }
});
</script>

<template>
    <div v-if="isDeleted" class="fadeInSlideUp fixed bottom-16 left-0 z-20 w-screen bg-blue-400 text-white p-3">
        削除しました
    </div>
</template>    