<script setup>
import Table from '@/Components/Table.vue';
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';

const props = defineProps({
    errors: Object,
    orderedByUser: Object,
    sumPerUser: Object,
    orderedByCurrency: Object,
    totalRevenuePerCurrency: Object,
    orderedByDate: Object,
    totalRevenuePerDay: Object,
});

const file = ref();
const form = useForm({
    file: file
});

const onFileChange = event => {
    form.file = event.target.files[0];
}

const uploadCsv = event => {
    form.post('/payments/upload-csv', {
        forceFormData: true
    });
}

const pages = {
    usersPage: props.orderedByCurrency.current_page,
    currencyPage: props.orderedByCurrency.current_page,
    datePage: props.orderedByDate.current_page
};
</script>

<template>
    <div class="h-screen">
        <div class="w-full bg-black flex flex-row justify-between">
            <h1 class="font-bold text-3xl p-2 text-white">Payments</h1>
        </div>
        <div class="h-full">
            <div class="flex flex-col justify-center items-center bg-gray-200">
                <div class="p-2">
                    <h2 class="font-bold text-2xl">
                        New payments
                    </h2>
                </div>
                <div class="p-2">
                    <div class="flex flex-row items-center">
                        <div class="px-2">
                            <input type="file" name="csv" @change="onFileChange">
                            <div v-if="errors.file" class="text-red-500">{{ errors.file }}</div>
                        </div>

                        <div class="">
                            <button type="submit" @click.prevent="uploadCsv"
                                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-blue-300 focus:border-indigo-500 focus:text-indigo-500 text-white bg-blue-500">
                                Upload
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-row justify-center">
                <div class="flex flex-col justify-between p-2 mx-2">
                    <Table
                        tableClass="m-2"
                        title="Total revenue per user"
                        :headers="['User ID', 'Amount']"
                        :collection="orderedByUser"
                        :items="props.sumPerUser"
                        :pages="pages"
                        state="usersPage"
                    />
                </div>
                <div class="flex flex-col justify-between p-2 mx-2">
                    <Table
                        tableClass="m-2"
                        title="Total revenue per currency"
                        :headers="['Currency', 'Amount']"
                        :collection="orderedByCurrency"
                        :items="props.totalRevenuePerCurrency"
                        :pages="pages"
                        state="currencyPage"
                    />
                </div>
                <div class="flex flex-col justify-between p-2 mx-2">
                    <Table
                        tableClass="m-2"
                        title="Total revenue per day"
                        :headers="['Date', 'Amount']"
                        :collection="orderedByDate"
                        :items="props.totalRevenuePerDay"
                        :pages="pages"
                        state="datePage"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
