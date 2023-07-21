<script setup>
import { ref, defineProps, defineEmits } from 'vue';

defineProps({
    modelValue: {
        type: [String, Number], // You can adjust the type to match your data
        required: true,
    },
    options: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits();

const handleChange = (event) => {
    const selectedValue = event.target.value;
    emit('update:modelValue', selectedValue);
};
</script>
<template>
    <div>
        <select :value="modelValue" @change="handleChange" class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option v-for="option in options" :value="option.value" :key="option.value">
                {{ option.label }}
            </option>
        </select>
    </div>
</template>


