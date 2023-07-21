<script setup>
import { ref, defineProps, defineEmits } from 'vue';

defineProps({
    modelValue: {
        type: String,
        required: true,
    },
});

const emit = defineEmits();

const handleDateChange = (event) => {
    const selectedDate = event.target.value;
    emit('update:modelValue', selectedDate);
};
</script>
<template>
    <div>
        <input
            type="date"
            :value="modelValue"
            @input="handleDateChange"
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
    </div>
</template>


