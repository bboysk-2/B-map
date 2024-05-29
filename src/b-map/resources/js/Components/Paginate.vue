<script setup>
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

defineProps({ links: Array })
</script>

<template>
    <!-- 「<<前」と「次>>」と「1」以外のリンクが存在する場合（2ページ以上リンクがある場合）のみページネーションリンクを表示 -->
    <div v-if="links.length > 3">
        <div class="flex flex-wrap -mb-1">
            <template v-for="link in links" :key="link.id">
                <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border border-slate-200 rounded"
                v-html="link.label" />

                <Link v-else class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border border-slate-400 rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500"
                :class="{ 'bg-blue-700 text-white': link.active }" :href="link.url" v-html="link.label" />
            </template>
        </div>
    </div>
</template>