<style scoped>
.container-table {
    margin: 0;
    overflow-x: auto;
    max-width: 100%;
}

</style>
<template>
    <section class="container">
        <div class="py-5 d-flex align-items-center justify-content-between">
            <span class="h1 pb-2 border-bottom border-3 border-dark">Data {{ dataTitle }}</span>
            <button type="button" @click="this.$parent.tambahData()" class="btn btn-success text-light">Tambah Data {{ dataTitle }}</button>
        </div>
        <div class="container-table">
            <v-data-table-server class="overflow-x-auto" :headers="headers" :items="items" :items-length="totalItems"
                :loading="loading" item-value="id" item-key="id" @update:options="loadItems">
                <template v-for="(header, index) in headers" v-slot:[`item.${header.key}`]="{ item }" :key="index">
                    <div v-if="header.key === 'status'">
                        <span class="badge p-2"
                            :class="item.status === 'Aktif' ? 'text-bg-success' : 'text-bg-danger'">{{ item.status
                            }}</span>
                    </div>
                    <div v-else-if="header.key === 'actions'">
                        <span @click="this.$parent.editData(item.id)" class="p-1 text-dark">
                            <v-icon icon="mdi-pencil" />
                        </span>
                        <span @click="this.$parent.deleteConfirm(item.id)" class="p-1 text-danger">
                            <v-icon icon="mdi-delete" />
                        </span>
                    </div>
                    <div v-else>
                        {{ item[header.key] }}
                    </div>
                </template>
            </v-data-table-server>
        </div>
    </section>
</template>
<script>
    import axios from 'axios';
    export default {
        name: 'TableComponent',
        props: {
            dataTitle: {
                type: String,
                required: true,
            },
            headers: {
                type: Array,
                required: true,
            },
            url: {
                type: String,
                required: true,
            }
        },
        data: () => ({
            items: [],
            totalItems: 0,
            loading: true,
        }),
        methods: {
            async loadItems() {
                this.loading = true;
                const response = await axios.get(this.url);

                if (response.data.status == 'success') {
                    this.items = response.data.data.map((item, index) => ({
                        ...item,
                        index: index + 1,
                    }));
                    this.totalItems = this.items.length;
                }

                this.loading = false;
            }
        }
    }
</script>