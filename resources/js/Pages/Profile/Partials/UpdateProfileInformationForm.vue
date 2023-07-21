<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import ImageInput from '@/Components/ImageInput.vue';
import DropdownInput from '@/Components/DropdownInput.vue';
import DatePicker from '@/Components/DatePicker.vue';

import { Link, useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { notify } from "notiwind";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const genderOptions = [
    { label: 'Male', value: 'male' },
    { label: 'Female', value: 'female' },
];

const user = usePage().props.auth.user;

const form = useForm({
    id: user.id,
    name: user.name,
    email: user.email,
    avatar: user.avatar,
    gender: user.gender,
    date_of_birth: user.date_of_birth,
    phone_number: user.phone_number,
});

const updateProfile = async() => {
    try {
        const formData = new FormData();

        formData.append('id', form.id);
        formData.append('name', form.name);
        formData.append('email', form.email);
        formData.append('avatar', form.avatar);
        formData.append('gender', form.gender);
        formData.append('date_of_birth', form.date_of_birth);
        formData.append('phone_number', form.phone_number);

        await axios.patch(route('profile.update'), formData, {
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
}

const getErrorMessage = (field) => {
    return Array.isArray(form.errors[field]) ? form.errors[field][0] : '';
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information and email address.
            </p>
        </header>

        <form @submit.prevent="updateProfile" enctype="multipart/form-data" class="mt-6 space-y-6">
            <div>
                <InputLabel for="avatar" value="Avatar" />

                <ImageInput
                    id="avatar"
                    class="mt-1 block w-full"
                    v-model="form.avatar"
                />

                <InputError class="mt-2" :message="getErrorMessage('avatar')" />
            </div>
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="getErrorMessage('name')" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="getErrorMessage('email')" />
            </div>

            <div>
                <InputLabel
                    for="gender"
                    value="Gender" />

                <DropdownInput
                    id="gender"
                    class="mt-1 block w-full"
                    v-model="form.gender"
                    :options="genderOptions"/>

                    <InputError class="mt-2" :message="getErrorMessage('gender')" />
            </div>

            <div>
                <InputLabel
                    for="date_of_birth"
                    value="Date of Birth" />

                <DatePicker
                    id="date_of_birth"
                    class="mt-1 block w-full"
                    v-model="form.date_of_birth"
                    required />

                <InputError class="mt-2" :message="getErrorMessage('date_of_birth')" />
            </div>

            <div>
                <InputLabel for="phone_number" value="Phone Number" />

                <TextInput
                    id="phone_number"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.phone_number"
                    required
                    autofocus
                    autocomplete="phone_number" />

                <InputError class="mt-2" :message="getErrorMessage('phone_number')" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
