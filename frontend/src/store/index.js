import { defineStore } from "pinia";
import useSwal from "@/utils/useSwal";
import axios from "axios";


export const useAuthStore = defineStore({
    id: 'auth-store',
    state: () => ({
        authenticate: false,
        user: null,
    }),
    getters: {
        getAuthStatus: (state) => {
            return state.authenticate;
        }
    },
    actions: {
        async login(url, payload) {
            const { loadingAlert, successAlert, errorAlert, validateAlert } = useSwal();

            loadingAlert('Sedang Login...');

            try {
                const response = await axios.post(url, payload);
                successAlert(response.data.message);
                
                this.authenticate = true;
                this.user = response.data.user;
                
                return true;
            } catch (err) {
                if (err.response && err.response.status === 422) {
                    validateAlert(err.response.data.message);
                } else {
                    errorAlert(err.response.data.message);
                }

                return false;
            }
        },
        async getUser() {
            try {
                const response = await axios.get('/me');
                if (response.status === 200) {
                    return response.data;
                } else {

                    return false;
                }
            } catch (err) {
                console.error(err);
                
                return false;
            }
        },
        async validateAuth() {
            const response = await this.getUser();
            if(response) {
                this.authenticate = true;
                this.user = response.user;

                return true;
            }

            return false;
        }
    }
});