<script setup>
import { defineProps, defineEmits } from 'vue';

defineProps({ links: Array })

const emit = defineEmits(['searchSpot']);

const sendURL = (url) => {
    emit('searchSpot', url);
}
</script>

<template>
    <div v-if="links.length > 3">
        <div class="flex flex-wrap mt-5">
            <template v-for="link in links" :key="index">
                <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border border-slate-200 rounded"
                v-html="link.label" />

                <button v-else @click="sendURL(link.url)" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border border-slate-400 rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500"
                :class="{ 'bg-blue-700 text-white': link.active }" v-html="link.label" />
            </template>
        </div>
    </div>
</template>