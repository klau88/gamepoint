<script setup>
import {Link} from '@inertiajs/vue3';
import {computed} from "vue";

const props = defineProps({
    title: String,
    headers: Array,
    items: Object,
    tableClass: String,
    collection: Object,
    pages: Object,
    state: String
});

const formatLinks = computed(() => {
    const links = props.collection.links;
    const currentIndex = links.findIndex(link => link.active);

    const range = 2;
    const min = Math.max(1, currentIndex - range);
    const max = Math.min(links.length - 2, currentIndex + range);

    const output = []

    output.push(links[0]);

    if (min > 1) {
        output.push({label: '...', url: null});
    }

    for (let i = min; i <= max; i++) {
        output.push(links[i]);
    }

    if (max < links.length - 2) {
        output.push({label: '...', url: null});
    }

    output.push(links[links.length - 1]);

    return output;
});
</script>

<template>

    <div class="flex flex-col items-center">

        <div class="p-2 text-center">
            <h2 class="text-lg font-bold">{{ title }}</h2>
        </div>

        <table :class="tableClass">
            <thead>
            <tr>
                <th class="p-2" v-for="header in headers">{{ header }}</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in props.items">
                <td class="p-2">{{ item.key }}</td>
                <td class="p-2">{{ item.value }}</td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="py-8" v-if="formatLinks.length > 1">
        <div class="flex flex-wrap -mb-1">
            <div v-for="(link, key) in formatLinks" :key="key">
                <div v-if="link.url === null"
                     class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                     v-html="link.label"/>
                <Link v-else
                      class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-blue-300 focus:border-indigo-500 focus:text-indigo-500"
                      :class="{ 'bg-blue-700 text-white': link.active }"
                      :href="link.url"
                      v-html="link.label"/>
            </div>
        </div>
    </div>
</template>
