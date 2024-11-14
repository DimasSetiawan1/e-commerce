const email = document.getElementById('email').value
const nick = email.split('@')[0]
const provider = email.split('@')[1]
let i = 1
document.getElementById('email').innerHTML = `${nick.charAt()}`
const maskedNick = nick.slice(0, 2) + '*'.repeat(Math.max(0, nick.length - 2))
const maskedEmail = `${maskedNick}@${provider}`
document.getElementById('email').value = maskedEmail
const setEmail = () => {
    const emailInput = document.getElementById('email')
    const editEmailBtn = document.getElementById('editEmail')
    const originalValue = emailInput.value

    emailInput.removeAttribute('disabled')
    editEmailBtn.classList.remove('fa-pen-to-square')
    editEmailBtn.classList.add('fa-check-circle')
    emailInput.focus()
    const revertEmail = () => {
        emailInput.value = originalValue
        emailInput.setAttribute('disabled', '')
        editEmailBtn.classList.remove('fa-check-circle')
        editEmailBtn.classList.add('fa-pen-to-square')
    }

    emailInput.addEventListener('blur', revertEmail)

    emailInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault()
            const email = emailInput.value
            if (email.includes('@')) {
                $('#confirmEmailChangeModal').modal('show')
                document.getElementById('confirmEmailChange').onclick = () => {
                    const [nick, domain] = email.split('@')
                    const maskedNick =
                        nick.slice(0, 2) +
                        '*'.repeat(Math.max(0, nick.length - 2))
                    emailInput.blur()
                    emailInput.value = `${maskedNick}@${domain}`

                    fetch('./utils/update_profile.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json; charset=UTF-8',
                        },
                        body: `email=${encodeURIComponent(email)}`,
                    })
                        .then((response) => {
                            console.log(response)
                            if (response.ok) {
                                document
                                    .getElementById('emailChangeAlert')
                                    .classList.add('alert', 'alert-success')
                                document.getElementById(
                                    'emailChangeAlert'
                                ).textContent = 'Email successfully updated'
                                document.getElementById(
                                    'emailChangeAlert'
                                ).style.display = 'block'
                                setTimeout(() => {
                                    document.getElementById(
                                        'emailChangeAlert'
                                    ).style.display = 'none'
                                }, 3000)
                            } else {
                                alert('An error occurred while updating email')
                            }
                            emailInput.setAttribute('disabled', '')
                            editEmailBtn.classList.remove('fa-check-circle')
                            editEmailBtn.classList.add('fa-pen-to-square')
                        })
                        // .then((data) => {
                        //     console.log(data)
                        //     document
                        //         .getElementById('emailChangeAlert')
                        //         .classList.add('alert', 'alert-success')
                        //     document.getElementById(
                        //         'emailChangeAlert'
                        //     ).textContent = 'Email successfully updated'
                        //     document.getElementById(
                        //         'emailChangeAlert'
                        //     ).style.display = 'block'
                        //     setTimeout(() => {
                        //         document.getElementById(
                        //             'emailChangeAlert'
                        //         ).style.display = 'none'
                        //     }, 3000)
                        //     emailInput.setAttribute('disabled', '')
                        //     editEmailBtn.classList.remove('fa-check-circle')
                        //     editEmailBtn.classList.add('fa-pen-to-square')
                        // })

                        .catch((error) => {
                            console.error('Error:', error)
                            alert('An error occurred while updating email')
                        })
                }
            } else {
                alert('Please enter a valid email address')
                emailInput.focus()
            }
        } else if (e.key === 'Escape') {
            revertEmail()
        }
    })

    editEmailBtn.onclick = () => {
        if (editEmailBtn.classList.contains('fa-check-circle')) {
            const email = emailInput.value
            const [nick, domain] = email.split('@')
            const maskedNick =
                nick.slice(0, 2) + '*'.repeat(Math.max(0, nick.length - 2))
            emailInput.value = `${maskedNick}@${domain}`
            emailInput.blur()
        } else {
            emailInput.removeAttribute('disabled')
            editEmailBtn.classList.remove('fa-pen-to-square')
            editEmailBtn.classList.add('fa-check-circle')
            emailInput.focus()
        }
    }
}
