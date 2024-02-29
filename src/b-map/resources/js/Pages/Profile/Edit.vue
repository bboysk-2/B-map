<script setup>
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import UpdateAvatar from '@/Components/UpdateAvatar.vue';
import Layout from '@/Layouts/Layout.vue';
import { defineProps } from 'vue';

const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
    errors: Object,
    avatarPath: String,
});
</script>

<template>
    <Head title="プロフィール編集" />

    <Layout>
        <h1 class="text-xl font-extrabold pl-4">プロフィール編集</h1>
            
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

        <div class="pt-10 pb-5">
            <UpdateAvatar :status="status" :avatarPath="avatarPath" />
        </div>
        
        <DeleteUserForm class="max-w-xl" />        
    </Layout>                 
</template>
