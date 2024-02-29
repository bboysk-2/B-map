<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import BlueButton from '@/Components/Button.vue';
import { ref } from 'vue';
import axios from 'axios';
import SpotImageUpdate from '@/Components/SpotImagesUpdate.vue';
import TextInput from '@/Components/TextInput.vue';
import Layout from '@/Layouts/Layout.vue';

defineProps({
    errors: Object,
    error: String,
});

</script>