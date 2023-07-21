<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DropdownInputVue from '@/Components/DropdownInput.vue';
import TextInput from '@/Components/TextInput.vue';
import DatePicker from '@/Components/DatePicker.vue';
import { Head, Link } from '@inertiajs/vue3';
import ImageInput from '@/Components/ImageInput.vue';

import { ref, reactive } from 'vue';
import { notify } from "notiwind";
import axios from 'axios';

const genderOptions = [
    { label: 'Male', value: 'male' },
    { label: 'Female', value: 'female' },
];

const form = reactive({
    name: ref(''),
    email: ref(''),
    password: ref(''),
    password_confirmation: ref(''),
    avatar: ref(null),
    gender: ref(''),
    dateOfBirth: ref(''),
    phone: ref(''),
    roleId: ref(2),
    errors: {}
});


const submit = async () => {
    try {
        const formData = new FormData();
        formData.append('name', form.name);
        formData.append('email', form.email);
        formData.append('password', form.password);
        formData.append('password_confirmation', form.password_confirmation);
        formData.append('avatar', form.avatar);

        formData.append('gender', form.gender);
        formData.append('dateOfBirth', form.dateOfBirth);
        formData.append('phone', form.phone);
        formData.append('roleId', form.roleId);

        const response = await axios.post(route('register'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });

        window.location.reload();

        notify({
            title: "Success",
            message: response.data.message,
            type: "success",
            group: "top",
        }, 2500);
    } catch (error) {
        if (error.response && error.response.data.errors) {
            form.errors = error.response.data.errors;
        } else {
            // Handle other types of errors, if needed
        }

        notify({
            title: "Error",
            message: error.response.data.message,
            type: "error",
            group: "top",
        }, 2500);
    }
};

const getErrorMessage = (field) => {
    return Array.isArray(form.errors[field]) ? form.errors[field][0] : '';
};

</script>

<template>
    <GuestLayout>

        <Head title="Register" />

        <form @submit.prevent="submit" enctype="multipart/form-data">
            <div>
                <InputLabel for="avatar" value="Avatar" />

                <ImageInput id="avatar" class="mt-1 block w-full" v-model="form.avatar" required autofocus
                    autocomplete="avatar" />

                <InputError class="mt-2" :message="getErrorMessage('avatar')" />
            </div>
            <div class="mt-4">
                <InputLabel for="name" value="Name" />

                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus
                    autocomplete="name" />

                <InputError class="mt-2" :message="getErrorMessage('name')" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                    autocomplete="username" />

                <InputError class="mt-2" :message="getErrorMessage('email')" />
            </div>

            <div class="mt-4">
                <InputLabel for="gender" value="Gender" />

                <DropdownInputVue id="gender" class="mt-1 block w-full" v-model="form.gender" :options="genderOptions"
                    required autofocus autocomplete="gender" />

                <InputError class="mt-2" :message="getErrorMessage('gender')" />
            </div>

            <div class="mt-4">
                <InputLabel for="dateOfBirth" value="Date of Birth" />

                <DatePicker id="dateOfBirth" class="mt-1 block w-full" v-model="form.dateOfBirth" required />

                <InputError class="mt-2" :message="getErrorMessage('dateOfBirth')" />
            </div>

            <div class="mt-4">
                <InputLabel for="phone" value="Phone Number" />

                <TextInput id="phone" type="text" class="mt-1 block w-full" v-model="form.phone" required autofocus
                    autocomplete="phone" />

                <InputError class="mt-2" :message="getErrorMessage('phone')" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                    autocomplete="new-password" />

                <InputError class="mt-2" :message="getErrorMessage('password')" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput id="password_confirmation" type="password" class="mt-1 block w-full"
                    v-model="form.password_confirmation" required autocomplete="new-password" />

                <InputError class="mt-2" :message="getErrorMessage('password_confirmation')" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link :href="route('login')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Already registered?
                </Link>

                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
