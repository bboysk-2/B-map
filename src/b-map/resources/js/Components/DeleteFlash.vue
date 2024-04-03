<script setup>
import { ref, watchEffect } from 'vue';

const props = defineProps({
    deleteId: Number,
});

// フラッシュメッセージ表示用
const isFlash = ref(false);
// 削除済みかどうか判定
const isDeleted =ref(false);

// onMountedだと削除処理後のページレンダリング時に実行されなかったためwatchEffectを使用
watchEffect(() => {
    // セッションから取り出した値は文字列なのでNumberで数値に変換して比較し、新たに削除処理が行われた場合にセッションに保存された値を削除
    if (Number(sessionStorage.getItem('deleteId')) !== props.deleteId ) {
        sessionStorage.removeItem('deleteId');
        isFlash.value = false;
    }
    // 削除済みidがセッションにある場合はfalse、ない場合はprops.deleteIdを代入
    isDeleted.value = (sessionStorage.getItem('deleteId') ? false : props.deleteId);

    if (isDeleted.value) {
      // 連続で削除処理を行った場合にDOMが生成される前にisFlashがtrueになってしまうため、setTimeoutで遅延させる
      setTimeout(() => {
        isFlash.value = true;
      }, 100);
    }

    // ブラウザバックで削除処理後のページに戻った場合のフラッシュメッセージ再表示を防ぐため、セッションに削除済みidを保存
    if (props.deleteId) {
        sessionStorage.setItem('deleteId', props.deleteId);
    }
});
</script>

<template>
    <div v-if="isFlash" class="fadeInSlideUp fixed bottom-16 left-0 z-20 w-screen bg-blue-400 text-white p-3">
        スポットを削除しました。
    </div>
</template>    