<script setup>
import { ref, watchEffect } from 'vue';

const props = defineProps({
    deleteId: Number,
});

// 新たに削除されたデータがあるかどうか判定
const isDeleted =ref(false);

// 削除処理後のprops.deletedIdの値の変化（新たにデータが削除されたかどうか）を監視。※onMounted等では動かなかった
watchEffect(() => {
    if(props.deleteId) {
        isDeleted.value = false;
        // isDeletedをfalseからtrueにする際、間隔をあけないとテンプレートに変更が反映されない（細かい理屈は不明）ため遅延させる
        setTimeout(() => {
                // 削除処理後のブラウザバック時のフラッシュメッセージ再表示防止策。削除済みidがセッションにある場合はfalse、ない場合は削除後のデータのidを代入しtrueと評価させる。
                isDeleted.value = (sessionStorage.getItem('deleteId') ? false : props.deleteId);
        
                sessionStorage.setItem('deleteId', props.deleteId);        
        }, 100);
    }
});
</script>

<template>
    <div v-if="isDeleted" class="fadeInSlideUp fixed bottom-16 left-0 z-20 w-screen bg-blue-400 text-white p-3">
        削除しました
    </div>
</template>    