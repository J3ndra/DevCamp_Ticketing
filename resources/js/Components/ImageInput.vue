<script setup>
import { ref, defineProps, computed, defineEmits } from "vue";

const { modelValue } = defineProps(["modelValue"]);

const fileInput = ref(null);
const fileObject = ref(null);
const base64Image = ref("");

const openFileInput = () => {
    fileInput.value.click();
};

const handleImageChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        fileObject.value = file;
        base64Image.value = ""; // Clear the old avatar preview
        const reader = new FileReader();
        reader.onload = (e) => {
            base64Image.value = e.target.result;
            emit("update:modelValue", fileObject.value); // Notify parent with the new File object
        };
        reader.readAsDataURL(file);
    }
};

const previewImage = computed(() => {
    if (modelValue instanceof File) {
        return URL.createObjectURL(modelValue);
    } else if (typeof modelValue === "string") {
        return modelValue; // Assume modelValue is already a valid image URL
    } else {
        return base64Image.value;
    }
});

const emit = defineEmits();
</script>

<template>
    <div>
        <input
            type="file"
            accept="image/*"
            style="display: none"
            ref="fileInput"
            @change="handleImageChange"
        />
        <label>
            <img
                :src="previewImage"
                alt="Selected Image"
                @click="openFileInput"
                class="cursor-pointer border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            />
        </label>
    </div>
</template>
