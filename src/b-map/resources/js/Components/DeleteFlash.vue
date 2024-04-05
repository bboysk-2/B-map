<script setup>
import { ref, watchEffect } from 'vue';

const props = defineProps({
    deleteId: Number,
});

// 新たに削除されたデータがあるかどうか判定
const isDeleted =ref(false);

// watchEffectを使用して削除処理後のprops.deletedIdの値の変化を監視。※onMounted等では動かなかった
watchEffect(() => {
    // セッションから取り出した値は文字列なのでNumberで数値に変換して比較し、新たに削除処理が行われた場合にセッションに保存された値を削除
    if (Number(sessionStorage.getItem('deleteId')) !== props.deleteId ) {
        sessionStorage.removeItem('deleteId');
        isDeleted.value = false;
    }
    // watchEffectはDOMが生成される前に実行されてしまうため遅延させる
    setTimeout(() => {
        // 削除済みidがセッションにある場合はfalse、ない場合は削除後のデータのidを代入しtrueとする。※削除処理後のブラウザバック時のフラッシュメッセージ再表示を防止
        isDeleted.value = (sessionStorage.getItem('deleteId') ? false : props.deleteId);

        if (props.deleteId) {
        sessionStorage.setItem('deleteId', props.deleteId);
        }
    }, 100);
});
</script>

<template>
    <div v-if="isDeleted" class="fadeInSlideUp fixed bottom-16 left-0 z-20 w-screen bg-blue-400 text-white p-3">
        削除しました
    </div>
</template>    