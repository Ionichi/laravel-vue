<template>
    <main>
        <NavbarComponent />
        <section class="container mb-1">
            <div class="py-5 d-flex align-items-center justify-content-between">
                <span class="h1 pb-2 border-bottom border-3 border-dark">Edit Data Tutor</span>
                <div class="d-flex justify-content-center align-items-center gap-3">
                    <span class="h5">Status : </span>
                    <SwitchButton v-model:status="tutor.status" />
                </div>
            </div>
        </section>
        <section class="container pb-5">
            <form @submit.prevent="sendData" class="row needs-validation" novalidate>
                <div class="col-md-6">
                    <input type="hidden" name="id" :value="tutor.id">
                    <div class="form-floating mb-3">
                        <input type="username" class="form-control" id="username" placeholder="Your username" :value="tutor.username" required readonly disabled>
                        <label for="username">Username <span class="text-danger">*</span></label>

                        <div class="invalid-feedback">
                            Username wajib di isi!
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control disabled" id="email" placeholder="Your email" :value="tutor.email" required readonly disabled>
                        <label for="email">Email <span class="text-danger">*</span></label>
    
                        <div class="invalid-feedback">
                            Email wajib di isi!
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="fullname" placeholder="Your fullname" v-model="tutor.fullname" ref="inpFullname" required>
                        <label for="fullname">Nama Lengkap <span class="text-danger">*</span></label>
    
                        <div class="invalid-feedback">
                            Nama lengkap wajib di isi!
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" id="tgl_lahir" placeholder="Your birth date" v-model="tutor.tgl_lahir" required>
                        <label for="tgl_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
    
                        <div class="invalid-feedback">
                            Tanggal lahir wajib di isi!
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <!-- <label for="gender">Jenis Kelamin <span class="text-danger">*</span></label> -->
                        <select id="gender" class="form-control" v-model="tutor.gender" required>
                            <option value=""> -- Pilih Jenis Kelamin -- </option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                        <label for="gender">Jenis Kelamin <span class="text-danger">*</span></label>
    
                        <div class="invalid-feedback">
                            Jenis kelamin wajib di isi!
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="tel" class="form-control" id="no_wa" placeholder="Your phone number" v-model="tutor.no_wa" required>
                        <label for="no_wa">No WA <span class="text-danger">*</span></label>
    
                        <div class="invalid-feedback">
                            No WA wajib di isi!
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="provinsi" placeholder="Your province" v-model="tutor.provinsi" required>
                        <label for="provinsi">Provinsi <span class="text-danger">*</span></label>
    
                        <div class="invalid-feedback">
                            Provinsi wajib di isi!
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="kota" placeholder="Your city" v-model="tutor.kota" required>
                        <label for="kota">Kota <span class="text-danger">*</span></label>
    
                        <div class="invalid-feedback">
                            Kota wajib di isi!
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="kode_pos" placeholder="Your Pos Number" v-model="tutor.kode_pos" required>
                        <label for="kode_pos">Kode Pos <span class="text-danger">*</span></label>
    
                        <div class="invalid-feedback">
                            Kode Pos wajib di isi!
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <textarea class="form-control" style="height: 130px" id="alamat_lengkap" placeholder="Your city" v-model="tutor.alamat_lengkap" required></textarea>
                        <label for="alamat_lengkap">Alamat Lengkap <span class="text-danger">*</span></label>
    
                        <div class="invalid-feedback">
                            Alamat Lengkap wajib di isi!
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <button type="submit" class="btn btn-success text-light" style="width: 100%">Simpan</button>
                </div>
                <div class="col-md-6 mb-3">
                    <button type="button" @click="cancelEdit" class="btn btn-outline-dark" style="width: 100%">Batal</button>
                </div>
            </form>
        </section>
    </main>
</template>
<script>
import axios from "axios";
import useSwal from "@/utils/useSwal";

export default {
    name: 'TutorEditView',
    data: () => ({
        tutor: {
            id: '',
            status: '',
            username: '',
            email: '',
            fullname: '',
            gender: '',
            tgl_lahir: '',
            no_wa: '',
            provinsi: '',
            kota: '',
            kode_pos: '',
            alamat_lengkap: '',
        },
    }),
    async created() {
        const id = this.$route.params.id;
        const { errorAlert } = useSwal();
        try {
            const response = await axios.get('/tutor/edit/' + id);

            this.tutor.id = response.data.data.id;
            this.tutor.status = response.data.data.status;
            this.tutor.username = response.data.data.username;
            this.tutor.email = response.data.data.email;
            this.tutor.fullname = response.data.data.fullname;
            this.tutor.gender = response.data.data.gender;
            this.tutor.tgl_lahir = response.data.data.tgl_lahir;
            this.tutor.no_wa = response.data.data.no_wa;
            this.tutor.provinsi = response.data.data.provinsi;
            this.tutor.kota = response.data.data.kota;
            this.tutor.kode_pos = response.data.data.kode_pos;
            this.tutor.alamat_lengkap = response.data.data.alamat_lengkap;
        } catch (error) {
            const { data } = error.response;
            errorAlert(data.message);
            
            this.$router.push('/tutor');
        }
    },
    mounted() {
        this.$refs.inpFullname.focus();
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
                    const response = await axios.post('/tutor/update', this.tutor);
                    successAlert(response.data.message);
                    this.$router.push('/tutor');
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
            this.$router.push('/tutor');
        }
    }
}
</script>