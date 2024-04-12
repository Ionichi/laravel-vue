<template>
    <main>
        <NavbarComponent />
        <section class="container mb-1">
            <div class="py-5 d-flex align-items-center justify-content-between">
                <span class="h1 pb-2 border-bottom border-3 border-dark">Edit Data Course</span>
                <div class="d-flex justify-content-center align-items-center gap-3">
                    <span class="h5">Status : </span>
                    <SwitchButton v-model:status="course.status" />
                </div>
            </div>
        </section>
        <section class="container pb-5">
            <form @submit.prevent="sendData" class="row needs-validation" novalidate>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nama_kursus" ref="inpNamaKursus" placeholder="Your course name" v-model="course.nama_kursus" required>
                        <label for="nama_kursus">Nama Kursus <span class="text-danger">*</span></label>
    
                        <div class="invalid-feedback">
                            Nama kursus wajib di isi!
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="hargaFormat" placeholder="Course price..." v-model="hargaFormat" required @keyup="formatCurrency">
                        <label for="hargaFormat">Harga <span class="text-danger">*</span></label>
    
                        <div class="invalid-feedback">
                            Harga wajib di isi!
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <select id="tutor" class="form-control" v-model="course.tutor_user_id" required>
                            <option value=""> -- Pilih Tutor -- </option>
                            <option v-for="(tutor, index) in tutors" :value="tutor.tutor_user_id" :key="index">
                                {{ tutor.nama_tutor }}
                            </option>
                        </select>
                        <label for="tutor">Tutor <span class="text-danger">*</span></label>
    
                        <div class="invalid-feedback">
                            Tutor wajib di pilih!
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <button type="submit" class="btn btn-success text-light" style="width: 100%">Simpan</button>
                </div>
                <div class="col-md-6 mb-3">
                    <button type="button" @click="cancelCreate" class="btn btn-outline-dark" style="width: 100%">Batal</button>
                </div>
            </form>
        </section>
    </main>
</template>
<script>
import axios from "axios";
import useSwal from "@/utils/useSwal";

export default {
    name: 'CourseEditView',
    data: () => ({
        course: {
            id: '',
            status: '',
            tutor_user_id: '',
            nama_kursus: '',
            harga: 0,
        },
        hargaFormat: 'Rp 0',
        tutors: [],
    }),
    async created() {
        const id = this.$route.params.id;
        const { errorAlert } = useSwal();
        try {
            const response = await axios.get('/course/edit/' + id);

            this.course.id = response.data.data.id;
            this.course.status = response.data.data.status;
            this.course.tutor_user_id = response.data.data.tutor_user_id;
            this.course.nama_kursus = response.data.data.nama_kursus;
            this.course.harga = response.data.data.harga;
            this.hargaFormat = this.numberToCurrency(this.course.harga);

            const res_tutor = await axios.get('/course/get-tutor');
            this.tutors = res_tutor.data.data;
        } catch (error) {
            const { data } = error.response;
            errorAlert(data.message);
            
            this.$router.push('/course');
        }
    },
    mounted() {
        this.$refs.inpNamaKursus.focus();
    },
    methods: {
        async sendData(event) {
            const form = event.target;
            const { loadingAlert, successAlert, errorAlert, validateAlert } = useSwal();

            if(!form.checkValidity()) {
                event.stopPropagation();
            } else {
                loadingAlert('Loading...');
                try {
                    const response = await axios.post('/course/update', this.course);
                    successAlert(response.data.message);
                    this.$router.push('/course');
                } catch(error) {
                    if (error.response && error.response.status === 422) {
                        validateAlert(error.response.data.message);
                    } else {
                        errorAlert(error.response.data.message);
                    }
                }
            }

            form.classList.add('was-validated');
        },
        cancelEdit() {
            this.$router.push('/course');
        },
        formatCurrency(event) {
            let value = parseInt(event.target.value.replace(/[^\d]/g, ''));
            if(!value) {
                value = 0;
            }
            this.course.harga = value;
            this.hargaFormat = this.numberToCurrency(value);

            event.target.value = this.hargaFormat;
        },
        numberToCurrency(number) {
            return new Intl.NumberFormat('id-ID', {style: 'currency', currency: 'IDR', maximumSignificantDigits: 3}).format(number);
        }
    }
}
</script>