<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import FooterMenu from '@/Components/FooterMenu.vue';
import BlueButton from '@/Components/Button.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>    
    <Head title="Log in" />

    <body>
        <Header />
        <div class="min-h-screen" style="background-image: url('/images/background_image.png');">
            <div class="container mx-auto px-5 mb-10 flex justify-center align-center">
                <div class="w-full max-w-sm my-24">
                    <h1 class="text-xl font-extrabold pl-4 mb-10">ログイン</h1>
                    <div class="mx-6" >                            
                        <a href="social-auth/google">
                            <div class="w-full flex items-center justify-center border border-black bg-white py-1">
                                <img src="/images/google_icon.png" class="h-5 w-5 mx-1">
                                <p class="">Googleでログイン</p>                                
                            </div>
                        </a>
                    </div>
                    <div class="flex items-center my-6" >
                        <div class="flex-grow border-t border-black"></div>
                        <span class="mx-4">または</span>
                        <div class="flex-grow border-t border-black"></div>
                    </div>
                    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                        {{ status }}
                    </div>
            
                    <form @submit.prevent="submit">
                        <div class="mx-5 mb-10">
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
           
                        <div class="mx-5 mb-10">
                            <InputLabel for="password" value="パスワード" />               
                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                            />                
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>
                        <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ml-5"
                            >
                                パスワードをお忘れですか?
                        </Link>
                            
                        <div class="flex flex-col items-center justify-center mt-5">
                            <BlueButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                ログイン
                            </BlueButton>
                            <div class="mt-6 mb-3">
                                <p>会員登録の方は                                   
                                    <Link :href="route('register')" class="underline text-sm text-gray-600 hover:text-gray-900">                       
                                        こちら
                                    </Link>
                                </p>
                            </div>                            
                        </div>
                    </form>                                                   
                </div>
            </div>
            <FooterMenu />
        </div>
    </body>
</template>
