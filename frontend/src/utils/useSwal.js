import Swal from "sweetalert2";
import loadingImg from '../assets/preloader.gif';

export default function useSwal() {
    const loadingAlert = (message) => {
        Swal.fire({
            title: `${message}`,
            imageUrl: loadingImg,
            showConfirmButton: false,
            allowOutsideClick: false,
        });
    }

    const successAlert = (message) => {
        Swal.fire({
            title: 'Berhasil!',
            text: `${message}`,
            icon: 'success',
            confirmButtonText: 'Oke',
        });

    }

    const errorAlert = (message) => {
        Swal.fire({
            title: 'Gagal!',
            text: `${message}`,
            icon: 'error',
            confirmButtonText: 'Oke',
        });
    }

    const validateAlert = (message) => {
        let arrayMessage = "";
        let counter = 1;
        for(const field in message) {
            const errMessage = message[field];

            for(const msg of errMessage) {
                arrayMessage += `${counter}. ${msg}<br>`;
                counter++;
            }
        }

        Swal.fire({
            title: 'Gagal!',
            html: `${arrayMessage}`,
            icon: 'error',
            confirmButtonText: 'Oke',
        })
    }

    const informationAlert = (message) => {
        Swal.fire({
            title: 'Information!',
            text: `${message}`,
            icon: 'info',
            confirmButtonText: 'Oke',
        });
    }

    const questionAlert = (message) => {
        return new Promise(async (resolve) => {
            await Swal.fire({
                title: 'Konfirmasi!',
                text: `${message}`,
                icon: 'question',
                confirmButtonText: 'Oke',
                confirmButtonColor: '#DC3545',
                showCancelButton: true,
                cancelButtonText: 'Batal',
            }).then((result) => {
                if(result.isConfirmed) {
                    resolve(true);
                } else {
                    resolve(false);
                }
            });
        });
    }

    return {
        loadingAlert,
        successAlert,
        errorAlert,
        validateAlert,
        informationAlert,
        questionAlert,
    };
}