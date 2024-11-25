/**
 * Displays a custom alert using SweetAlert2.
 * @param {object} options - An object containing the options for the alert
 * @param {string} options.status - The status of the alert ('error', 'success', or 'warning')
 * @param {string} options.title - The title of the alert
 * @param {string} options.message - The message content of the alert
 */

const customAlert = ({ status, title, message }) => {
    switch (status) {
        case 'error':
            swal.fire({
                title: String(title),
                text: String(message),
                icon: 'error',
                timer: 1500,
            }).then(() => {
                location.reload()
            })
            break
        case 'success':
            swal.fire({
                title: String(title),
                text: String(message),
                icon: 'success',
                timer: 1500,
            }).then(() => {
                location.reload()
            })
            break
        case 'warning':
            swal.fire({
                title: String(title),
                text: String(message),
                icon: 'warning',
                confirmButtonText: 'Continue Shopping',
                cshowConfirmButton: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload()
                }
            })
            break
        default:
            console.error(`Invalid alert status: ${status}`)
            break
    }
}
