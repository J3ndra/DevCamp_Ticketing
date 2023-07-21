<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import FilterTextInput from "@/Components/FilterTextInput.vue";
import ButtonVue from "@/Components/Button.vue";
import DataTableVue from "@/Components/DataTable.vue";
import LoadingVue from "@/Components/Loading.vue";
import AlertVue from "@/Components/Alert.vue";
import PaginationVue from "@/Components/Pagination.vue";
import DropdownEditMenuVue from "@/Components/DropdownEditMenu.vue";

import EmptyIcon from "@/Components/src/Icons/EmptyIcon.vue";
import TrashIcon from "@/Components/src/Icons/TrashIcon.vue";
import EditIcon from "@/Components/src/Icons/EditIcon.vue";

import debounce from "@/Composables/debounce";

import ModalForm from "./ModalForm.vue";

import { Head } from "@inertiajs/vue3";
import { ref, onMounted, reactive } from "vue";
import { string } from "vue-types";
import { notify } from "notiwind";

const query = ref([]);
const searchFilter = ref("");

const pagination = ref({
    count: "",
    current_page: 1,
    per_page: "",
    total: 0,
    total_pages: 1,
});

const alertData = reactive({
    headerLabel: "",
    contentLabel: "",
    closeLabel: "",
    submitLabel: "",
});

const itemSelected = ref({});
const openAlert = ref(false);
const isLoading = ref(true);
const updateAction = ref(false);
const openModalForm = ref(false);

const heads = ["Name", "Created By", "Description", "Date", "Price", ""];

const props = defineProps({
    title: string(),
});

const handleAddModalForm = () => {
    updateAction.value = false;
    openModalForm.value = true;
};

const closeModalForm = () => {
    itemSelected.value = ref({});
    openModalForm.value = false;
};

const successSubmit = () => {
    isLoading.value = true;
    getData(pagination.value.current_page);
};

const alertDelete = (data) => {
    itemSelected.value = data;
    openAlert.value = true;
    alertData.headerLabel = "Are you sure to delete this?";
    alertData.contentLabel = "You won't be able to revert this!";
    alertData.closeLabel = "Cancel";
    alertData.submitLabel = "Delete";
};

const closeAlert = () => {
    itemSelected.value = ref({});
    openAlert.value = false;
};

const getData = debounce(async (page) => {
    axios
        .get(route("event.getData"), {
            params: {
                page: page,
                search: searchFilter.value,
            },
        })
        .then((res) => {
            query.value = res.data.data;
            pagination.value = res.data.meta.pagination;
        })
        .catch((res) => {
            notify(
                {
                    type: "error",
                    group: "top",
                    text: res.response.data.message,
                },
                2500
            );
        })
        .finally(() => (isLoading.value = false));
}, 500);

const nextPaginate = () => {
    pagination.value.current_page += 1;
    isLoading.value = true;
    getData(pagination.value.current_page);
};

const previousPaginate = () => {
    pagination.value.current_page -= 1;
    isLoading.value = true;
    getData(pagination.value.current_page);
};

const searchHandle = (search) => {
    searchFilter.value = search;
    isLoading.value = true;
    getData(1);
};

const deleteHandle = async () => {
    axios
        .delete(route("event.destroy", { id: itemSelected.value.id }))
        .then((res) => {
            notify(
                {
                    type: "success",
                    group: "top",
                    text: res.data.meta.message,
                },
                2500
            );
            openAlert.value = false;
            isLoading.value = true;
            getData(pagination.value.current_page);
        })
        .catch((res) => {
            notify(
                {
                    type: "error",
                    group: "top",
                    text: res.response.data.message,
                },
                2500
            );
        });
};

onMounted(() => {
    getData(1);
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Event
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div
                        class="bg-white shadow-lg rounded-sm border border-slate-200"
                        :class="isLoading && 'min-h-[40vh] sm:min-h-[50vh]'"
                    >
                        <header
                            class="block justify-between items-center sm:flex py-6 px-4"
                        >
                            <h2 class="font-semibold text-slate-800">
                                All Events
                                <span class="text-slate-400 !font-medium ml">{{
                                    pagination.total
                                }}</span>
                            </h2>
                            <div
                                class="mt-3 sm:mt-0 flex space-x-2 sm:justify-between justify-end"
                            >
                                <FilterTextInput @search="searchHandle" />
                                <ButtonVue
                                    label="Add Event"
                                    type="primary"
                                    class="mt-auto"
                                    @click="handleAddModalForm"
                                />
                            </div>
                        </header>
                        <DataTableVue :heads="heads" :isLoading="isLoading">
                            <tr v-if="isLoading">
                                <td
                                    class="h-[100%] overflow-hidden my-2"
                                    :colspan="heads.length"
                                >
                                    <LoadingVue />
                                </td>
                            </tr>
                            <tr v-else-if="query.length === 0 && !isLoading">
                                <td
                                    class="overflow-hidden my-2"
                                    :colspan="heads.length"
                                >
                                    <div
                                        class="flex items-center flex-col w-full my-32"
                                    >
                                        <EmptyIcon />
                                        <div
                                            class="mt-9 text-slate-500 text-xl md:text-xl font-medium"
                                        >
                                            Result not found.
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr
                                v-for="(data, index) in query"
                                :key="index"
                                v-else
                            >
                                <td class="px-4 whitespace-nowrap h-16">
                                    {{ data.name }}
                                </td>
                                <td class="px-4 whitespace-nowrap h-16">
                                    {{ data.description }}
                                </td>
                                <td
                                    class="px-4 whitespace-nowrap h-16 text-right"
                                >
                                    <DropdownEditMenuVue
                                        class="relative inline-flex r-0"
                                        :align="'right'"
                                        :last="
                                            index === query.length - 1
                                                ? true
                                                : false
                                        "
                                    >
                                        <li
                                            class="cursor-pointer hover:bg-slate-100"
                                            @click="handleEditModal(data)"
                                        >
                                            <div
                                                class="flex items-center space-x-2 p-3"
                                            >
                                                <span>
                                                    <EditIcon color="primary" />
                                                </span>
                                                <span>Edit</span>
                                            </div>
                                        </li>
                                        <li
                                            class="cursor-pointer hover:bg-slate-100"
                                        >
                                            <div
                                                class="flex justify-between items-center space-x-2 p-3"
                                                @click="alertDelete(data)"
                                            >
                                                <span>
                                                    <TrashIcon color="danger" />
                                                </span>
                                                <span>Delete</span>
                                            </div>
                                        </li>
                                    </DropdownEditMenuVue>
                                </td>
                            </tr>
                        </DataTableVue>
                        <div class="px-4 py-6">
                            <PaginationVue
                                :pagination="pagination"
                                @next="nextPaginate"
                                @previous="previousPaginate"
                            />
                        </div>
                    </div>
                    <AlertVue
                        :open-dialog="openAlert"
                        @closeAlert="closeAlert"
                        @submitAlert="deleteHandle"
                        type="danger"
                        :headerLabel="alertData.headerLabel"
                        :content-label="alertData.contentLabel"
                        :close-label="alertData.closeLabel"
                        :submit-label="alertData.submitLabel"
                    />
                    <ModalForm
                        data="itemSelected"
                        :update-action="updateAction"
                        :open-dialog="openModalForm"
                        @close="closeModalForm"
                        @successSubmit="successSubmit"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
