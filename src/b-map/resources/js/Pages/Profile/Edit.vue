<script setup>
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import FooterMenu from '@/Components/FooterMenu.vue';
import BlueButton from '@/Components/Button.vue';

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
    erros: Object,
    avatarPath: String,
});

const form = useForm({
    avatar: null,
});

const submit = () => {
  form.post(route('avatar.update'),{ preserveScroll: true });
};
</script>

<template>
    <Head title="プロフィール編集" />

    <body>
        <Header />
        <div class="min-h-screen" style="background-image: url('/images/background_image.png');">
            <div class="container mx-auto px-5 mb-10 flex justify-center align-center">
                <div class="w-full max-w-sm my-24">
                    <h1 class="text-xl font-extrabold pl-4">プロフィール編集</h1>
                    <div class="mx-6">
                        <div class="py-5">
                            <UpdateProfileInformationForm
                                :must-verify-email="mustVerifyEmail"
                                :status="status"
                                class="max-w-xl"
                            />
                        </div>
    
                        <div class="py-10">
                            <UpdatePasswordForm class="max-w-xl" />
                        </div>

                        <div class="py-10">
                            <h2 class="text-lg font-medium text-gray-900 mb-6">プロフィール画像</h2>
                            <!-- フォームの送信ボタンがクリックされたときにページの再読み込みを防ぎ、代わりにsubmitメソッドが呼び出される -->
                            <form @submit.prevent="submit">
                                <!-- ユーザーが選択した画像ファイルをform.avatarに代入 -->
                                <input type="file" name="avatar" @input="form.avatar = $event.target.files[0]" accept="image/*">
                                <div class="flex items-center">
                                    <div class="w-16 h-16 rounded-full overflow-hidden flex items-center justify-center mt-7 ml-4">
                                        <img :src="avatarPath" class="object-cover w-full h-full">
                                    </div>
                                    <BlueButton type="submit" :disabled="form.processing" class="ml-8 mt-7">アップロード</BlueButton>         
                                </div>                         
                            </form>
                            <p v-if="status" class="ml-4 mt-7 text-green-500">{{ status }}</p>    
                        </div>
        
                        <div class="pt-10">
                            <DeleteUserForm class="max-w-xl" />
                        </div>
                    </div>
                </div>
            </div>
        </div>   
        <FooterMenu /> 
    </body>            
</template>
