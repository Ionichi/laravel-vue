<template>
    <main>
        <NavbarComponent />
        <TableComponent :dataTitle="dataTitle" :headers="headers" :url="url" ref="courseTable" />
    </main>
</template>
<script>
    import useSwal from '@/utils/useSwal';
    import axios from 'axios';
    export default {
        name: 'CourseView',
        data: () => ({
            dataTitle: 'Course',
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
                    title: 'Status Kursus',
                    align: 'center',
                    key: 'status',
                },
                {
                    title: 'Nama Kursus',
                    align: 'start',
                    key: 'nama_kursus',
                },
                {
                    title: 'Harga (Rp)',
                    align: 'center',
                    key: 'harga',
                },
                {
                    title: 'Nama Mentor',
                    align: 'start',
                    key: 'nama_tutor',
                },
                {
                    title: 'Email',
                    align: 'start',
                    key: 'email',
                },
                {
                    title: 'Username',
                    align: 'start',
                    key: 'username',
                },
                {
                    title: 'Jenis Kelamin',
                    align: 'center',
                    key: 'gender',
                },
                {
                    title: 'No WA',
                    align: 'end',
                    key: 'no_wa',
                },
                {
                    title: 'Status Tutor',
                    align: 'center',
                    key: 'status_tutor',
                },
            ],
            url: '/course',
        }),
        methods: {
            async deleteConfirm(id) {
                const { loadingAlert, successAlert, errorAlert, questionAlert } = useSwal();
                let confirm = await questionAlert('Yakin ingin menghapus data course?');
                if(confirm) {
                    loadingAlert('Loading...');
                    try {
                        const response = await axios.post('/course/delete/' + id);
                        successAlert(response.data.message);
                        this.$refs.courseTable.loadItems();
                    } catch(error) {
                        if (error.response) {
                            errorAlert(error.response.data.message);
                        }
                    }
                }
            },
            tambahData() {
                this.$router.push('/course/create');
            },
            editData(id) {
                this.$router.push('/course/edit/' + id);
            }
        }
    }
</script>