<script setup>
import { useForm } from '@inertiajs/vue3';
import BlueButton from '@/Components/Button.vue';
import { defineProps, ref } from 'vue';

const props = defineProps({
    status: String,
    avatarPath: String,
});

const form = useForm({
    avatar: null,
});

const showStatus = ref(false);

const toggleStatus = () => {
      showStatus.value = true; 
      setTimeout(() => {
        showStatus.value = false; 
      }, 3000);
    };

const submit = () => {
  form.post(route('avatar.update'),{ preserveScroll: true });
};

</script>

<template>
        <h2 class="text-lg font-medium text-gray-900 mb-6">プロフィール画像</h2>
        <!-- フォームの送信ボタンがクリックされたときにページの再読み込みを防ぎ、代わりにsubmitメソッドが呼び出される -->
        <form @submit.prevent="submit">
            <!-- ユーザーが選択した画像ファイルをform.avatarに代入 -->
            <input type="file" name="avatar" @input="form.avatar = $event.target.files[0]" accept="image/*">

            <div class="flex items-center justify-center">
                <div class="w-20 h-20 rounded-full overflow-hidden flex items-center justify-center mt-7 ml-4">
                    <img :src="avatarPath" @load="toggleStatus" class="object-cover w-full h-full">
                </div>

                <BlueButton type="submit" :disabled="form.processing" class="ml-8 mt-7">画像を更新</BlueButton>         
            </div>                         
        </form>
        
        <div class="h-10 mt-5">
            <p v-if="showStatus" class="ml-4 text-green-500">{{ status }}</p>    
        </div>            
</template>
