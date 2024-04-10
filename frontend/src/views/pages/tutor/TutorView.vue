<template>
    <main>
        <NavbarComponent />
        <TableComponent :dataTitle="dataTitle" :headers="headers" :url="url" ref="tutorTable" />
    </main>
</template>
<script>
    import axios from "axios";
    import useSwal from '@/utils/useSwal';
    export default {
        data: () => ({
            dataTitle: 'Tutor',
            headers: [
                {
                    title: 'No',
                    sortable: false,
                    align: 'center',
                    key: "index",
                },
                {
                    title: 'Actions',
                    align: 'center',
                    key: 'actions',
                },
                {
                    title: 'Status',
                    align: 'center',
                    key: 'status',
                },
                {
                    title: 'Username',
                    align: 'start',
                    key: 'username',
                },
                {
                    title: 'Email',
                    align: 'start',
                    key: 'email',
                },
                {
                    title: 'Nama Mentor',
                    align: 'start',
                    key: 'nama_tutor',
                },
                {
                    title: 'Jenis Kelamin',
                    align: 'center',
                    key: 'gender',
                },
                {
                    title: 'Tanggal Lahir',
                    align: 'center',
                    key: 'tgl_lahir',
                },
                {
                    title: 'No WA',
                    align: 'end',
                    key: 'no_wa',
                },
                {
                    title: "Provinsi",
                    align: 'center',
                    key: 'provinsi',
                },
                {
                    title: 'Kota',
                    align: 'center',
                    key: 'kota',
                },
                {
                    title: 'Kode Pos',
                    align: 'center',
                    key: 'kode_pos',
                },
                {
                    title: 'Alamat Lengkap',
                    align: 'start',
                    key: 'alamat_lengkap',
                }
            ],
            url: '/tutor',
        }),
        methods: {
            async deleteConfirm(id) {
                const { loadingAlert, successAlert, errorAlert, questionAlert } = useSwal();
                let confirm = await questionAlert('Yakin ingin menghapus data tutor?');
                if(confirm) {
                    loadingAlert('Loading...');
                    try {
                        const response = await axios.post('/tutor/delete/' + id);
                        successAlert(response.data.message);
                        this.$refs.tutorTable.loadItems();
                    } catch(error) {
                        if (error.response) {
                            errorAlert(error.response.data.message);
                        }
                    }
                }
            },
            tambahData() {
                this.$router.push('/tutor/create');
            },
            editData(id) {
                this.$router.push('/tutor/edit/' + id);
            }
        }
    };
</script>