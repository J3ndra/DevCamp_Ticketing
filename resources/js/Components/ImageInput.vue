<script setup>
import { ref, defineProps, defineEmits, computed } from 'vue';

const { modelValue } = defineProps(['modelValue']);

const fileInput = ref(null);
const fileObject = ref(null);
const base64Image = ref('');

const openFileInput = () => {
  fileInput.value.click();
};

const emit = defineEmits();

const handleImageChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    fileObject.value = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      base64Image.value = e.target.result;
      emit('update:modelValue', fileObject.value);
    };
    reader.readAsDataURL(file);
  }
};

const previewImage = computed(() => modelValue || base64Image.value);
</script>

<template>
  <div>
    <input type="file" accept="image/*" style="display: none" ref="fileInput" @change="handleImageChange" />
    <label>
      <img :src="previewImage" alt="Selected Image" @click="openFileInput" class="cursor-pointer border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
    </label>
  </div>
</template>
