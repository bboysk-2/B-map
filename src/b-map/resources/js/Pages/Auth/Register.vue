<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import FooterMenu from '@/Components/FooterMenu.vue';
import BlueButton from '@/Components/Button.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="会員登録" />

    <body>
        <Header />
        <div class="min-h-screen" style="background-image: url('/images/background_image.png');">
            <div class="container mx-auto px-5 mb-10 flex justify-center align-center">
                <div class="w-full max-w-sm my-24">
                    <h1 class="text-xl font-extrabold pl-4 mb-10">会員登録</h1>
                    <form @submit.prevent="submit">
                        <div class="mx-6">
                            <div>
                                <InputLabel for="name" value="ユーザー名" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                    autofocus
                                    autocomplete="name"
                                />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>
    
                            <div class="mt-4">
                                <InputLabel for="email" value="メールアドレス" />
                                <TextInput
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.email"
                                    required
                                    autocomplete="username"
                                />
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>
                
                            <div class="mt-4">
                                <InputLabel for="password" value="パスワード" />
                                <TextInput
                                    id="password"
                                    type="password"
                                    class="mt-1 block w-full"
                                    v-model="form.password"
                                    required
                                    autocomplete="new-password"
                                />            
                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>
                
                            <div class="mt-4">
                                <InputLabel for="password_confirmation" value="パスワード確認" />            
                                <TextInput
                                    id="password_confirmation"
                                    type="password"
                                    class="mt-1 block w-full"
                                    v-model="form.password_confirmation"
                                    required
                                    autocomplete="new-password"
                                />            
                                <InputError class="mt-2" :message="form.errors.password_confirmation" />
                            </div>
                
                            <div class="flex flex-col items-center justify-center mt-5">
                                <BlueButton class="my-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    登録する
                                </BlueButton> 
                                <div class="mt-6 mb-3">
                                    <p>ログインページは                                   
                                        <Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900">                       
                                            こちら
                                        </Link>
                                    </p>
                                </div>                            
                            </div>
                        </div>
                    </form> 
                </div>
            </div>
        </div>            
        <FooterMenu />
    </body>       
</template>

