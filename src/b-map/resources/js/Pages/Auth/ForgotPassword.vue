<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import BlueButton from '@/Components/Button.vue';
import FooterMenu from '@/Components/FooterMenu.vue';
import Layout from '@/Layouts/Layout.vue';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head title="パスワードリセット" />

    <Layout>
        <div class="mt-32" >
            <div class="mb-4 text-sm text-gray-600">
                <h2 class="font-bold mb-4">
                    パスワードをお忘れですか？
                </h2>
                以下の入力フォームに入力されたメールアドレスにパスワードリセット用の
                リンクをお送りします。
            </div>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">{{ status }}</div>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="メールアドレス" />           
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                        />
                     <InputError class="mt-2" :message="form.errors.email" />
                </div> 

                <div class="flex items-center justify-end mt-4">
                    <!-- 送信処理が進行中はボタンの透明度が変化し、ボタンを無効化 -->
                    <BlueButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        リンクを送信する
                    </BlueButton>
                </div>
            </form>
        </div>
    </Layout>
</template>
